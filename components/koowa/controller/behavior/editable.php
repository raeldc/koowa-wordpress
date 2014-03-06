<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Editable Controller Behavior
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Controller\Behavior
 */
class ComKoowaControllerBehaviorEditable extends KControllerBehaviorEditable
{
    /**
     * Set the referrer
     *
     * @param  KControllerContextInterface $context A controller context object
     * @return void
     */
    public function setReferrer(KControllerContextInterface $context)
    {
        if (!$context->request->cookies->has('referrer_locked'))
        {
            $request  = $context->request->getUrl();
            $referrer = $context->request->getReferrer();

            //Compare request url and referrer
            if (!isset($referrer) || ((string)$referrer == (string)$request))
            {
                $controller = $this->getMixer();
                $identifier = $controller->getIdentifier();

                $page     = $identifier->package;
                $view     = KStringInflector::pluralize($identifier->name);
                $referrer = $controller->getView()->getRoute('page=' . $page . '&view=' . $view);
            }

            //Add the referrer cookie
            $cookie = $this->getObject('lib:http.cookie', array(
                'name'  => 'referrer',
                'value' => $referrer,
                'path'  => $this->_cookie_path
            ));

            $context->response->headers->addCookie($cookie);
        }
    }

    /**
     * Only lock entities in administrator
     *
     * {@inheritdoc}
     */
    protected function _lockResource(KControllerContextInterface $context)
    {
        if (is_admin()) {
            parent::_lockResource($context);
        }
    }

    /**
     * Only unlock entities in administrator
     *
     * {@inheritdoc}
     */
    protected function _unlockResource(KControllerContextInterface $context)
    {
        if (is_admin()) {
            parent::_unlockResource($context);
        }
    }
}