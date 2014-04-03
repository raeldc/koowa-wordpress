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
 * @package Koowa\Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterScript extends KTemplateFilterScript
{
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
        $location     = !isset($attribs['location']) || $attribs['location'] !== 'header' ? 'footer': 'header';
        $link         = isset($attribs['src']) ? $attribs['src'] : false;
        $dependencies = array();

        unset($attribs['location']);

        // Get dependencies
        if (isset($attribs['dependencies']))
        {
            $dependencies = explode(',', $attribs['dependencies']);
            array_walk($dependencies, 'trim');
            unset($attribs['dependencies']);
        }

        if(!$link)
        {
            $attributes = $this->buildAttributes($attribs);
            $html  = '<script '.$attributes.'>'."\n";
            $html .= trim($content);
            $html .= '</script>'."\n";

            $this->getObject('document')->addScript($html, $location, $dependencies);
        }
        else
        {
            unset($attribs['src']);
            unset($attribs['condition']);

            $name = (isset($attribs['name'])) ? $attribs['name'] : str_replace('.js', '', end(explode('/', $link)));
            unset($attribs['name']);

            $version = isset($attribs['version']) ? $attribs['version']: false;
            unset($attribs['version']);

            $this->getObject('document')->addScript(array(
                'name'         => $name,
                'link'         => $link,
                'dependencies' => $dependencies,
                'version'      => $version,
                'footer'       => $location === 'footer'
            ));
        }
    }
}
