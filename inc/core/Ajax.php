<?php

namespace NyxitSoft;

defined( 'ABSPATH' ) ?: exit;

class Ajax
{
	use Singleton;

	/**
	 * Registered endpoints
	 *
	 * @var array
	 */
	protected $endpoints = [];

	/**
	 * Registered localized vars
	 *
	 * @var array
	 */
	protected $localized_vars = [];

	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
	}

	/**
	 * Adds a new AJAX endpoint
	 *
	 * @param string   $action
	 * @param callable $callback
	 */
	public static function addEndpoint( $action, $callback)
	{
		// Store endpoint in class
		array_push( self::getInstance()->endpoints, [
			'action'    => $action,
			'callback'  => $callback
		]);

		// Register endpoint in WordPress
		add_action( "wp_ajax_$action", $callback );
		add_action( "wp_ajax_nopriv_$action", $callback );
	}

	/**
	 * Removes a registered AJAX endpoint
	 *
	 * @param $action
	 */
	public static function removeEndpoint( $action, $callback, $priority = 10 )
	{
		$endpoints = self::getInstance()->endpoints;

		// Remove endpoint from class if exists
		foreach ( $endpoints as $key => $endpoint ) {
			if ( $endpoint['action'] === $action ) {
				unset ( self::getInstance()->endpoints[$key] );
			}
		}

		// Unregister endpoint in WordPress
		remove_action( "wp_ajax_$action", $callback, $priority );
		remove_action( "wp_ajax_nopriv_$action", $callback, $priority );
	}

	/**
	 * Returns registered AJAX endpoints
	 *
	 * @return mixed
	 */
	public static function getEndpoints()
	{
		return self::getInstance()->endpoints;
	}

	/**
	 * Adds a new localized var
	 *
	 * @param $key
	 * @param $value
	 */
	public static function addLocalizedVar( $key, $value )
	{
		self::getInstance()->localized_vars = array_merge( self::getInstance()->localized_vars, [
			$key => $value
		]);
	}

	/**
	 * Removes registered localized var
	 *
	 * @param $key
	 */
	public static function removeLocalizedVar( $key )
	{
		foreach ( self::getInstance()->localized_vars as $var_key => $var_value ) {
			if ( $key === $var_key ) {
				unset( self::getInstance()->localized_vars[$key] );
			}
		}
	}

	/**
	 * Returns registered localized vars
	 *
	 * @return array
	 */
	public static function getLocalizedVars()
	{
		return self::getInstance()->localized_vars;
	}

	/**
	 * Crate and localize nonce
	 */
	public static function localizeNonce()
	{
		$nonce = [];

		foreach ( self::getInstance()->endpoints as $endpoint ) {
			$action = $endpoint['action'];

			$nonce[$action] = wp_create_nonce( $action );
		}

		self::addLocalizedVar( 'nonce', $nonce );
	}

	/**
	 * Passes AJAX endpoints nonce to front-end
	 */
	public function enqueueScripts()
	{
		self::addLocalizedVar( 'ajax_url', admin_url( 'admin-ajax.php' ) );

		self::localizeNonce();

		wp_register_script( 'nyxit-endpoints', get_stylesheet_directory_uri() . '/assets/js/ajax.js', array('jquery', 'wp-util') );

		wp_localize_script(
			'nyxit-endpoints',
			'nyxit_ajax_endpoints',
			$this->localized_vars
		);

		wp_enqueue_script( 'nyxit-endpoints' );
	}
}