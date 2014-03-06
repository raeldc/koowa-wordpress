<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Application Bootstrapper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Bootstrapper
 */
class ComApplicationBootstrapper extends KObjectBootstrapperComponent
{
    protected function _initialize(KObjectConfig $config)
    {
        global $wpdb;

        $config->append(array(
            'priority' => self::PRIORITY_HIGHEST,
            'aliases'  => array(
                'date'                                   => 'com:koowa.date',
                'request'                                => 'lib:dispatcher.request',
                'translator'                             => 'com:koowa.translator',
                'application'                            => 'com:application.dispatcher.http',
                'event.publisher'                        => 'com:koowa.event.publisher',
                'exception.handler'                      => 'com:koowa.exception.handler',
                'lib:object.bootstrapper.chain'          => 'com:koowa.object.bootstrapper.chain',
                'lib:template.locator.component'         => 'com:koowa.template.locator.component',
                'lib:dispatcher.response.transport.http' => 'com:koowa.dispatcher.response.transport.http'
            ),
            'configs' => array(
                'lib:database.adapter.mysqli' => array(
                    'table_prefix'  => $wpdb->prefix,
                    'options'       => array(
                        'host'      => DB_HOST,
                        'username'  => DB_USER,
                        'password'  => DB_PASSWORD,
                        'database'  => DB_NAME,
                    )
                ),
            )
        ));

        parent::_initialize($config);
    }

    public function bootstrap()
    {
        parent::bootstrap();

        $this->getObject('lib:database.adapter.mysqli')->connect();

        // Boostrap other koowa extensions
        do_action('koowa_bootstrap');
        $this->getObject('lib:object.bootstrapper.chain')->bootstrap();
    }

    public function getHandle()
    {
        //Prevent recursive bootstrapping
        return null;
    }
}