<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Error Controller Permission
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Library\Controller
 */
class ComKoowaControllerPermissionError extends KControllerPermissionAbstract
{
    public function canRender()
    {
        return true;
    }
}