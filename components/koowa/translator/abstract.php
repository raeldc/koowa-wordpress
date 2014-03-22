<?php
/**
 * Koowa for Wordpress
 *
 * @copyright   Copyright (C) 2014 Israel D. Canasa and WIZMEDIA (http://wizmediateam.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/raeldc/koowa-wordpress.git for the canonical source repository
 */

/**
 * Translator
 *
 * @author  Israel Canasa <http://github.com/raeldc>
 * @package Koowa\Wordpress\Translator
 */
abstract class ComKoowaTranslatorAbstract extends KTranslatorAbstract
{
    /**
     * Load the component language files.
     *
     * @param string|KObjectIdentifier $extension Extension identifier or name (e.g. com_files)
     * @param string $app Application. Leave blank for current one.
     *
     * @return boolean
     */
    public function loadTranslations($extension = '', $app = null)
    {
        if ((string)$this->getIdentifier() != (string)$this->getObject('translator')->getIdentifier())
        {
            $extension = preg_replace('/^com_/', '', $extension);

            return $this->getObject('translator')->loadTranslations($extension, $app);
        }
    }

    public function translate($string, array $parameters = array())
    {
        $translator = $this->getObject('translator');
        $domain     = $translator->getComponentDomain($this->getIdentifier()->name);

        // Make sure translations for this component has been loaded
        if ($translator !== $this) {
            $this->loadTranslations($this->getIdentifier()->name);
        }

        return $translator->wptranslate($string, $parameters, $domain);
    }

    public function translateParameters($string, $parameters)
    {
        return KTranslatorAbstract::translate($string, $parameters);
    }
}