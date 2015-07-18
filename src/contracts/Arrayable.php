<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for objects that can cast to arrays.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Arrayable
{
	/**
	 * Returns object converted to array.
	 *
	 * @param array.
	 */
	public function to_array();
}