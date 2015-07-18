<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for findable objects.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Findable
{
	/**
	 * Finds record based on an ID.
	 * @param mixed $id Record ID.
	 */
	public static function find( $id );
}