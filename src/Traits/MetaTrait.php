<?php

namespace Amostajo\LightweightMVC\Traits;

/**
 * Trait related to all meta functionality of a model.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 * @version 1.0.1
 */
trait MetaTrait
{
	/**
	 * Meta data.
	 * @since 1.0.0
	 * @var array
	 */
	protected $meta = array();

	/**
	 * Loads meta values into objet.
	 * @since 1.0.0
	 */
	public function load_meta()
	{
		if ( empty( $this->attributes ) ) return;

		foreach ( get_post_meta( $this->attributes['ID'] ) as $key => $value ) {
			if ( ! preg_match( '/_wp_/', $key )
				|| in_array( 'meta_' . $key, $this->aliases )
			) {
				$value = $value[0];

				$this->meta[$key] = is_string( $value )
					? ( preg_match( '/_wp_/', $key )
						? $value
						: json_decode( $value )
					)
					: ( is_integer( $value )
						? intval( $value )
						: floatval( $value )
					);
			}
		}
	}

	/**
	 * Returns flag indicating if object has meta key.
	 * @since 1.0.0
	 *
	 * @param string $key Key.
	 *
	 * @return bool
	 */
	public function has_meta( $key )
	{
		return array_key_exists( $key, $this->meta );
	}

	/**
	 * Sets meta value.
	 * @since 1.0.0
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function set_meta( $key, $value )
	{
		$this->meta[$key] = $value;
	}

	/**
	 * Gets value from meta.
	 * @since 1.0.0
	 *
	 * @param string $key Key.
	 *
	 * @return mixed.
	 */
	public function get_meta( $key )
	{
		return $this->has_meta( $key ) ? $this->meta[$key] : null;
	}

	/**
	 * Deletes meta.
	 * @since 1.0.0
	 *
	 * @param string $key Key.
	 */
	public function delete_meta( $key )
	{
		if ( ! $this->has_meta( $key ) ) return;

		delete_post_meta( $this->attributes['ID'], $key );

		unset( $this->meta[$key] );
	}

	/**
	 * Either adds or updates a meta.
	 * @since 1.0.0
	 * @since 1.0.1 Hot fix, saves only registered meta.
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function save_meta( $key, $value, $update_array = true )
	{
		if ( preg_match( '/_wp_/', $key ) ) return;

		if ( ! in_array( 'meta_' . $key, $this->aliases ) ) return;
		
		if ( $update_array )
			$this->meta[$key] = $value;

		\update_post_meta( 
			$this->attributes['ID'],
			$key,
			is_numeric( $value )
				? $value
				: json_encode( $value )
		);
	}

	/**
	 * Saves all meta values.
	 * @since 1.0.0
	 */
	public function save_meta_all()
	{
		foreach ( $this->meta as $key => $value ) {
			$this->save_meta( $key, $value, false );
		}
	}
}