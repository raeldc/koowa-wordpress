<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Script Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterScript extends KTemplateFilterScript
{
    protected $_footer_scripts = '';
    protected $_header_scripts = '';

    /**
     * An array of MD5 hashes for loaded script strings
     */
    protected $_loaded = array();

    /**
     * Find any virtual tags and render them
     *
     * This function will pre-pend the tags to the content
     *
     * @param string $text  The text to parse
     */
    public function render(&$text)
    {
        $scripts = $this->_parseTags($text);

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
        global $wp_scripts;

        $link = isset($attribs['src']) ? $attribs['src'] : false;
        $condition = isset($attribs['condition']) ? $attribs['condition'] : false;

        if(!$link)
        {
            $location = (isset($attribs['location'])) ? $attribs['location'] : '';
            unset($attribs['location']);

            $attributes = $this->buildAttributes($attribs);
            $html  = '<script '.$attributes.'>'."\n";
            $html .= trim($content);
            $html .= '</script>'."\n";

            if ($location == 'header')
            {
                if (empty($this->_header_scripts)) {
                    add_action(is_admin() ? 'admin_head' : 'wp_head', array($this, 'renderHeaderScripts'));
                }

                $this->_header_scripts .= $html;
            }
            else 
            {
                if (empty($this->_footer_scripts)) {
                    add_action(is_admin() ? 'admin_footer' : 'wp_footer',  array($this, 'renderFooterScripts'));
                }

                $this->_footer_scripts .= $html;
            }
        }
        else
        {
            /*
            unset($attribs['src']);
            unset($attribs['condition']);
            $attribs = $this->buildAttributes($attribs);

            if($condition)
            {
                $html  = '<!--['.$condition.']>';
                $html .= '<script src="'.$link.'" '.$attribs.' /></script>'."\n";
                $html .= '<![endif]-->';
            }
            else $html  = '<script src="'.$link.'" '.$attribs.' /></script>'."\n";
            */
        }
    }

    public function renderHeaderScripts()
    {
        echo $this->_header_scripts;
    }

    public function renderFooterScripts()
    {
        echo $this->_footer_scripts;
    }
}
