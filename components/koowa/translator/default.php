<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Translator
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Translator
 */
final class ComKoowaTranslatorDefault extends KTranslatorAbstract implements KObjectInstantiable, KObjectSingleton
{
    /**
     * Force creation of a singleton
     *
     * @param   KObjectConfigInterface  $config   A ObjectConfig object with configuration options
     * @param   KObjectManagerInterface $manager  A ObjectInterface object
     * @return KDispatcherRequest
     */
    public static function getInstance(KObjectConfigInterface $config, KObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered('translator'))
        {
            $class     = $manager->getClass($config->object_identifier);
            $instance  = new $class($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias($config->object_identifier, 'translator');
        }

        return $manager->getObject('translator');
    }

    /**
     * Load the component language files.
     *
     * @param string|KObjectIdentifier $extension Extension identifier or name (e.g. com_files)
     * @param string $app Application. Leave blank for current one.
     *
     * @return boolean
     */
    public function loadTranslations($extension, $app = null)
    {
        return false;
    }

    public function translate($string, array $parameters = array())
    {
        return __( $string);
    }
}