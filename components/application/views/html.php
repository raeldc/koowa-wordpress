<?php

class ComApplicationViewHtml extends KViewTemplate implements KObjectInstantiable, KObjectSingleton
{
    /**
     * An array of MD5 hashes for loaded style strings
     */
    protected $_loaded_styles = array();

    /**
     * String of Styles
     * @var string
     */
    protected $_styles = '';

    /**
     * @var string
     */
    protected $_footer_scripts = '';

    /**
     * @var string
     */
    protected $_header_scripts = '';

    /**
     * An array of MD5 hashes for loaded script strings
     */
    protected $_loaded_scripts = array();

    /**
     * Force creation of a singleton
     *
     * @param  KObjectConfigInterface  $config  Configuration options
     * @param  KObjectManagerInterface $manager A KObjectManagerInterface object
     * @return KDispatcherDefault
     */
    public static function getInstance(KObjectConfigInterface $config, KObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists or not
        if (!$manager->isRegistered($config->object_identifier))
        {
            //Create the singleton
            $class    = $manager->getClass($config->object_identifier);
            $instance = new $class($config);
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }

    public function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'media_basepath' => $this->getObject('request')->getApplicationPath('site').'/wp-content/plugins/koowa/media/'
        ))->append(array(
            'scripts' => array(
                'bootstrap'  => array(
                    'url'          => $config->media_basepath.'js/bootstrap.min.js',
                    'dependencies' => array('jquery'),
                    'version'      => '3.1.1',
                    'location'     => 'footer',
                ),
                'datepicker' => array(
                    'url'          => $config->media_basepath.'js/datepicker.min.js',
                    'dependencies' => array('jquery'),
                    'version'      => '1.3.0',
                    'location'     => 'footer',
                ),
                'waypoints' => array(
                    'url'          => $config->media_basepath.'js/waypoints.min.js',
                    'dependencies' => array('jquery'),
                    'version'      => '2.0.4',
                    'location'     => 'footer',
                )
            ),
            'styles' => array(
                'bootstrap'  => $config->media_basepath.'css/bootstrap.css',
                'datepicker' => $config->media_basepath.'css/datepicker.css',
                'settings'   => $config->media_basepath.'css/settings.css',
            )
        ));

        foreach ($config->scripts as $name => $details) {
            wp_register_script($name, $details->url, $details->dependencies, $details->version, $details->location === 'footer');
        }

        foreach ($config->styles as $name => $url) {
            wp_register_style($name, $url);
        }

        parent::_initialize($config);
    }

    public function addStyle($style, $namespace = '')
    {
        if (is_string($style))
        {
            if ((!empty($namespace) && !isset($this->_loaded_styles[$namespace])) || empty($namespace))
            {
                // If it's the first type to add styles
                if (empty($this->_styles)) {
                    add_action(is_admin() ? 'admin_head' : 'wp_head', array($this, 'renderStyles'));
                }

                if (!empty($namespace)) {
                    $this->_loaded_styles[$namespace] = true;
                }

                $this->_styles .= $style;
            }
        }
        elseif (is_array($style))
        {
            $link         = isset($style['src'])          ? $style['src']          : false;
            $name         = isset($style['name'])         ? $style['name']         : current(explode('.', basename($link)));
            $media        = isset($style['media'])        ? $style['media']        : 'all';
            $version      = isset($style['version'])      ? $style['version']      : null;
            $dependencies = isset($style['dependencies']) ? $style['dependencies'] : array();

            wp_register_style($name, $link, $dependencies, $version, $media);
            wp_enqueue_style($name);
        }
    }

    public function addScript($script, $location = 'footer', $dependencies = array())
    {
        if (is_string($script) && !empty($script))
        {
            if ($location === 'footer')
            {
                if (empty($this->_footer_scripts)) {
                    add_action(is_admin() ? 'admin_footer' : 'wp_footer', array($this, 'renderFooterScripts'));
                }

                $this->_footer_scripts .= $script;
            }
            else
            {
                if (empty($this->_header_scripts)) {
                    add_action(is_admin() ? 'admin_head' : 'wp_head', array($this, 'renderHeaderScripts'));
                }

                $this->_header_scripts .= $script;
            }

            foreach ($dependencies as $dependency) {
                wp_enqueue_script($dependency);
            }
        }
        elseif (is_array($script))
        {
            $link         = isset($script['link'])         ? $script['link']         : false;
            $name         = isset($script['name'])         ? $script['name']         : false;
            $version      = isset($script['version'])      ? $script['version']      : null;
            $dependencies = isset($script['dependencies']) ? $script['dependencies'] : array();
            $footer       = isset($script['footer'])       ? $script['footer']       : false;

            if ($link && $name)
            {
                wp_register_script($name, $link, $dependencies, $version, $footer);
                wp_enqueue_script($name);
            }
        }
    }

    /**
     * Send the generated header styles to the browser
     * @return void
     */
    public function renderStyles()
    {
        echo $this->_styles;
    }

    /**
     * Send the generated header scripts to the browser
     * @return void
     */
    public function renderHeaderScripts()
    {
        echo $this->_header_scripts;
    }

    /**
     * Send the generated footer scripts to the browser
     * @return void
     */
    public function renderFooterScripts()
    {
        echo $this->_footer_scripts;
    }
}