<?php

class ComApplicationDatabaseRowSettings extends KDatabaseRowTable
{
    public function save()
    {
        $settings = $this->getObject('settings.'.$this->component);

        if ($this->pages)
        {
            $this->savePages($this->pages);
            $this->remove('pages');
        }

        $this->remove('settings');

        foreach ($this as $key => $value)
        {
            if (!preg_match('/^_(.*)/', $key) && $key !== 'settings' && $key !== 'component' && !is_numeric($key)) {
                $settings->$key = $value;
            }
        }

        $settings->save();
        $this->setStatus($settings->getStatus());
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
}