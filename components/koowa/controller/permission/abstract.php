<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Abstract Controller Permission
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Library\Controller
 */
abstract class ComKoowaControllerPermissionAbstract extends KControllerPermissionAbstract
{
    /**
     * {@inheritdoc}
     */
    public function canAdd()
    {
        return current_user_can('edit_posts');
    }

    /**
     * {@inheritdoc}
     */
    public function canEdit()
    {
        return current_user_can('edit_posts');
    }

    /**
     * {@inheritdoc}
     */
    public function canDelete()
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check if user can perform administrative tasks such as changing configuration options
     *
     * @return  boolean  Can return both true or false.
     */
    public function canAdmin()
    {
        return current_user_can('manage_options');
    }

    /**
     * Check if user can can access a component in the administrator backend
     *
     * @return  boolean  Can return both true or false.
     */
    public function canManage()
    {
        return current_user_can('manage_options');
    }
}