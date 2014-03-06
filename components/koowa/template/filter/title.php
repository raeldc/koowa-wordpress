<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Title Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterTitle extends KTemplateFilterTitle
{
    protected $_title = '';
    protected $_attribs = array();

    /**
     * Render the tag
     *
     * @param 	array	$attribs Associative array of attributes
     * @param 	string	$content The tag content
     * @return string
     */
    protected function _renderTag($attribs = array(), $content = null)
    {
        $this->_title   = $content;
        $this->_attribs = $attribs;

    	add_filter( 'the_title',  array($this, 'setTitle'), 10, 1);
    }

    public function setTitle($current_title)
    {
        if (isset($this->_attribs['concat']) == 'prepend') {
            $current_title = $this->_title.$current_title;
        }elseif (isset($this->_attribs['concat']) == 'append') {
            $current_title = $current_title.$this->_title;
        }
        else $current_title = $this->_title;

        return $current_title;
    }
}
