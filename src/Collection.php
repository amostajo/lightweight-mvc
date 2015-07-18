<?php

namespace Amostajo\LightweightMVC;

use ArrayObject;
use Amostajo\LightweightMVC\Contracts\Sortable as Sortable;
use Amostajo\LightweightMVC\Contracts\JSONable as JSONable;
use Amostajo\LightweightMVC\Contracts\Stringable as Stringable;

/**
 * Holds a colleciton of model results.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
class Collection extends ArrayObject implements Sortable, JSONable, Stringable
{
	/**
	 * Sorts results by specific field and direction.
	 *
	 * @param string $attribute Attribute to sort by.
	 * @param string $sort_flag Sort direction.
	 *
	 * @return this for chaining
	 */
	public function sort_by( $attribute, $sort_flag = SORT_REGULAR )
	{
		$attributes = array();

		for ( $i = count( $this ) -1; $i >= 0; --$i ) {
			$attributes[$this[$i]->$attribute] = $this[$i];
		}

		ksort( $attributes );

		$new = new self();

		foreach ( $attributes as $key => $value ) {
			$new[] = $value;
		}

		return $new;
	}

	/**
	 * Returns json string.
	 *
	 * @param string
	 */
	public function to_json()
	{
		return json_encode( $this );
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
}