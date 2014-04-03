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
    protected $_components = array();

    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_components = $config->components;
    }

    protected function _initialize(KObjectConfig $config)
    {
        global $wpdb;

        $config->append(array(
            'priority' => self::PRIORITY_HIGHEST,
            'aliases'  => array(
                'date'                                   => 'com:koowa.date',
                'request'                                => 'lib:dispatcher.request',
                'document'                               => 'com:application.view.html',
                'translator'                             => 'com:koowa.translator',
                'application'                            => 'com:application.dispatcher.http',
                'event.publisher'                        => 'com:koowa.event.publisher',
                'exception.handler'                      => 'com:koowa.exception.handler',
                'lib:template.locator.component'         => 'com:koowa.template.locator.component',
                'lib:dispatcher.response.transport.http' => 'com:koowa.dispatcher.response.transport.http'
            ),
            'configs' => array(
                'com:application.database.table.pages' => array(
                    'name' => 'koowa_pages'
                ),
                'com:application.database.table.settings' => array(
                    'name'            => 'koowa_settings',
                    'identity_column' => ''
                )
            ),
            'components' => array()
        ));

        if ($wpdb->use_mysqli)
        {
            $config->configs->append(array(
                    'lib:database.adapter.mysqli' => array(
                        'connection'    => $wpdb->dbh,
                        'table_prefix'  => $wpdb->prefix,
                    ),
            ));
        }
        else
        {
            $config->configs->append(array(
                    'lib:database.adapter.mysqli' => array(
                        'table_prefix'  => $wpdb->prefix,
                        'options'       => array(
                            'host'      => DB_HOST,
                            'username'  => DB_USER,
                            'password'  => DB_PASSWORD,
                            'database'  => DB_NAME,
                        )
                    ),
            ));
        }

        parent::_initialize($config);
    }

    public function bootstrap()
    {
        global $wpdb;

        parent::bootstrap();

        if (!$wpdb->use_mysqli) $this->getObject('lib:database.adapter.mysqli')->connect();

        foreach ($this->_components as $component => $config)
        {
            if ($config instanceof KObjectConfigInterface)
            {
                if (!empty($config->path)) {
                    $this->getObject('application')->registerComponent($component, $config->path);
                }

                if (is_admin() && $config->adminmenu) {
                    add_filter('admin_menu', array($this->getObject('application')->getAdminmenu($component), 'render'));
                }
            }
            elseif(is_string($config)) $this->getObject('application')->registerComponent($component, $config);

            $this->getObjectManager()->registerAlias('com:koowa.translator.'.$component, 'translator.'.$component);
        }
    }

    public function getHandle()
    {
        //Prevent recursive bootstrapping
        return null;
    }
}