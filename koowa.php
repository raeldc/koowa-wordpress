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
add_action('plugins_loaded', 'koowa_bootstrap');

function koowa_bootstrap()
{
	$path = WP_PLUGIN_DIR.'/koowa/library/koowa.php';

	if (!file_exists($path))
		return false;

	require_once $path;

	$application = is_admin() ? 'admin' : 'site';

	Koowa::getInstance(array(
		'cache_namespace' => 'koowa-'.$application,
		'cache_enabled' => extension_loaded('apc')
	));

	$manager = KObjectManager::getInstance();
	$loader = $manager->getClassLoader();

	//Application Basepaths
	$loader->registerBasepath('site', 'site');
	$loader->registerBasepath('admin', 'admin');

	//Component Locator
	require_once dirname(__FILE__).'/components/koowa/class/locator/component.php';

	$loader->registerLocator(
		new ComKoowaClassLocatorComponent(array(
			'namespaces' => array(
				'\\'          => __DIR__,
			)
		))
	);

	$manager->registerLocator('lib:object.locator.component');

    // Boostrap other koowa extensions
    do_action('koowa_bootstrap');

	// Call the Bootstrapper
	$manager->getObject('com:application.bootstrapper')->bootstrap();

	// Boostrap other koowa extensions
    do_action('koowa_bootstrapped');

	//Setup the request
	$manager->getObject('request')
		->registerApplication('site', '')
		->registerApplication('admin', '/wp-admin')
		->setApplication($application);

	add_action('init', array($manager->getObject('application'), 'run'));

	KStringInflector::addWord('settings', 'settings');
}