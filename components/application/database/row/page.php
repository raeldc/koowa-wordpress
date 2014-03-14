<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Page Row - Automatically convert the query to KObjectConfigIni
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Wordpress\Database\Table
 */
class ComApplicationDatabaseRowPage extends KDatabaseRowTable
{
    public function __set($key, $value)
    {
        if ($key == 'query')
        {
            if(is_array($value) && !($value instanceof KObjectConfigIni))
            {
                $query = $this->getObject('lib:object.config.factory')->getFormat('ini');
                $query->add($value);
                $value = $query;
            }elseif (is_string($value)) {
                $value = $this->getObject('lib:object.config.factory')->getFormat('ini')->fromString($value);
            }
        }

        $this->offsetSet($key, $value);
    }
}