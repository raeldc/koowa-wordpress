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
 * @package Koowa\Component\Koowa
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
    }

    /**
     * Route the request
     *
     * @param KDispatcherContextInterface $context   A dispatcher context object
     */
    protected function _actionRoute(KDispatcherContextInterface $context)
    {
        $url = clone $context->request->getUrl();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        $context->request->query->add($url->query);
        if ($context->request->query->has('page'))
        {
            // Get the component and the view
            $page = $context->request->query->get('page', 'internalurl');

            if (strpos($page, '/') !== false)
            {
                list($component, $view, $layout) = explode('/', $page, 3);

                if (!$context->request->query->has('view')) {
                    $context->request->query->set('view', $view);
                }

                if (!$context->request->query->has('layout')) {
                    $context->request->query->set('layout', $layout);
                }
            }
            else $component = $page;

            //Forward the request only if the component is registered as a Koowa component
            if ($this->hasComponent($component)) {
                $this->forward($component);
            }
        }
    }

    /**
     * Get the application router.
     *
     * @param  array $options   An optional associative array of configuration options.
     * @return  ComKoowaDispatcherRouter
     */
    public function getRouter(array $options = array())
    {
        $router = $this->getObject('com:application.router', $options);
        return $router;
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

    protected function _actionRender(KDispatcherContextInterface $context)
    {
        echo $context->response->getContent();
    }

    public function registerComponent($component, $dir)
    {
        $this->getObject('manager')->getClassLoader()->getLocator('component')->registerNamespace(ucfirst($component), $dir.'/components');

        $this->getObject('lib:object.bootstrapper.chain')->addBootstrapper($this->getObject('com:'.$component.'.bootstrapper'));

        $this->_registered_components[$component] = end(explode('/',$dir));
    }

    public function hasComponent($component)
    {
        return isset($this->_registered_components[$component]);
    }

    public function getComponentDir($component)
    {
        return isset($this->_registered_components[$component]) ? $this->_registered_components[$component] : false;
    }
}
