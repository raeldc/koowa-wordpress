<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Abstract Dispatcher Router
 *
 * Provides route buidling and parsing functionality
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Bootstrapper
 */
interface ComKoowaDispatcherRouterInterface
{
    /**
     * Function to convert a route to an internal URI
     *
     * @param   HttpUrl  $url  The url.
     * @return  boolean
     */
	public function parse(KHttpUrl $uri);

    /**
     * Function to convert an internal URI to a route
     *
     * @param	HttpUrl   $url	The internal URL
     * @return	boolean
     */
	public function build(KHttpUrl $url);
}
