<?php

class ComApplicationDatabaseRowSettings extends KDatabaseRowTable
{
    public function save()
    {
        $component = $this->component;
        $pages     = $this->getObject('lib:object.config.factory')->getFormat('ini')->add($this->pages);
        $settings  = $this->getObject('lib:object.config.factory')->getFormat('ini');

        $settings->add(array_merge((array)$this->settings, array('last_modified' => date( 'Y-m-d H:i:s'))));

        $this->reset();

        // Load previous settings - this add the settings if it doesn't exist yet, but update if exists.
        $this->component = $component;
        $this->load();

        $this->settings = $settings;

        return parent::save();
    }

    public function __get($key)
    {
        $result = $this->offsetGet($key);

        if ($key == 'settings' && is_string($result)) {
            $result = $this->getObject('lib:object.config.factory')->getFormat('ini')->fromString($result);
        }

        return $result;
    }
}