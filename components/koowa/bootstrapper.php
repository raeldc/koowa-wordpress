<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Bootstrapper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Bootstrapper
 */

class ComKoowaBootstrapper extends KObjectBootstrapperComponent
{
    protected function _initialize(KObjectConfig $config)
    {
        global $wpdb;

        $config->append(array(
            'priority' => self::PRIORITY_LOW,
            'aliases'  => array(
                'request' => 'lib:dispatcher.request',
                'lib:database.adapter.mysqli' => 'com:koowa.database.adapter.mysqli'
            ),
            'configs' => array(
                'lib:database.adapter.mysqli' => array(
                    'table_prefix' => $wpdb->prefix,
                    'options' => array(
                        'host'      => DB_HOST,
                        'username'  => DB_USER,
                        'password'  => DB_PASSWORD,
                        'database'  => DB_NAME,
                    )
                )
            )
        ));

        parent::_initialize($config);
    }
}