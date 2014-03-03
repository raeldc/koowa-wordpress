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
				'\\'         => WP_PLUGIN_DIR,
				'Koowa'      => WP_PLUGIN_DIR.'/koowa/library'
			)
		))
	);

	$loader->getLocator('component')->registerNamespace('Koowa', WP_PLUGIN_DIR.'/koowa/components');
	$loader->getLocator('component')->registerNamespace('Application', WP_PLUGIN_DIR.'/koowa/components');

	$manager->registerLocator('lib:object.locator.component');

	// Call the Bootstrapper
	$manager->getObject('com:application.bootstrapper')->bootstrap($application);

	//Setup the request
	$manager->getObject('request')
		->registerApplication($application, '')
		->setApplication($application);

	add_action('init', array(KObjectManager::getInstance()->getObject('application'), 'run'));
}