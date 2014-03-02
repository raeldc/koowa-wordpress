<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * View
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\View
 */
class ComKoowaViewHtml extends KViewHtml
{
    /**
     * Constructor
     *
     * @param   KObjectConfig $config Configuration options
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);
        //Add alias filter for editor helper
        //$this->getTemplate()->getFilter('function')->addFunction('@editor', '$this->renderHelper(\'editor.display\', ');
    }
}
