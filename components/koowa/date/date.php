<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Date Class
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Koowa
 */
class ComKoowaDate extends KDate
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KObjectConfig $config Configuration options
     * @return void
     */
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'translator' => 'com:koowa.translator'
        ));

        parent::_initialize($config);
    }

    /**
     * Returns human readable date.
     *
     * @param  string $period The smallest period to use. Default is 'second'.
     * @return string Formatted date.
     */
    public function humanize($period = 'second')
    {
        $translator = $this->getTranslator();

        $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
        $lengths = array(60, 60, 24, 7, 4.35, 12, 10);
        $now     = $this->getObject('lib:date');

        if($now !== $this->_date)
        {
            if($now->getTimestamp() > $this->getTimestamp())
            {
                $difference = $now->getTimestamp() - $this->getTimestamp();
                $tense      = 'ago';
            }
            else
            {
                $difference = $this->getTimestamp() - $now->getTimestamp();
                $tense      = 'from now';
            }

            for($i = 0; $difference >= $lengths[$i] && $i < 6; $i++) {
                $difference /= $lengths[$i];
            }

            $difference      = round($difference);
            $period_index    = array_search($period, $periods);
            $omitted_periods = $periods;
            array_splice($omitted_periods, $period_index);

            if(in_array($periods[$i], $omitted_periods))
            {
                $difference = 1;
                $i          = $period_index;
            }

            if($periods[$i] == 'day' && $difference == 1)
            {
                // Since we got 1 by rounding it down and if it's less than 24 hours it would say x hours ago, this
                // is yesterday
                return $tense == 'ago' ? $translator->translate('Yesterday') : $translator->translate('Tomorrow');
            }

            $period        = $periods[$i];
            $period_plural = $period.'s';

            // We do not pass $period or $tense as parameters to replace because some languages use different words
            // for them based on the time difference.
            $result = $translator->choose(
                array("{number} $period $tense", "{number} $period_plural $tense"),
                $difference,
                array('number' => $difference)
            );
        }
        else $result = $translator->translate('Just now');

        return $result;
    }
}