<?php

class ComKoowaModelViewlayouts extends KModelAbstract
{
    public function getList()
    {
        if (empty($this->_list))
        {
            $component = $this->getIdentifier()->package;

            $this->_total = 0;
            $this->_list  = $this->getObject('lib:object.set');

            $loader    = $this->getObject('manager')->getClassLoader();
            $path      = $loader->getLocator('component')->getNamespace(ucfirst($component));
            $basepath  = $loader->getBasepath('site');
            $viewspath = $path.'/'.$basepath.'/components/'.$component.'/views';

            foreach (new DirectoryIterator($viewspath) as $dir)
            {
                //Only get the component directory names
                if ($dir->isDot() || !$dir->isDir() || $dir == 'tmpl' || !preg_match('/^[a-zA-Z]+/', $dir->getBasename())) {
                    continue;
                }

                foreach (new DirectoryIterator($dir->getPathname().'/tmpl') as $layout)
                {
                    //Only get the component directory names
                    if ($layout->isDot() || $layout->isDir() || $layout->getExtension() != 'json') {
                        continue;
                    }

                    $data           = json_decode(file_get_contents($layout->getPathname()), true);
                    $data['view']   = $dir->getBasename();
                    $data['layout'] = str_replace('.'.$layout->getExtension(), '', $layout->getFilename());

                    $this->_list->insert($this->getObject('lib:object.array', array('data' => $data)));
                }
            }
        }

        return $this->_list;
    }
}