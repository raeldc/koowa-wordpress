This is Koowa for WordPress
===

Yup, you can build web applications using the Koowa Framework in WordPress. It's not seamless yet, but it's in progress. Admin pretty much works. Frontend works as far as rendering content but routing doesn't work.

Installation
---
* Symlink or clone this repo to your WordPress plugin folder.
* Symlink `/library` to your `koowa/code/libraries/koowa/libraries` repository.
* Run this SQL:
	
	-- Create syntax for TABLE 'wp_koowa_pages'
	CREATE TABLE `wp_koowa_pages` (
		`id` int(11) unsigned NOT NULL,
		`component` varchar(255) NOT NULL DEFAULT '',
		`view` varchar(255) NOT NULL DEFAULT '',
		`layout` varchar(255) NOT NULL DEFAULT '',
		`query` text,
		`params` text,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;

	-- Create syntax for TABLE 'wp_koowa_settings'
	CREATE TABLE `wp_koowa_settings` (
		`component` varchar(100) NOT NULL DEFAULT '',
		`settings` text NOT NULL,
		PRIMARY KEY (`component`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;
	
* Then activate the Koowa for WordPress plugin on WordPress. You are now ready to make awesome apps in WordPress.

Making a Plugin/Component for WordPress
---
* Make a new folder in your WordPress plugins folder. Let's name it **some-koowa-plugin**.
* WordPress expects it to have a php file with the same name so make **some-koowa-plugin.php**
* Put the meta docblock that WordPress uses to get information about your plugin.
* Now, you have to let Koowa know that your plugin exists. Here's how you bootstrap your plugin:

	add_action('koowa_before_bootstrap', 'register_somekoowaplugin');
	
	function register_somekoowaplugin()
	{
	    KObjectManager::getInstance()->setConfig('com:application.bootstrapper', array(
	        'components'    => array(
	            'somekoowaplugin' => array(
					// If your plugin is symlinked, do this. If not, you can simply use __DIR__
	                'path'      => WP_PLUGIN_DIR.'/some-koowa-plugin',
	                'adminmenu' => true
	            ))
	    ));
	}
* Koowa components for WordPress does not use the bootstrapper chain (where it calls the bootstrapper object of each component). It's redundant and not good for performance. So do the bootstrapping within the plugin file itself using the `koowa_before_bootstrap` hook. There is also a `koowa_after_bootstrap`. As you can see, only the `com:application.bootstrapper` is called. That should be enough to bootstrap all Koowa plugins.
* See the `adminmenu => true` up there? That's very important for your backend.

Directory Structure of a Plugin
---

	/admin
		/components
			/componentname
				/controller
				/etc...
				/views
					/tmpl
						adminmenu.html.php
					/whateverview
						html.php
						/tmpl
							default.html.php
	/site
		/components
			/componentname
				/controller
				/etc...
				/views
					/whateverview
						html.php
						/tmpl
							default.html.php
							default.json
	/media
	plugin-file.php

**What is adminmenu.html.php?**

That's where you put your menus for the backend. It looks something like this:

	<adminmenu view="whateverview">My Component</adminmenu>
	<submenu view="settings">Settings</submenu>
	<submenu view="anotherview" layout="form">Another View</submenu>

This will put menu items on your WordPress admin backend. You'll notice that it will generate a url like this: `admin.php?page=componentname/view`. If there is a layout, it will generate this: `admin.php?page=componentname/view/layout`

**Frontend doesn't work yet, but here's the idea**

Traditionally, you use shortcodes in WordPress to render the html of your plugin. We don't want to do that, it's slow and it's just not user friendly. So we're making it easy by "assigning" pages to views. It's similar to how Joomla! works. But since WordPress doesn't have that facility, Koowa for Wordpress will provide it. It's in the works and it's partially working. All you have to do is access the "Settings" submenu. The settings submenu will scan your `site views` and look for the layouts that has a corresponding .json file. It's similar to Joomla!'s layout.xml. Who uses xml nowadays!? But anyway, if found, you will see an interface that allows you to assign the layouts to WordPress Pages. More work still needs to be done on this area.

Some things you need to know
---

Bootstrap is built in. Add it using:

	<style dependencies="bootstrap"></style>
	
But it is namespaced so encapsulate your html within `<div class="bs"></div>`.

Here's how you include scripts

	<script src="media://js/myscript.js" location="footer" dependencies="jquery,bootstrap"></script>
	
Or

	<script dependencies="jquery">
		jQuery.ready(function(){
			// script there
		});
	</script>

The default location of scripts is on the footer.