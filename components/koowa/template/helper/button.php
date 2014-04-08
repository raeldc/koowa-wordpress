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
            'attribs'   => array(),
            'true'      => 'Enabled',
            'false'     => 'Disabled',
            'selected'  => null,
            'size'      => ''
        ));

        $name    = $config->name;
        $attribs = $this->buildAttributes($config->attribs);
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