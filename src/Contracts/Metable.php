<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for objects with Meta.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Metable
{
	/**
	 * Loads meta values into objet.
	 */
	public function load_meta();

	/**
	 * Returns flag indicating if object has meta key.
	 *
	 * @param string $key Key.
	 *
	 * @return bool
	 */
	public function has_meta( $key );

	/**
	 * Gets value from meta.
	 *
	 * @param string $key Key.
	 *
	 * @return mixed.
	 */
	public function get_meta( $key );

	/**
	 * Sets meta value.
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function set_meta( $key, $value );

	/**
	 * Deletes meta..
	 *
	 * @param string $key Key.
	 */
	public function delete_meta( $key );

	/**
	 * Either adds or updates a meta.
	 *
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 */
	public function save_meta( $key, $value );

	/**
	 * Saves all meta values.
	 */
	public function save_meta_all();
}