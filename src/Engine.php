<?php

namespace Amostajo\LightweightMVC;

use Amostajo\LightweightMVC\;

/**
 * MVC mini engine.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
class Engine
{
	/**
	 * Path to where controllers are.
	 * @var string
	 */
	public static $controllers_path;

	/**
	 * Plugin namespace.
	 * @var string
	 */
	public static $namespace;

	/**
	 * Calls controller and function.
	 * Echos return.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 */
	public static function call( $controller_name ) 
	{
		$args = func_get_args();

		unset( $args[0] );

		echo self::exec( $controller_name, $args );
	}

	/**
	 * Returns controller results.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 */
	public static function action( $controller_name ) 
	{
		$args = func_get_args();

		unset( $args[0] );

		return self::exec( $controller_name, $args );
	}

	/**
	 * Executes controller.
	 * Returns result.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 * @param array  $args 		      Controller parameters.
	 */
	public static function exec( $controller_name, $args )
	{
		// Process controller
		$compo = explode( '@', $controller_name );

		if ( count( $compo ) <= 1 ) {

			throw new Exception( sprintf( 'Controller action must be defined in %s.', $controller_name ) );
			
		}

		// Get controller
		require_once(  self::$controllers_path . $compo[0] . '.php' );
		$classname = sprintf( $namespace . '\Controllers\%s', $compo[0]);
		$controller = new $classname();

		if ( !method_exists( $controller, $compo[1] ) ) {

			throw new Exception( sprintf( 'Controller action "%s" not found in %s.', $compo[1], $compo[0] ) );
			
		}

		return call_user_func_array( [ $controller, $compo[1] ], $args );
	}
}