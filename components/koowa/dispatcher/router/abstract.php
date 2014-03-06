<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Abstract Dispatcher Router
 *
 * Provides route building and parsing functionality
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Dispatcher\Router
 */
abstract class ComKoowaDispatcherRouterAbstract extends KObject implements ComKoowaDispatcherRouterInterface, KObjectMultiton
{
    /**
     * Function to convert a route to an internal URI
     *
     * @param   HttpUrl  $url  The url.
     * @return  boolean
     */
    public function parse(KHttpUrl $url)
    {
        return true;
    }

    /**
     * Function to convert an internal URI to a route
     *
     * @param   HttpUrl   $url  The internal URL
     * @return  boolean
     */
    public function build(KHttpUrl $url)
    {
        // Build the url : mysite/route/index.php?var=x
        return true;
    }
}
