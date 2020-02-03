<?php
	/*
    Plugin Name: Custom Field Editor
    Plugin URI: 
    Description: Plugin which allows custom fields to be edited from the front end. Useful for creating editable chunks on pages as well as a number of other applications.
    Author: Andrew Beeken
    Version: 0.1
    Author URI: http://www.andrewbeeken.co.uk
    */
    
    /*
    How does this plugin work? Basically it opens up custom post fields to front-end editing by building a form if the user is logged in. If they're not, it will simply output the current values stored.
    
    An example of a form would be:
    
    [cust_form]
    	[cust_input name="some_field"]
    	[cust_input name="some_other_field"]
    	[cust_check name="some_tickbox"]
    	[cust_submit value="Save This Form"]
    [/cust_form]
	*/

	class customFieldEditor {
		protected $pluginDir;

		public function __construct(){
			$this->pluginDir = dirname(__FILE__);

			include(dirname( __FILE__ ) .'/fns/globals.php');

			include($this->pluginDir .'/fns/shortcodes/cust_form.php');
			include($this->pluginDir .'/fns/shortcodes/cust_input.php');
			include($this->pluginDir .'/fns/shortcodes/cust_text.php');
			include($this->pluginDir .'/fns/shortcodes/cust_wysiwyg.php');
			include($this->pluginDir .'/fns/shortcodes/cust_radio.php');
			include($this->pluginDir .'/fns/shortcodes/cust_check.php');
			include($this->pluginDir .'/fns/shortcodes/cust_dropdown.php');
			include($this->pluginDir .'/fns/shortcodes/cust_submit.php');
			include($this->pluginDir .'/fns/shortcodes/cust_printmode.php');
			
			include($this->pluginDir .'/fns/process_form.php');
			
			include(dirname( __FILE__ ) .'/fns/hooks.php');
			
			include(dirname( __FILE__ ) .'/admin/admin.php');
		}
	}

	$customfieldeditor = new customFieldEditor();