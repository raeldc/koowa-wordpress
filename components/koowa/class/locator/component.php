<?php
/**
 * Koowa Framework - http://developer.joomlatools.com/koowa
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/joomlatools/koowa for the canonical source repository
 */

/**
 * Component Object Locator
 *
 * @author  Israel Canasa <https://github.com/johanjanssens>
 * @package Koopress\Class\Locator
 */
class ComKoowaClassLocatorComponent extends KClassLocatorAbstract
{
    /**
     * The adapter type
     *
     * @var string
     */
    protected $_type = 'component';

    /**
     * The active basepath
     *
     * @var string
     */
    protected $_basepath;

    /**
     * Get the path based on a class name
     *
     * @param  string $class     The class name
     * @param  string $basepath  The base path
     * @return string|bool       Returns the path on success FALSE on failure
     */
    public function locate($class, $basepath = null)
    {
        //Find the class
        if (substr($class, 0, 3) === 'Com')
        {
            /*
             * Exception rule for Exception classes
             *
             * Transform class to lower case to always load the exception class from the /exception/ folder.
             */
            if ($pos = strpos($class, 'Exception'))
            {
                $filename = substr($class, $pos + strlen('Exception'));
                $class    = str_replace($filename, ucfirst(strtolower($filename)), $class);
            }

            $word    = strtolower(preg_replace('/(?<=\\w)([A-Z])/', ' \\1', $class));
            $parts   = explode(' ', $word);

            array_shift($parts);
            $package   = array_shift($parts);
            $namespace = ucfirst($package);

            $component = $package;
            $file      = array_pop($parts);

            if(count($parts))
            {
                if($parts[0] === 'view') {
                    $parts[0] = KStringInflector::pluralize($parts[0]);
                }

                $path = implode('/', $parts).'/'.$file;
            }
            else
            {
                //Exception for framework components. Follow library structure. Don't load classes from root.
                if(isset($this->_namespaces[$namespace]) && $file != 'bootstrapper') {
                    $path = $file.'/'.$file;
                } else {
                    $path = $file;
                }
            }

            //Switch basepath
            if ($this->getNamespace($namespace)) {
                $basepath = $this->getNamespace($namespace);
            }
            elseif (!empty($basepath)) {
                $this->_basepath = $basepath;
            }
            elseif ($this->_basepath) {
                $basepath = $this->_basepath;
            }
            else {
                $basepath = $this->getNamespace('\\');
            }

            return $basepath.'/'.$component.'/'.$path.'.php';
        }

        return false;
    }
}