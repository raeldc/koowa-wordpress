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
final class ComKoowaTranslatorDefault extends ComKoowaTranslatorAbstract implements KObjectInstantiable, KObjectSingleton
{
    /**
     * List of loaded domains
     * 
     * @var array
     */
    protected $_loaded_domains = array();

    /**
     * Map component names to domain
     * 
     * @var array
     */
    protected $_domain_map = array();

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

    public function loadTranslations($component, $app = null)
    {
        $domain = $this->getComponentDomain($component);

        if(!isset($this->_loaded_domains[$domain]) && load_plugin_textdomain($domain, false, $domain.'/languages/')){
            $this->_loaded_domains[$domain] = true;
        }
    }

    public function registerComponentDomain($component, $domain = '')
    {
        $this->_domain_map[$component] = empty($domain) ? $component: $domain;
    }

    public function getComponentDomain($component)
    {
        if (isset($this->_domain_map[$component])) {
            return $this->_domain_map[$component];
        }

        return 'default';
    }

    public function wptranslate($string, $parameters, $domain = 'default')
    {
        return KTranslatorAbstract::translate(__($string, $domain), $parameters);
    }
}