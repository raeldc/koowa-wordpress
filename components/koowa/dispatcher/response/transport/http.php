<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Http Dispatcher Response Transport
 *
 * Pass all 'html' GET requests rendered outside of 'koowa' context on to Joomla.
  *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Bootstrapper\Response
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

        if ($request->isGet() && $request->getFormat() == 'html')
        {
            //Content
            add_filter('the_content', array($response, 'getContent'));

            return true;
        }

        return parent::send($response);
    }
}