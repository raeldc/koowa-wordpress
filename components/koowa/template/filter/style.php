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
        $link         = isset($attribs['src']) ? $attribs['src'] : false;
        $condition    = isset($attribs['condition']) ? $attribs['condition'] : false;
        $dependencies = array();

        // Get dependencies
        if (isset($attribs['dependencies']))
        {
            $dependencies = explode(',', $attribs['dependencies']);
            array_walk($dependencies, 'trim');
        }

        if (!$link || $condition) {
            $this->getObject('document')->addStyle(parent::_renderTag($attribs, $content));
        }
        else
        {
            $this->getObject('document')->addStyle(array_merge(
                $attribs, array(
                    'dependencies' => $dependencies
                )
            ));
        }
    }
}
