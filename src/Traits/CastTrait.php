<?php

namespace Amostajo\LightweightMVC\Traits;

/**
 * Trait related to all casting functionality of a model.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
trait CastTrait
{
	/**
	 * Constructs object based on passed object.
	 * Should be an array of attributes or a WP_Post.
	 *
	 * @param mixed $object Array of attributes or a WP_Post.
	 */
	public function from_post( $object )
	{
		if ( is_array( $object ) ) {

			$this->attributes = $object;

		} else if ( is_a( $object, 'WP_Post' ) ) {

			$this->attributes = $object->to_array();

		}

		if ( ! empty( $this->attributes ) ) {

			$this->load_meta();
			
		}

		return $this;
	}

	/**
	 * Cast object into a WP_Post.
	 *
	 * @return object
	 */
	public function to_post()
	{
		return \WP_Post::get_instance( $this->attributes['ID'] );
	}
}