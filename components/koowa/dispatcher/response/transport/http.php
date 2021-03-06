<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Http Dispatcher Response Transport
 *
 * Pass all 'html' GET requests rendered outside of 'koowa' context on to Joomla.
  *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Bootstrapper\Response
 */
class ComKoowaDispatcherResponseTransportHttp extends KDispatcherResponseTransportHttp
{
    /**
     * Send HTTP response
     *
     * @param KDispatcherResponseInterface $response
     * @return boolean
     */
    public function send(KDispatcherResponseInterface $response)
    {
        $request = $response->getRequest();

        if (!headers_sent())
        {
            //Cookies
            foreach ($response->headers->getCookies() as $cookie)
            {
                setcookie(
                    $cookie->name,
                    $cookie->value,
                    $cookie->expire,
                    $cookie->path,
                    $cookie->domain,
                    $cookie->isSecure(),
                    $cookie->isHttpOnly()
                );
            }

            /* TODO: Send Message to Wordpress
            //Messages
            $messages = $response->getMessages(false);
            foreach($messages as $type => $group)
            {
                if ($type === 'success') {
                    $type = 'message';
                }

                foreach($group as $message) {
                    JFactory::getApplication()->enqueueMessage($message, $type);
                }
            }
            */
            if ($request->isAjax() || !$request->isGet() || $request->getFormat() != 'html') {
                parent::send($response);
            }
        }

        return true;
    }
}