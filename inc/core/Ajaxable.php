<?php

namespace NyxitSoft;

trait Ajaxable
{
	public function enableAjax()
	{
		add_action( 'init', array( $this, 'registerAjaxEndpoints' ) );

		add_action( 'init', array( $this, 'registerLocalizedVars' ) );

		add_action( 'wp_footer', array( $this, 'outputScriptTemplates' ) );
	}

	/**
	 * Outputs registered front-end script templates
	 *
	 * The class using the trait must be property $script_templates
	 * containing the theme relative path to templates.
	 *
	 * @hook wp_footer
	 */
	public function outputScriptTemplates()
	{
		if ( ! property_exists( $this, 'script_templates' ) ) {
			return;
		}

		foreach ( $this->script_templates as $template ) {
			get_template_part( $template );
		}
	}

	/**
	 * Registers objects AJAX endpoints
	 *
	 * The class using the trait must have getAjaxEndpoints() method
	 * defined and return an array with endpoints to be registered.
	 */
	public function registerAjaxEndpoints()
	{
		if ( ! method_exists( $this, 'getAjaxEndpoints' ) ) {
			return;
		}

		foreach( $this->getAjaxEndpoints() as $action => $callback ) {
			Ajax::addEndpoint( $action, $callback );
		}
	}

	/**
	 * Registers localized vars on the front-end
	 *
	 * The class implementing the trait must have getLocalizedVars() method
	 * defined and return an array with key => var pairs to be registered.
	 */
	public function registerLocalizedVars()
	{
		if ( ! method_exists( $this, 'getLocalizedVars' ) ) {
			return;
		}

		foreach( $this->getLocalizedVars() as $key => $var ) {
			Ajax::addLocalizedVar( $key, $var );
		}
	}
}