<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Abstract Template
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Template
 */
abstract class ComKoowaTemplateAbstract extends KTemplateAbstract
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'filters' => array('title')
        ));

        parent::_initialize($config);
    }
}