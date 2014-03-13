<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Default Bootstrapper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Bootstrapper
 */
class ComKoowaBootstrapper extends KObjectBootstrapperComponent
{
    protected $_has_adminmenu;
    /**
     * Constructor.
     *
     * @param   KObjectConfig $config Configuration options
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_has_adminmenu = $config->has_adminmenu;
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'has_adminmenu' => true,
        ));

        parent::_initialize($config);
    }

    public function bootstrap()
    {
        parent::bootstrap();

        if (is_admin() && $this->_has_adminmenu) {
            add_filter('admin_menu', array($this->getAdminmenu(), 'render'));
        }
    }

    public function getAdminmenu()
    {
        $identifier         = $this->getObject('application')->getIdentifier()->toArray();
        $identifier['path'] = array('view');
        $identifier['name'] = 'adminmenu';
        $identifier         = $this->getIdentifier($identifier);

        $tmpl         = $this->getIdentifier()->toArray();
        $tmpl['path'] = array('view');
        $tmpl['name'] = 'adminmenu';
        $tmpl         = $this->getIdentifier($tmpl);

        return $this->getObject($identifier)->setTmpl($tmpl);
    }
}