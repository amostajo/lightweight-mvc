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
	 * View class object.
	 * @var object
	 */
	protected $view;
	
	/**
	 * Default construct.
	 *
	 * @param object $view View class object.
	 */
	public function __construct( $view )
	{
		$this->user = \get_userdata( get_current_user_id() );
		$this->view = $view;
	}
}