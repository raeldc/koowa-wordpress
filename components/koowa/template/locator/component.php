<?php

class ComKoowaTemplateLocatorComponent extends KTemplateLocatorComponent
{
	/**
     * Locate the template based on a virtual path
     *
     * @param  string $path  Stream path or resource
     * @return string The physical stream path for the template
     * @throws RuntimeException If a partial template path is passed and no base template has been loaded.
     */
    public function locate($path)
    {
        //Qualify partial templates.
        if(strpos($path, ':') === false)
        {
            if(!$base = $this->getTemplate()->getPath()) {
                throw new RuntimeException('Cannot qualify partial template path');
            }

            $identifier = $this->getIdentifier($base)->toArray();

            $format    = pathinfo($path, PATHINFO_EXTENSION);
            $template  = pathinfo($path, PATHINFO_FILENAME);

            $parts     = $identifier['path'];
            array_pop($parts);
        }
        else
        {
            // Need to clone here since we use array_pop and it modifies the cached identifier
            $identifier = $this->getIdentifier($path)->toArray();

            $format    = $identifier['name'];
            $template  = array_pop($identifier['path']);
            $parts     = $identifier['path'];
        }

        $rootpath  = $this->getObject('manager')->getClassLoader()->getLocator('component')->getNamespace(ucfirst($identifier['package']));

        if (empty($rootpath)) {
            $rootpath = $this->getObject('manager')->getClassLoader()->getLocator('component')->getNamespace('\\');
        }

        $rootpath .= empty($identifier['domain']) ? '' : '/'. $this->getObject('manager')->getClassLoader()->getBasepath($identifier['domain']);

        if ($parts[0] != 'view' && count($parts)) {
            $filepath  = 'views/'.implode('/', $parts).'/tmpl';
        }
        else $filepath  = 'views/tmpl';

        // Find the template
        $realpath = $rootpath.'/components/'.$identifier['package'].'/'.$filepath.'/'.$template.'.'.$format.'.php';

        return $this->realPath($realpath);
    }
}