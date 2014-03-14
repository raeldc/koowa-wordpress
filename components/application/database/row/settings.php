<?php

class ComApplicationDatabaseRowSettings extends KDatabaseRowTable
{
    public function save()
    {
        $component = $this->component;
        $settings  = $this->getObject('lib:object.config.factory')->getFormat('ini');
        $settings->add(array_merge((array)$this->settings, array('last_modified' => date( 'Y-m-d H:i:s'))));

        $pages          = $this->getObject('com:application.model.pages');
        $new_pages      = $this->pages;
        $assigned_pages = array();
        $updated_pages  = array();

        // Get the pages that were assigned
        foreach ($new_pages as $page)
        {
            if(empty($page['id'])) {
                continue;
            }

            $page['component']           = $component;
            $assigned_pages[$page['id']] = $page;
        }

        // Query all existing pages with the assigned pages
        $pages->getState()->id = array_keys($assigned_pages);
        $existing_pages = $pages->getList();

        // Update the existing pages
        if ($existing_pages->count()) 
        {
            foreach ($existing_pages as $page)
            {
                $page->setData($assigned_pages[$page->id])->save();
                $updated_pages[] = $page->id;
                unset($assigned_pages[$page->id]);
            }
        }

        // Create non-existing page
        foreach ($assigned_pages as $page)
        {
            $pages->getTable()->getRow()->setData($page)->save();
            $updated_pages[] = $page['id'];
            unset($assigned_pages[$page['id']]);
        }

        // Delete others that are not assigned
        $pages->reset()->getState()->setValues(array(
            'component' => $component,
            'exclude' => $updated_pages
        ));

        foreach ($pages->getList() as $page) {
            $page->delete();
        }

        $this->reset();

        // Load previous settings - add the settings if it doesn't exist yet, but update if exists.
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