<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Url Template Filter
 *
 * Filter allows to create url aliases that are replaced on compile and render.
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterUrl extends KTemplateFilterUrl
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $dir = $this->getObject('application')->getComponentDir($this->getIdentifier()->package);

        $config->append(array(
            'aliases' => array(
                'script://' => str_replace('wp-admin/', '', $this->getObject('request')->getBaseUrl().'/wp-content/plugins/'.$dir.'/media/js/'),
                'css://'    => str_replace('wp-admin/', '', $this->getObject('request')->getBaseUrl().'/wp-content/plugins/'.$dir.'/media/css/'),
                'img://'    => str_replace('wp-admin/', '', $this->getObject('request')->getBaseUrl().'/wp-content/plugins/'.$dir.'/media/images/'),
            ),
        ));

        parent::_initialize($config);
    }
}