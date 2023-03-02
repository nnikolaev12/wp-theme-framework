<?php

namespace NyxitSoft;

defined( 'ABSPATH' ) ?: exit;

trait Singleton
{
	/** @var Singleton|null */
	protected static $instance = null;

	protected function __construct() {}

	/**
	 * Prevent object properties clonning
	 *
	 * Do not call this class since it is a PHP magic method
	 */
	protected function __clone() {}

	/**
	 * Returns class instance
	 *
	 * @return $this|null
	 */
	public static function getInstance()
	{
		if ( self::$instance === null ) {
			$class = get_called_class();

			self::$instance = new $class();
		}

		return self::$instance;
	}
}