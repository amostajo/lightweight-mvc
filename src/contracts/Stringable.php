<?php

namespace Amostajo\LightweightMVC\Contracts;

/**
 * Interface contract for objects that can cast to string.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
interface Stringable
{
	/**
	 * Returns string.
	 *
	 * @param string
	 */
	public function __toString();
}