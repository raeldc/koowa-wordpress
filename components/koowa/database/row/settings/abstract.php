<?php

abstract class ComKoowaDatabaseRowSettingsAbstract extends KDatabaseRowTable  implements KObjectMultiton
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->component = $this->getIdentifier()->name;

        $this->load();
    }

    public function save()
    {
        // Make sure settings is a string before saving
        $this->_data['settings'] = (string) $this->_data['settings'];

        return parent::save();
    }

    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'table' => 'com:application.database.table.settings'
        ));

        parent::_initialize($config);
    }

    public function __get($column)
    {
        if ($column === 'settings')
        {
            $settings = parent::__get($column);

            if (is_string($settings) || empty($settings)) {
                $this->settings = $settings;
            }
        }
        elseif ($column !== 'settings' && $column !== 'component') {
            return $this->settings->get($column);
        }

        return parent::__get($column);
    }

    public function __set($column, $value)
    {
        if ($column === 'settings' && !$value instanceof KObjectConfigSerializable)
        {
            $this->_data[$column] = $this->getObject('lib:object.config.factory')->getFormat('json')->fromString($value);
            return;
        }
        elseif ($column !== 'settings' && $column !== 'component')
        {
            $this->_modified['settings'] = true;
            return $this->settings->set($column, $value);
        }

        parent::__set($column, $value);
    }
}