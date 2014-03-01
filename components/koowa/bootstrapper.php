<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Default Bootstrapper
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Bootstrapper
 */
abstract class ComKoowaBootstrapper extends KObjectBootstrapperComponent
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'admin_menu_view' => 'adminmenu',
        ));

        parent::_initialize($config);
    }
}