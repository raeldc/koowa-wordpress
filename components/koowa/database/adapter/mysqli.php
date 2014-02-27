<?php

/**
 * MySQLi Database Adapter
 *
 * @author  Israel Canasa <https://github.com/raeldc>
 * @package Wordpress\Database\Adapter
 */
class ComKoowaDatabaseAdapterMysqli extends KDatabaseAdapterMysqli implements KObjectInstantiable, KObjectMultiton
{
    /**
     * Force creation of a singleton
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
            $instance->connect();
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }
}
