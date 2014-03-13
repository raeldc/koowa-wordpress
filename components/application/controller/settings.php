<?php

class ComApplicationControllerSettings extends ComKoowaControllerModel
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'view'  => 'com:application.view.settings',
            'model' => 'com:application.model.settings',
        ));

        parent::_initialize($config);
    }
}