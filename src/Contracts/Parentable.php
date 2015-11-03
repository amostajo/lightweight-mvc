<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for object who have parents.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Parentable
{
	/**
	 * Returns an array collection of the implemented class based on parent ID.
	 * Returns children from parent.
	 *
	 * @param int $id Parent post ID.
	 *
	 * @return array
	 */
	public static function from( $id );
}