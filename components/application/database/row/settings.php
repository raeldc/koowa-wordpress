<?php

class ComApplicationDatabaseRowSettings extends KDatabaseRowTable
{
    public function save()
    {
        $component = $this->component;

        if ($this->pages) {
            $this->savePages($this->pages);
        }

        if (!$this->settings) {
            return KDatabase::STATUS_UPDATED;
        }

        $settings = $this->settings;
        $settings->set('last_modified', date( 'Y-m-d H:i:s'));

        $this->reset();

        // Load previous settings - add the settings if it doesn't exist yet, but update if exists.
        $this->component = $component;
        $this->load();

        $this->settings = $settings;

        return parent::save();
    }

    public function savePages(array $pages = array())
    {
        $pagesmodel     = $this->getObject('com:application.model.pages');
        $assigned_pages = array();
        $updated_pages  = array();

        // Get the pages that were assigned
        foreach ($pages as $page)
        {
            if(empty($page['id'])) {
                continue;
            }

            $page['component']           = $this->component;
            $assigned_pages[$page['id']] = $page;
        }

        // Query all existing pages with the assigned pages
        $pagesmodel->getState()->id = array_keys($assigned_pages);

        // Update the existing pages
        if ($assigned_pages && $pagesmodel->getTotal()) 
        {
            foreach ($pagesmodel->getList() as $page)
            {
                $page->setData($assigned_pages[$page->id])->save();
                $updated_pages[] = $page->id;
                unset($assigned_pages[$page->id]);
            }
        }

        // Create non-existing page
        foreach ($assigned_pages as $page)
        {
            $pagesmodel->getTable()->getRow()->setData($page)->save();
            $updated_pages[] = $page['id'];
            unset($assigned_pages[$page['id']]);
        }

        // Delete others that are not assigned
        $pagesmodel->reset()->getState()->setValues(array(
            'component' => $this->component,
            'exclude' => $updated_pages
        ));

        foreach ($pagesmodel->getList() as $page) {
            $page->delete();
        }

        return $this;
    }

    public function __set($key, $value)
    {
        if ($key == 'settings')
        {
            if ( (is_array($value) && !($value instanceof KObjectConfigIni)) || empty($value) )
            {
                $query = $this->getObject('lib:object.config.factory')->getFormat('ini');
                $query->add((array)$value);
                $value = $query;
            }elseif (is_string($value)) {
                $value = $this->getObject('lib:object.config.factory')->getFormat('ini')->fromString($value);
            }
        }

        $this->offsetSet($key, $value);
    }
}