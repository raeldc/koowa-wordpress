<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Tag Template Filter
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Template\Filter
 */
abstract class ComKoowaTemplateFilterTag extends KTemplateFilterAbstract implements KTemplateFilterRenderer
{
    /**
     * Tag to detect
     * @var string
     */
    protected $_tag;

    /**
     * Contains all the content of the parsed tag
     * @var array
     */
    protected $_parsed_tags = array();

    /**
     * Constructor.
     *
     * @param   KObjectConfig $config Configuration options
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->_priority = $config->priority;
        $this->_tag = $config->tag;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOW,
            'tag'      => $this->getIdentifier()->name
        ));

        parent::_initialize($config);
    }

    /**
     * Find any virtual tags and render them
     *
     * This function will pre-pend the tags to the content
     *
     * @param string $text  The text to parse
     */
    public function render(&$text)
    {
        //Parse the tags
        $this->_parseTags($text);
    }

    /**
     * Parse the text for html tags
     *
     * @param string $text  The text to parse
     * @return array
     */
    protected function _parseTags(&$text)
    {
        if (empty($this->_tag)) return;

        $matches = array();
        if(preg_match_all('#<'.$this->_tag.'(.*)>(.*)<\/'.$this->_tag.'>#siU', $text, $matches))
        {
            foreach(array_unique($matches[2]) as $key => $match)
            {
                $attribs = new KObjectConfig(array_merge(array('content' => $match), $this->parseAttributes($matches[1][$key])));
                $this->_parsed_tags[] = $attribs;
            }

            $text = str_replace($matches[0], '', $text);
        }
    }
}
