<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Adminmenu Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterAdminmenu extends ComKoowaTemplateFilterTag
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'tag' => 'adminmenu'
        ));
    }
    /**
     * Render the tag
     *
     * @param 	array	$attribs Associative array of attributes
     * @param 	string	$content The tag content
     * @return string
     */
    public function render(&$text)
    {
        parent::render($text);

        foreach ($this->_parsed_tags as $tag)
        {
            $tag->append(array(
                'page_title' => $tag->content,
                'capability' => 'manage_options',
                'page'  => $this->getIdentifier()->package,
                'icon_url' => '',
                'position' => null
            ));

            add_menu_page(
                $tag->page_title,
                $tag->content, 
                $tag->capability,
                $tag->page,
                array($this->getObject('application'), 'render'),
                $tag->icon_url,
                $tag->position
            );
        }
    }
}
