<?php

namespace Amostajo\LightweightMVC;

/**
 * View class.
 * Extends templating functionality to apply a mini MVC engine.
 *
 * @author Alejandro Mostajo
 * @license MIT
 * @package Amostajo\LightweightMVC
 */
class View
{
	/**
	 * Path to where controllers are.
	 * @var string
	 */
	public static $views_path;

	/**
	 * Returns view with the parameters passed by.
	 *
	 * @param string $view   Name and location of the view within "theme/lucyvegas/views" path.
	 * @param array  $params View parameters passed by.
	 *
	 * @return string
	 */
	public static function get( $view, $params = array() )
	{
		$template = preg_replace( '/\./', '/', $view );

		$theme_path =  get_template_directory() . '/views/' . $template . '.php';

		$plugin_path = self::$views_path . $template . '.php';

		$path = is_readable( $theme_path )
			? $theme_path
			: ( is_readable( $plugin_path )
				? $plugin_path
				: null
			);

		if ( ! empty( $path ) ) {
			extract( $params );
			ob_start();
			include( $path );
			return ob_get_clean();
		} else {
			return;
		}
	}

	/**
	 * Displays view with the parameters passed by.
	 *
	 * @param string $view   Name and location of the view within "theme/lucyvegas/views" path.
	 * @param array  $params View parameters passed by.
	 */
	public static function show( $view, $params = array() )
	{
		echo View::get( $view, $params );
	}
}