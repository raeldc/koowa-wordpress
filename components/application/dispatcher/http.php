<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Dispatcher
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Component\Koowa
 */
class ComApplicationDispatcherHttp extends KDispatcherAbstract implements KObjectInstantiable, KObjectMultiton
{
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
        if ($context->request->query->has('com')) {
            //Forward the request
            $this->forward($context->request->query->get('com', 'cmd'));
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
}
