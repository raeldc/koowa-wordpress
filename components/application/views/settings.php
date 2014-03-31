<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Settings View
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\View\Adminmenu
 */
class ComApplicationViewSettings extends ComKoowaViewHtml
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
            'model' => 'com:application.model.settings',
        ));

        parent::_initialize($config);
    }

    /**
     * Return the views output
     *
     * @param KViewContext  $context A view context object
     * @return string  The output of the view
     */
    protected function _actionRender(KViewContext $context)
    {
        //Handle partial layout paths
        $identifier         = $this->getIdentifier()->toArray();
        $identifier['path'] = array('view');
        $identifier['name'] = 'settings';

        $layout = (string) $this->getIdentifier($identifier);

        //Render the template
        $this->_content = (string) $this->getTemplate()
            ->load((string) $layout.'.html')
            ->compile()
            ->evaluate($this->getData())
            ->render();

        return $this->_content;
    }

    /**
     * Fetch the view data
     *
     * This function will always fetch the model state. Model data will only be fetched if the auto_fetch property is
     * set to TRUE.
     *
     * @param KViewContext  $context A view context object
     * @return void
     */
    protected function _fetchData(KViewContext $context)
    {
        $pages       = $this->getObject('com:application.model.pages');
        $layouts     = $this->getObject('lib:object.set');
        $component   = $this->getObject('dispatcher')->getIdentifier()->package;
        $viewlayouts = $this->getObject('com:application.model.viewlayouts')->component($component)->getList();

        if (empty($viewlayouts)) {
            return;
        }

        // Get all pages attached to this component.
        $pages->getState()->component = $component;
        $existing_pages               = $pages->getList();

        foreach ($viewlayouts as $viewlayout)
        {
            foreach ($existing_pages as $page)
            {
                // Add the page id on the layouts it is attached to.
                if ($viewlayout->view == $page->view && $viewlayout->layout == $page->layout) {
                    $viewlayout->id = $page->id;
                }
            }

            $layouts->insert($viewlayout);
        }

        $context->data->layouts = $layouts;
    }
}
