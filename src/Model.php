<?php

namespace Amostajo\LightweightMVC;

use Amostajo\LightweightMVC\Contracts\Modelable as Modelable;
use Amostajo\LightweightMVC\Contracts\Findable as Findable;
use Amostajo\LightweightMVC\Contracts\Metable as Metable;
use Amostajo\LightweightMVC\Contracts\Parentable as Parentable;
use Amostajo\LightweightMVC\Contracts\PostCastable as PostCastable;
use Amostajo\LightweightMVC\Contracts\Arrayable as Arrayable;
use Amostajo\LightweightMVC\Contracts\JSONable as JSONable;
use Amostajo\LightweightMVC\Contracts\Stringable as Stringable;
use Amostajo\LightweightMVC\Traits\MetaTrait as MetaTrait;
use Amostajo\LightweightMVC\Traits\CastTrait as CastTrait;

/**
 * Abstract Model Class.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
abstract class Model implements Modelable, Findable, Metable, Parentable, PostCastable, Arrayable, JSONable, Stringable
{
	use MetaTrait, CastTrait;

	/**
	 * Post type.
	 * @var string
	 */
	protected $type = 'post';

	/**
	 * Default post status.
	 * @var string
	 */
	protected $status = 'draft';

	/**
	 * Posts are moved to trash when on soft delete.
	 * @var bool
	 */
	protected $forceDelete = false;

	/**
	 * Attributes in model.
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Field aliases.
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * Attributes and aliases hidden from print.
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * Default constructor.
	 */
	public function __construct( $id = 0 )
	{
		if ( ! empty( $id )  )
			$this->load($id);
	}

	/**
	 * Loads model from db.
	 *
	 * @param mixed $id Rercord ID.
	 */
	public function load( $id )
	{
		$this->attributes = \get_post( $id, ARRAY_A );

		$this->load_meta();
	}

	/**
	 * Saves current model in the db.
	 *
	 * @return mixed.
	 */
	public function save()
	{
		if ( ! $this->is_loaded() ) return false;

		$this->fill_defaults();

		$error = null;

		$id = wp_insert_post( $this->attributes, $error );

		if ( ! empty( $id ) ) {

			$this->attributes['ID'] = $id;

			$this->save_meta_all();

		}

		return $error === false ? true : $error;
	}

	/**
	 * Deletes current model in the db.
	 *
	 * @return mixed.
	 */
	public function delete()
	{
		if ( ! $this->is_loaded() ) return false;

		$error = wp_delete_post( $this->attributes['ID'], $this->forceDelete );

		return $error !== false;
	}

	/**
	 * Returns flag indicating if object is loaded or not.
	 *
	 * @return bool
	 */
	public function is_loaded()
	{
		return !empty( $this->attributes );
	}

	/**
	 * Getter function.
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get( $property )
	{
		$property = $this->get_alias_property( $property );

		if ( preg_match( '/meta_/', $property ) ) {

			return $this->get_meta( preg_replace( '/meta_/', '', $property ) );

		}

		if ( preg_match( '/func_/', $property ) ) {

			$function_name = preg_replace( '/func_/', '', $property );

			return $this->$function_name();
		}

		if ( array_key_exists( $property, $this->attributes ) ) {

			return $this->attributes[$property];

		} else {

			switch ($property) {

				case 'type':
				case 'status':
					return $this->$property;

				case 'post_content_filtered':
					$content = \apply_filters( 'the_content', $this->attributes[$property] );
					$content = str_replace( ']]>', ']]&gt;', $content );
					return $content;

			}

		}

		return null;
	}

	/**
	 * Setter function.
	 *
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return object
	 */
	public function __set( $property, $value )
	{
		$property = $this->get_alias_property( $property );

		if ( preg_match( '/meta_/', $property ) ) {

			return $this->set_meta( preg_replace( '/meta_/', '', $property ), $value );

		} else {

			$this->attributes[$property] = $value;

		}
	}

	/**
	 * Returns object converted to array.
	 *
	 * @param array.
	 */
	public function to_array()
	{
		$output = array();

		// Attributes
		foreach ($this->attributes as $property => $value) {
			$output[$this->get_alias($property)] = $value;
		}

		// Meta
		foreach ($this->meta as $key => $value) {
			$alias = $this->get_alias('meta_' . $key);
			if ( $alias !=  'meta_' . $key) {
				$output[$alias] = $value;
			}
		}

		// Functions
		foreach ($this->aliases as $alias => $property) {
			if ( preg_match( '/func_/', $property ) ) {
				$function_name = preg_replace( '/func_/', '', $property );
				$output[$alias] = $this->$function_name();
			}
		}

		// Hidden
		foreach ( $this->hidden as $key ) {
			unset( $output[$key] );
		}

		return $output;
	}

	/**
	 * Returns json string.
	 *
	 * @param string
	 */
	public function to_json()
	{
		return json_encode( $this->to_array() );
	}

	/**
	 * Returns string.
	 *
	 * @param string
	 */
	public function __toString()
	{
		return $this->to_json();
	}

	/**
	 * Fills default when about to create object
	 */
	private function fill_defaults()
	{
		if ( ! array_key_exists('ID', $this->attributes) ) {

			$this->attributes['post_type'] = $this->type;

			$this->attributes['post_status'] = $this->status;

		}
	}

	/**
	 * Returns property mapped to alias.
	 *
	 * @param string $alias Alias.
	 *
	 * @return string
	 */
	private function get_alias_property( $alias )
	{
		if ( array_key_exists( $alias, $this->aliases ) )
			return $this->aliases[$alias];

		return $alias;
	}

	/**
	 * Returns alias name mapped to property.
	 *
	 * @param string $property Property.
	 *
	 * @return string
	 */
	private function get_alias( $property )
	{
		if ( in_array( $property, $this->aliases ) )
			return array_search( $property, $this->aliases );

		return $property;
	}
}
