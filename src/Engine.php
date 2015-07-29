<?php

namespace Amostajo\LightweightMVC;

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
	protected $controllers_path;

	/**
	 * Plugin namespace.
	 * @var string
	 */
	protected $namespace;

	/**
	 * View class object.
	 * @var string
	 */
	protected $view;


	/**
 	 * Default engine constructor.
 	 *
 	 * @param string $controllers_path
 	 * @param string $namespace
	 */
	public function __construct( $views_path, $controllers_path, $namespace )
	{
		$this->controllers_path = $controllers_path;
		$this->namespace = $namespace;
		$this->view = new View( $views_path );
	}

	/**
	 * Calls controller and function.
	 * Echos return.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 */
	public function call( $controller_name ) 
	{
		$args = func_get_args();

		unset( $args[0] );

		echo $this->exec( $controller_name, $args );
	}

	/**
	 * Returns controller results.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 */
	public function action( $controller_name ) 
	{
		$args = func_get_args();

		unset( $args[0] );

		return $this->exec( $controller_name, $args );
	}

	/**
	 * Executes controller.
	 * Returns result.
	 *
	 * @param string $controller_name Controller name and method. i.e. DealController@show
	 * @param array  $args 		      Controller parameters.
	 */
	private function exec( $controller_name, $args )
	{
		// Process controller
		$compo = explode( '@', $controller_name );

		if ( count( $compo ) <= 1 ) {

			throw new Exception( sprintf( 'Controller action must be defined in %s.', $controller_name ) );
			
		}

		// Get controller
		require_once(  $this->controllers_path . $compo[0] . '.php' );
		$classname = sprintf( $this->namespace . '\Controllers\%s', $compo[0]);
		$controller = new $classname( $this->view );

		if ( !method_exists( $controller, $compo[1] ) ) {

			throw new Exception( sprintf( 'Controller action "%s" not found in %s.', $compo[1], $compo[0] ) );
			
		}

		return call_user_func_array( [ $controller, $compo[1] ], $args );
	}
}