<?php

namespace Amostajo\LightweightMVC;

/**
 * Controller base class.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
abstract class Controller
{
	/**
	 * Logged user reference.
	 * @var object
	 */
	protected $user;
	
	/**
	 * Default construct.
	 */
	public function __construct()
	{
		$this->user = \get_userdata( get_current_user_id() );
	}
}