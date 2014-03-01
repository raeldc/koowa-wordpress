<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Application Bootstrapper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Bootstrapper
 */
class ComApplicationBootstrapper extends KObjectBootstrapperComponent
{
    protected function _initialize(KObjectConfig $config)
    {
        global $wpdb;

        $config->append(array(
            'priority' => self::PRIORITY_HIGHEST,
            'aliases'  => array(
                'request'                                => 'lib:dispatcher.request',
                'application'                            => 'com:application.dispatcher.http',
                'lib:database.adapter.mysqli'            => 'com:koowa.database.adapter.mysqli',
                'lib:template.locator.component'         => 'com:koowa.template.locator.component',
                'translator'                             => 'com:koowa.translator',
                'exception.handler'                      => 'com:koowa.exception.handler',
                'date'                                   => 'com:koowa.date',
                'event.publisher'                        => 'com:koowa.event.publisher',
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
        $chain = $this->getObject('lib:object.bootstrapper.chain');

        //Application components
        $directory = WP_PLUGIN_DIR;
        foreach ($this->getComponents($directory) as $component)
        {
            if (!in_array($component.'/'.$component.'.php', get_option('active_plugins'))) {
                continue;
            }

            if (file_exists($directory.'/'.$component.'/bootstrapper.php') && ($bootstrapper = $this->getBootstrapper($component, false))) {
                $chain->addBootstrapper($bootstrapper);
            }
        }

        $chain->bootstrap();

        parent::bootstrap();
    }

    public function getBootstrapper($name, $fallback = true)
    {
        $bootstrapper = null;

        //Register the bootstrapper
        $identifier = 'com:'.$name.'.bootstrapper';
        if($this->getObjectManager()->getClass($identifier, $fallback)) {
            $bootstrapper = $this->getObject($identifier);
        }

        return $bootstrapper;
    }

    public function getComponents($directory)
    {
        $components = array();
        foreach (new DirectoryIterator($directory) as $dir)
        {
            //Only get the component directory names
            if ($dir->isDot() || !$dir->isDir() || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                continue;
            }

            $components[] = (string)$dir;
        }

        return $components;
    }

    public function getHandle()
    {
        //Prevent recursive bootstrapping
        return null;
    }
}