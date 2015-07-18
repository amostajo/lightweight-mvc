<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for objects that can cast to json.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface JSONable
{
	/**
	 * Returns json string.
	 *
	 * @param string
	 */
	public function to_json();
}