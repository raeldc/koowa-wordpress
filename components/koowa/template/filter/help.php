<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Help Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Template\Filter
 */
class ComKoowaTemplateFilterHelp extends ComKoowaTemplateFilterTag
{
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

        add_action('current_screen', array($this, 'display'));
    }

    public function display()
    {
        $screen = get_current_screen();

        foreach ($this->_parsed_tags as $key => $help)
        {
            $identifier = $this->getTemplate()->getView()->getIdentifier();

            $id = implode('-', 
                array_merge(
                    array(
                        $identifier->package), 
                        $identifier->path, 
                        array(
                            $this->getTemplate()->getView()->getLayout(), 
                            $identifier->name,
                            (string)($key + 1)
                        )
                    )
                );

            $screen->add_help_tab(array(
                'id'      => $id,
                'title'   => $help->title,
                'content' => $help->content
            ));
        }
    }
}
