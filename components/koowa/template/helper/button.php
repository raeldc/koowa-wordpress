<?php

class ComKoowaTemplateHelperButton extends KTemplateHelperAbstract
{
    /**
     * Generates an HTML radio list
     *
     * @param   array|KObjectConfig     $config An optional array with configuration options
     * @return  string  Html
     */
    public function toggler($config = array())
    {
        $config = new KObjectConfigJson($config);
        $config->append(array(
            'name'      => '',
            'true'      => 'Enabled',
            'false'     => 'Disabled',
            'selected'  => null,
            'size'      => ''
        ));

        $name    = $config->name;
        $size    = $this->_getSize($config->size);

        $html = array();
        $html[] = '<div class="btn-group" data-toggle="buttons">';

        $text     = $config->false;
        $active   = '';
        $selected = '';

        if (!$config->selected)
        {
            $active   = 'active';
            $selected = 'checked="checked"';
        }

        $html[] = '<label class="btn btn-disabled '. $active . ' ' .$size. '" for="'.$name.'0">';
        $html[] = '<input '. $selected .' type="radio" name="'. $name .'" value="0" id="'.$name.'0">'. $text;
        $html[] = '</label>';


        $text     = $config->true;
        $active   = '';
        $selected = '';

        if ($config->selected)
        {
            $active   = 'active';
            $selected = 'checked="checked"';
        }

        $html[] = '<label class="btn btn-enabled '. $active . ' ' .$size. '" for="'.$name.'1">';
        $html[] = '<input '. $selected .' type="radio" name="'. $name .'" value="1" id="'.$name.'1">'. $text;
        $html[] = '</label>';

        $html[] = '</div>';
        return implode(PHP_EOL, $html);
    }

    /**
     * Generates an HTML radio list
     *
     * @param   array|KObjectConfig     $config An optional array with configuration options
     * @return  string  Html
     */
    public function singletoggler($config = array())
    {
        $config = new KObjectConfigJson($config);
        $config->append(array(
            'name'      => '',
            'value'     => '',
            'true'      => 'Enabled',
            'false'     => 'Disabled',
            'invert'    => false,
            'selected'  => null,
            'size'      => ''
        ));

        $name           = $config->name;
        $size           = $this->_getSize($config->size);

        switch ($config->invert) {
            case true:
                $enabled_class  = 'btn-disabled';
                $disabled_class = 'btn-enabled';
                break;
            
            default:
                $enabled_class  = 'btn-enabled';
                $disabled_class = 'btn-disabled';
                break;
        }

        $html         = array();
        $disabledhtml = array();
        $enabledhtml  = array();
        $html[]       = '<div class="btn-group" data-toggle="checkbox">';

        $text     = $config->false;
        $active   = '';
        $selected = '';

        if (!$config->selected)
        {
            $active   = 'active';
            $selected = 'checked="checked"';
        }

        $disabledhtml[] = '<a class="btn '. $disabled_class .' '. $active . ' ' .$size. '">'. $text . '</a>';

        $text     = $config->true;
        $active   = '';
        $selected = '';

        if ($config->selected)
        {
            $active   = 'active';
            $selected = 'checked="checked"';
        }

        $enabledhtml[] = '<label class="btn '. $enabled_class .' '. $active . ' ' .$size. '" for="'.$name.'1">';
        $enabledhtml[] = '<input '. $selected .' type="checkbox" name="'. $name .'" value="'. $config->value .'" id="'.$name.'1">'. $text;
        $enabledhtml[] = '</label>';

        $html = !$config->invert ? array_merge($html, $disabledhtml, $enabledhtml) : array_merge($html, $enabledhtml, $disabledhtml);

        $html[] = '</div>';
        return implode(PHP_EOL, $html);
    }

    protected function _getSize($size)
    {
        switch ($size) {
            case 'tiny':
                $result = 'btn-xs';
                break;
            case 'small':
                $result = 'btn-sm';
                break;
            case 'large':
                $result = 'btn-lg';
                break;
            default:
                $result = '';
                break;
        }

        return $result;
    }
}