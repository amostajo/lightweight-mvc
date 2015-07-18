<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for Models.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Modelable
{
	/**
	 * Loads model from db.
	 */
	public function load( $id );

	/**
	 * Saves current model in the db.
	 *
	 * @return mixed.
	 */
	public function save();
	
	/**
	 * Deletes current model in the db.
	 *
	 * @return mixed.
	 */
	public function delete();
}