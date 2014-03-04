<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * View
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\View
 */
class ComKoowaViewHtml extends KViewHtml
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'template_filters' => array('help')
        ));

        parent::_initialize($config);
    }

    /**
     * Get a route based on a full or partial query string
     *
     * 'option', 'view' and 'layout' can be omitted. The following variations will all result in the same route :
     *
     * - foo=bar
     * - option=com_mycomp&view=myview&foo=bar
     *
     * In templates, use @route()
     *
     * @param   string|array $route  The query string or array used to create the route
     * @param   boolean      $fqr    If TRUE create a fully qualified route. Defaults to FALSE.
     * @param   boolean      $escape If TRUE escapes the route for xml compliance. Defaults to TRUE.
     * @return  KHttpUrl     The route
     */
    public function getRoute($route = '', $fqr = false, $escape = false)
    {
        //Parse route
        $parts = array();

        //@TODO : Check if $route if valid. Throw exception if not.
        if(is_string($route)) {
            parse_str(trim($route), $parts);
        } else {
            $parts = $route;
        }

        //Check to see if there is component information in the route if not add it
        if (!isset($parts['page'])) {
            $parts['page'] = $this->getIdentifier()->package;
        }

        //Add the view information to the route if it's not set
        if (!isset($parts['view'])) {
            $parts['view'] = $this->getName();
        }

        //Add the format information to the route only if it's not 'html'
        if (!isset($parts['format']) && $this->getIdentifier()->name !== 'html') {
            $parts['format'] = $this->getIdentifier()->name;
        }

        //Add the model state only for routes to the same view
        if ($parts['page'] == $this->getIdentifier()->package && $parts['view'] == $this->getName())
        {
            $states = array();
            foreach($this->getModel()->getState() as $name => $state)
            {
                if($state->default != $state->value && !$state->internal) {
                    $states[$name] = $state->value;
                }
            }

            $parts = array_merge($states, $parts);
        }

        if (!isset($parts['tmpl']) && $tmpl = $this->getObject('request')->getQuery()->get('tmpl', 'cmd')) {
            $parts['tmpl'] = $tmpl;
        }

        // Push option and view to the beginning of the array for easy to read URLs
        $parts = array_merge(array(
            'page' => null,
            'view'   => null
        ), $parts);

        $route = clone $this->getObject('request')->getUrl();
        $route->setQuery($parts);

        //Add the host and the schema
        if ($fqr === true) {
            return $route->toString(KHttpUrl::AUTHORITY);
        }

        return urldecode($route->toString(KHttpUrl::FULL));
    }
}
