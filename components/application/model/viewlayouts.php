<?php

class ComApplicationModelViewlayouts extends KModelAbstract
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        // Set the static states
        $this->getState()->insert('component', 'cmd', $this->getIdentifier()->package);
    }

    public function getList()
    {
        if (empty($this->_list))
        {
            $component = $this->getState()->component;

            $this->_total = 0;
            $this->_list  = $this->getObject('lib:object.set');

            $loader    = $this->getObject('manager')->getClassLoader();
            $path      = $loader->getLocator('component')->getNamespace(ucfirst($component));
            $basepath  = $loader->getBasepath('site');
            $viewspath = $path.'/'.$basepath.'/components/'.$component.'/views';

            if (!is_dir($viewspath)) {
                return false;
            }

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