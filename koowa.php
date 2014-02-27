<?php
/**
 * @package Koowa
 * @version .01
 */
/*
Plugin Name: Koowa for Wordpress
Plugin URI: http://github.com/raeldc/koowa-wordpress
Description: Use Koowa Framework to develop Plugins in Wordpress
Author: Israel D. Canasa
Version: 0.1
Author URI: http://israelcanasa.com/
*/

/**
 * Initialize
 */
add_action('init', 'koowa_bootstrap');

function koowa_bootstrap()
{
	$path = WP_PLUGIN_DIR.'/koowa/library/koowa.php';

	if (!file_exists($path))
		return false;

	require_once $path;

	$application = 'site';

	Koowa::getInstance(array(
		'cache-namespace' => 'koowa-'.$application.'-'.md5(AUTH_KEY),
		'cache-enabled' => false
	));

	$manager = KObjectManager::getInstance();
	$loader = $manager->getClassLoader();

	//Application Basepaths
	$loader->registerBasepath('site', ABSPATH);

	//Component Locator
	require_once dirname(__FILE__).'/components/koowa/class/locator/component.php';

	$loader->registerLocator(
		new ComKoowaClassLocatorComponent(array(
			'namespaces' => array(
				'\\'	=> WP_PLUGIN_DIR,
				'Koowa' => WP_PLUGIN_DIR.'/koowa/library'
			)
		))
	);

	$loader->getLocator('component')->registerNamespace('Koowa', WP_PLUGIN_DIR.'/koowa/components');

	$manager->registerLocator('lib:object.locator.component');

	// Call the Bootstrapper
	$manager->getObject('com:koowa.bootstrapper')->bootstrap($application);

	//Setup the request
	$manager->getObject('request')
		->registerApplication($application, '')
		->setApplication($application);
}

function koowa_dispatch()
{
	$manager = KObjectManager::getInstance();
	$request = $manager->getObject('request')->getQuery();

	$uri = $request->page;
	list($component, $view) = explode('/', $uri);

	// Set the view but don't override it if it's already there
	$request->set('view', $view, false);

	$manager->getObject('com:'.$component.'.dispatcher.http')->dispatch();
}