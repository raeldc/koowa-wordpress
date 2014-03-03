<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Submenu Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterSubmenu extends ComKoowaTemplateFilterTag
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'tag' => 'submenu'
        ));
    }
    /**
     * Render the tag
     *
     * @param   array   $attribs Associative array of attributes
     * @param   string  $content The tag content
     * @return string
     */
    public function render(&$text)
    {
        parent::render($text);

        $parent = '';
        if(preg_match('#<parent>(.*)<\/parent>#siU', $text, $matches))
        {
            $parent = $matches[1];
            $text = str_replace($matches[0], '', $text);
        }

        foreach ($this->_parsed_tags as $tag)
        {
            $tag->append(array(
                'page_title' => $tag->content,
                'capability' => 'manage_options',
                'parent_page'  => $parent,
                'page'  => empty($tag->view) ? $this->getIdentifier()->package : $this->getIdentifier()->package.'/'.$tag->view
            ));

            add_submenu_page(
                $tag->parent_page,
                $tag->page_title,
                $tag->content,
                $tag->capability,
                $tag->page,
                array($this->getObject('application'), 'render')
            );
        }
    }
}
