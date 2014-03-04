<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Adminmenu View
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\View\Adminmenu
 */
class ComKoowaViewAdminmenu extends KViewTemplate implements KObjectInstantiable, KObjectSingleton
{
    protected $_registered_adminmenu = array();
    protected $_registered_submenu = array();

    /**
     * Force creation of a singleton
     *
     * @param  KObjectConfigInterface  $config  Configuration options
     * @param  KObjectManagerInterface $manager A KObjectManagerInterface object
     * @return KDispatcherDefault
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

	/**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        parent::_initialize($config);

        $config->template_filters = array('adminmenu', 'submenu');
        $config->auto_fetch = false;
    }

    /**
     * Return the views output
     *
     * @param KViewContext	$context A view context object
     * @return string  The output of the view
     */
    protected function _actionRender(KViewContext $context)
    {
        //Handle partial layout paths
        $identifier = $this->getIdentifier()->toArray();
        $identifier['path'] = array('view');
        $identifier['name'] = 'adminmenu';

        $layout = (string) $this->getIdentifier($identifier);

        //Render the template
        $this->_content = (string) $this->getTemplate()
            ->load((string) $layout.'.html')
            ->compile()
            ->evaluate()
            ->render();
    }

    public function registerAdminmenu($page)
    {
        $this->_registered_adminmenu[$page] = true;
    }

    public function registerSubmenu($page)
    {
        $this->_registered_submenu[$page] = true;
    }

    public function hasMenu($page)
    {
        return isset($this->_registered_adminmenu[$page]) || isset($this->_registered_submenu[$page]);
    }

    public function setData($data = array())
    {
    	if (!is_string($data)) {
    		parent::setData($data);
    	}

    	return $this;
    }
}
