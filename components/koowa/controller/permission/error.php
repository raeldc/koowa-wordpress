<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Error Controller Permission
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Library\Controller
 */
class ComKoowaControllerPermissionError extends KControllerPermissionAbstract
{
    public function canRender()
    {
        return true;
    }
}