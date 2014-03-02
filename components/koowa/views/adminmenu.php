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
class ComKoowaViewAdminmenu extends KViewTemplate
{
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
            ->render();
    }

    public function setData($data = array())
    {
    	if (!is_string($data)) {
    		parent::setData($data);
    	}

    	return $this;
    }
}
