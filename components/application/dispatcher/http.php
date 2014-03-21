<?php
/**
 * Koowa for Wordpress
 *
 * @copyright	Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Dispatcher
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Component\Koowa
 */
class ComApplicationDispatcherHttp extends KDispatcherAbstract implements KObjectInstantiable, KObjectSingleton
{
    /**
     * List of Registered Components
     *
     * @var array
     */
    protected $_registered_components = array();

    /**
     * Constructor.
     *
     * @param KObjectConfig $config	An optional KObjectConfig object with configuration options.
     */
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        //Force the controller to the information found in the request
        if($this->getRequest()->query->has('view')) {
            $this->_controller = $this->getRequest()->query->get('view', 'cmd');
        }

        if (is_admin()) {
            $this->addCommandCallback('before.route', '_getAdminPage');
        } else {
            $this->addCommandCallback('before.route', '_getSitePage');
        }
    }

    /**
     * Force creation of a singleton
     *
     * @param  KObjectConfigInterface  $config  Configuration options
     * @param  KObjectManagerInterface $manager A KObjectManagerInterface object
     * @return KDispatcherDefault
     */
    public static function getInstance(KObjectConfigInterface $config, KObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists or not
        if (!$manager->isRegistered($config->object_identifier))
        {
            //Add the object alias to allow easy access to the singleton
            $manager->registerAlias($config->object_identifier, 'application');

            //Merge alias configuration into the identifier
            $config->append($manager->getIdentifier('application')->getConfig());

            //Create the singleton
            $class    = $manager->getClass($config->object_identifier);
            $instance = new $class($config);
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }

    /**
     * Run the application
     *
     * @param KDispatcherContextInterface $context   A dispatcher context object
     */
    protected function _actionRun(KDispatcherContextInterface $context)
    {
        if (is_admin()) {
            add_action('admin_init', array($this, 'route'));
        }
        // If not in admin and the request method is not GET or format is not HTML - this will allow Koowa to hijack Wordpress's application flow.
        elseif(
            ( !$context->request->isGet() || $context->request->getFormat() != 'html') &&
            $this->hasComponent($component = $context->request->query->get('com', 'cmd'))
        ){
            // No need to route
            $this->forward($component);
        }
        // Try to see if the page is attached to a component view
        else{
            add_action('wp', array($this, 'route'));
        }
    }

    /**
     * Parse the shortcode
     *
     * @param KDispatcherContextInterface $context   A dispatcher context object
     */
    protected function _actionShortcode(KDispatcherContextInterface $context)
    {
        if ($context->param->has('component'))
        {
            $component = $context->param->component;
            if ($this->hasComponent($component))
            {
                $context->param->remove('component');
                $context->request->query->add($context->param->toArray());

                $this->addCommandCallback('after.forward', '_actionRender');
                $this->forward($component);
            }
        }
    }

    /**
     * Get the query from the page
     * @param  KDispatcherContextInterface $context
     * @return void
     */
    protected function _getAdminPage(KDispatcherContextInterface $context)
    {
        if ($context->request->query->has('page'))
        {
            // Get the component and the view
            $page = $context->request->query->get('page', 'internalurl');

            if (strpos($page, '/') !== false)
            {
                list($component, $view, $layout) = explode('/', $page, 3);

                if (!$this->hasComponent($component)) {
                    return false;
                }

                if (!$context->request->query->has('view')) {
                    $context->request->query->set('view', $view);
                }

                if (!$context->request->query->has('layout')) {
                    $context->request->query->set('layout', $layout);
                }

                $context->component = $component;
            } elseif ($this->hasComponent($page)) {
                $context->component = $page;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the query from the post
     * @param  KDispatcherContextInterface $context
     * @return void
     */
    protected function _getSitePage(KDispatcherContextInterface $context)
    {
        global $post;

        if (is_page() && $post)
        {
            $page = $this->getObject('com:application.model.pages')->setState(array(
                'id' => $post->ID
            ))->getItem();

            // Return
            if (!$page->isNew() && $this->hasComponent($page->component))
            {
                $context->component = $page->component;

                if (!$context->request->query->has('view')) {
                    $context->request->query->set('view', $page->view);
                }

                if (!$context->request->query->has('layout')) {
                    $context->request->query->set('layout', $page->layout);
                }

                add_filter('the_content', array($context->response, 'getContent'));
            } else {
                return false;
            }
        }
        else
        {
            // Cancel the command chain if page is not found
            return false;
        }
    }

    /**
     * Route the request for the admin section
     *
     * @param KDispatcherContextInterface $context   A dispatcher context object
     */
    protected function _actionRoute(KDispatcherContextInterface $context)
    {
        //Forward the request only if the component is registered as a Koowa component
        $this->forward($context->component);
    }

    /**
     * Forward the request
     *
     * Forward to another dispatcher internally. Method makes an internal sub-request, calling the specified
     * dispatcher and passing along the context.
     *
     * @param KDispatcherContextInterface $context  A dispatcher context object
     * @throws  UnexpectedValueException    If the dispatcher doesn't implement the KDispatcherInterface
     */
    protected function _actionForward(KDispatcherContextInterface $context)
    {
        //Get the dispatcher identifier
        if(is_string($context->param) && strpos($context->param, '.') === false )
        {
            $identifier            = $this->getIdentifier()->toArray();
            $identifier['domain']  = is_admin() ? 'admin' : 'site';
            $identifier['package'] = $context->param;

            $identifier            = $this->getIdentifier($identifier);
        }
        else $identifier = $this->getIdentifier($context->param);

        //Create the dispatcher
        $config = array(
            'request'    => $context->request,
            'response'   => $context->response,
            'user'       => $context->user,
        );

        $dispatcher = $this->getObject($identifier, $config);

        if(!$dispatcher instanceof KDispatcherInterface)
        {
            throw new UnexpectedValueException(
                'Dispatcher: '.get_class($dispatcher).' does not implement KDispatcherInterface'
            );
        }

        $dispatcher->dispatch($context);
    }

    /**
     * Send the content of the response object to the browser.
     * @param  KDispatcherContextInterface $context
     * @return void
     */
    protected function _actionRender(KDispatcherContextInterface $context)
    {
        echo $context->response->getContent();
    }

    /**
     * Register a component namespace
     * @param  string $component
     * @param  string $dir
     * @return ComApplicationDispatcherHttp
     */
    public function registerComponent($component, $dir)
    {
        $application = is_admin() ? 'admin' : 'site';

        $this->getObject('manager')->getClassLoader()->getLocator('component')->registerNamespace(ucfirst($component), $dir);

        $this->getObject('lib:object.bootstrapper.chain')->addBootstrapper($this->getObject('com://'.$application.'/'.$component.'.bootstrapper'));

        $component_dir = basename($dir);
        $this->_registered_components[$component] = $component_dir;
        $this->getObject('translator')->registerComponentDomain($component, $component_dir);

        return $this;
    }

    /**
     * Check if the component has been registered
     * @param  string  $component
     * @return boolean
     */
    public function hasComponent($component)
    {
        return isset($this->_registered_components[$component]);
    }

    /**
     * Returns the directory of the component
     * @param  string $component
     * @return string or boolean false
     */
    public function getComponentDir($component)
    {
        return isset($this->_registered_components[$component]) ? $this->_registered_components[$component] : false;
    }
}
