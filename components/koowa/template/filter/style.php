<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Style Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterStyle extends KTemplateFilterStyle
{
    /**
     * An array of MD5 hashes for loaded style strings
     */
    protected $_loaded = array();

    /**
     * String of Styles
     * @var string
     */
    protected $_styles = '';

    /**
     * Find any virtual tags and render them
     *
     * This function will pre-pend the tags to the content
     *
     * @param string $text  The text to parse
     */
    public function render(&$text)
    {
        $this->_parseTags($text);
    }

    /**
     * Render the tag
     *
     * @param   array   $attribs Associative array of attributes
     * @param   string  $content The tag content
     * @return string
     */
    protected function _renderTag($attribs = array(), $content = null)
    {
        $link      = isset($attribs['src']) ? $attribs['src'] : false;
        $condition = isset($attribs['condition']) ? $attribs['condition'] : false;

        if(!$link)
        {
            $style = parent::_renderTag($attribs, $content);
            $hash  = md5($style.serialize($attribs));

            if (!isset($this->_loaded[$hash]))
            {
                $this->_loaded[$hash] = true;
                $this->_styles .= $style;
                add_action(is_admin() ? 'admin_head' : 'wp_head', array($this, 'renderStyles'));
            }
        }
        else
        {
            if($condition)
            {
                $this->_styles .= parent::_renderTag($attribs, $content);
                add_action(is_admin() ? 'admin_head' : 'wp_head', array($this, 'renderStyles'));
            }
            else
            {
                $hash = md5($link);
                wp_register_style($hash, $link);
                wp_enqueue_style($hash);
            }
        }
    }

    /**
     * Send the generated header styles to the browser
     * @return void
     */
    public function renderStyles()
    {
        echo $this->_styles;
    }
}
