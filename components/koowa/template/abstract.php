<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel Canasa and WIZMEDIA. (http://www.wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/raeldc/koowa-wordpress for the canonical source repository
 */

/**
 * Abstract Template
 *
 * @author  Israel Canasa <https://github.com/raeldc>
 * @package Koowa\Wordpress\Template
 */
abstract class ComKoowaTemplateAbstract extends KTemplateAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  KObjectConfig $config  An optional KObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'locators' => array('com' => 'com:koowa.template.locator.component')
        ));

        parent::_initialize($config);
    }
}
