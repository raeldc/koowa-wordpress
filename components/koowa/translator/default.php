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
final class ComKoowaTranslatorDefault extends ComKoowaTranslatorAbstract implements KObjectInstantiable, KObjectMultiton
{
    /**
     * Create the instance for the multiton
     *
     * @param  KObjectConfigInterface   $config   A ObjectConfig object with configuration options
     * @param  KObjectManagerInterface  $manager  A ObjectInterface object
     * @return KEventPublisher
     */
    public static function getInstance(KObjectConfigInterface $config, KObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists or not
        if (!$manager->isRegistered($config->object_identifier))
        {
            //Create the singleton
            $class    = $manager->getClass($config->object_identifier);
            $instance = new $class($config);
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }
}