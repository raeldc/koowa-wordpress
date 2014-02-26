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

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options.
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        global $wpdb;

        $config->append(array(
            'table_prefix' => $wpdb->prefix,
            'options' => array(
            	'host'		=> DB_HOST,
    			'username'	=> DB_USER,
    			'password'  => DB_PASSWORD,
    			'database'	=> DB_NAME,
            )
        ));

        parent::_initialize($config);
    }
}
