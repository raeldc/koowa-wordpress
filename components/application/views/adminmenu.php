<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Adminmenu View
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\View\Adminmenu
 */
class ComApplicationViewAdminmenu extends KViewTemplate
{
    protected $_registered_adminmenu = array();
    protected $_registered_submenu   = array();
    protected $_tmpl                 = '';

    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_tmpl = $config->tmpl;
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
        $config->append(array(
            'tmpl' => ''
        ));

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
        if (empty($this->_tmpl)) {
            return;
        }

        $layout = (string) $this->getIdentifier($this->_tmpl);

        //Render the template
        $this->_content = (string) $this->getTemplate()
            ->load((string) $layout.'.html')
            ->compile()
            ->evaluate()
            ->render();
    }

    public function setTmpl($tmpl)
    {
        $this->_tmpl = $tmpl;

        return $this;
    }

    public function getTmpl()
    {
        return $this->getIdentifier($this->_tmpl);
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
