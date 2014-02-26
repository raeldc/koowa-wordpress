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

        if(!empty($identifier['domain'])) {
            $rootpath = $this->getObject('manager')->getClassLoader()->getBasepath($identifier['domain']);
        } else {
            $rootpath  = $this->getObject('manager')->getClassLoader()->getLocator('component')->getNamespace(ucfirst($identifier['package']));
        }
        // TODO: Load namespaces from the activated plugins list of Wordpress. Then also look for template overrides from the current theme.
        $basepath  = WP_PLUGIN_DIR.'/'.strtolower($identifier['package']);
        $filepath  = 'views/'.implode('/', $parts).'/tmpl';
        $fullpath  = $basepath.'/'.$filepath.'/'.$template.'.'.$format.'.php';

        // Find the template
        $result = $this->realPath($fullpath);

        return $result;
    }
}