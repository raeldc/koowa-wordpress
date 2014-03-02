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
}
