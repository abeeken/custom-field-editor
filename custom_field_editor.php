<?php 
    /*
    Plugin Name: Custom Field Editor
    Plugin URI: 
    Description: Plugin which allows custom fields to be edited from the front end. Useful for creating editable chunks on pages as well as a number of other applications.
    Author: Andrew Beeken
    Version: 0
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
	
	//Initialise Global Variables used by this plugin
	function cfe_globals(){
		global $cfe_can_edit;
		
		$cfe_can_edit = "";
	}
	
	add_action( 'parse_query', 'cfe_globals' );
	
	//Check to see if the form has been submitted and save the values before we go any further
	function process_form(){
		if( $_POST['cust_submitted'] == "yes" ):
			//var_dump($_POST); //#DEBUG
			//echo "FOO".get_the_id(); //#DEBUG
			foreach( $_POST as $key => $value ):
				update_post_meta(get_the_id(), $key, $value);
			endforeach;
		endif;
	}
	
	add_action('the_post','process_form');
	
	//Form shortcodes - these will allow forms to be built from within the wysiwyg which will enable custom field content to be directly editable by logged in users.
	//The form - wraps the whole thing in a tidy package
	function cust_form($atts, $content = null){
                extract(shortcode_atts(array(
			"user" => '',
                        "lock_override" => false                    
		), $atts));
                
		//echo "User: ".$user; //#DEBUG
		
		$current_user = wp_get_current_user();

		//var_dump($current_user);

		$user_role = $current_user->roles[0];

		//echo "User Role: ".$user_role; //#DEBUG
		
		$form_lock = get_option('form_lock');
		$is_locked = false;
		
		//echo "Form Lock: ".$form_lock; //#DEBUG
		
		if( is_user_logged_in() ):
			//Can the user edit the form
			//Is the global lock on?
			if($form_lock == "yes"){
				if($lock_override){
					//Nothing to do
				} else {
					//echo "Locking."; //#DEBUG
					$is_locked = true;
				}
			}
			
			if(!$is_locked){
				if($user == ""){
					$GLOBALS['cfe_can_edit'] = true;
				} else {
					$users = explode(",",$user);

					//var_dump($users); //#DEBUG     

					if (in_array($user_role, $users)){
						//echo "2"; //#DEBUG
						$GLOBALS['cfe_can_edit'] = true; 
					} else {
						//echo "3"; //#DEBUG
						$GLOBALS['cfe_can_edit'] = false;
					}
				}
			} else {
				$GLOBALS['cfe_can_edit'] = false;
			}			
				
				
				//echo "Is Locked:".$is_locked; //#DEBUG				

				//echo "Can Edit: ".$can_edit; //#DEBUG
				
				//echo "Session: ".$GLOBALS['cfe_can_edit']; //#DEBUG
		
			do_action( 'custom_field_editor_before_form_render' );
			
			//echo "<p>CAN EDIT: ".$GLOBALS['cfe_can_edit']."</p>"; // #DEBuG
		
			return '<form action="#" method="post" class="cust_form"><input type="hidden" name="cust_submitted" value="yes" />'.do_shortcode($content).'</form>';
		else:
			return do_shortcode($content);
		endif;		
	}
	
	add_shortcode("cust_form", "cust_form");
	
	//Input - a basic text input
	function cust_input($atts) {
		extract(shortcode_atts(array(
			"name" => ''
		), $atts));
		//Let's get the value from the custom field
		$value = get_post_meta(get_the_id(),$name);
        
		//echo "<p>CAN EDIT: ".$GLOBALS['cfe_can_edit']."</p>"; // #DEBuG
		
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
                
                //echo "Can Edit: ".$can_edit; //#DEBUG
                
		//var_dump($value); //#DEBUG
                
		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $can_edit && !$printmode ):
			return '<input type="text" name="'.$name.'" class="cust_form_input" value="'.$value[0].'" />';
		else:
			return $value[0];
		endif;
	}
	
	add_shortcode("cust_input", "cust_input");
	
	//Text Area - a textarea input
	function cust_text($atts) {
		extract(shortcode_atts(array(
			"name" => '',
                        "rows" => '5',
                        "max_words" => ''
		), $atts));
		//Let's get the value from the custom field
		$value = get_post_meta(get_the_id(),$name);
		
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
                
		//var_dump($value); //#DEBUG
		
                //Is there a max_words value? If so, let's set a data value
                if( $max_words != "" ){
                    $words_output = ' data-maxwords="'.$max_words.'"';
                } else {
                    $words_output = "";
                }
                
		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $can_edit && !$printmode ):
			return '<textarea name="'.$name.'" rows="'.$rows.'" class="cust_form_text"'.$words_output.'>'.$value[0].'</textarea><div id="'.$name.'_count" class="cust_form_text_count"></div>';
		else:
			return wpautop($value[0]);
		endif;
	}
	
	add_shortcode("cust_text", "cust_text");
	
    //WYSIWYG Text Area - a textarea input with formatting controls
	function cust_wysiwyg($atts) {
		extract(shortcode_atts(array(
			"name" => '',
                        "max_words" => ''
		), $atts));
		//Let's get the value from the custom field
		$value = get_post_meta(get_the_id(),$name);
		
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
                
		//var_dump($value); //#DEBUG
                
                //Is there a max_words value? If so, let's set a data value
                if( $max_words != "" ){
                    $words_output = ' data-maxwords="'.$max_words.'"';
                } else {
                    $words_output = "";
                }
                
		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $can_edit && !$printmode ):
			$settings = array( 'media_buttons' => false );
                
                        //We need to use the PHP output buffer here...
                        ob_start();
                
                        wp_editor($value[0],$name,$settings);
                        
                        $editor = ob_get_clean();
                        
                        return $editor;
		else:
			return wpautop($value[0]);
		endif;
	}
	
	add_shortcode("cust_wysiwyg", "cust_wysiwyg");   
        
	//Radio Buttons - a series of radio buttons. Also requires "values" to be passed in
	function cust_radio($atts) {
		extract(shortcode_atts(array(
			"name" => '',
			"values" => 'Yes|No',
		), $atts));
		//Let's get the value from the custom field
		$value = get_post_meta(get_the_id(),$name);
                
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
		
		//Let's get our list of values
		$radios = explode("|", $values);
		
		//var_dump($value); //#DEBUG
                
		//Set output string
		$output = "";
		
		//Itterate through the options
		foreach ($radios as $radio):
			//var_dump($radio); //#DEBUG
			$output .= '<span class="cust_form_radio"><input type="radio" name="'.$name.'" value="'.$radio.'"';
			if($value[0] == $radio):
				$output .= ' checked';
			endif;
			$output .= ' /> '.$radio.'</span>';
		endforeach;
		
		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $can_edit && !$printmode):
			return $output;
		else:
			return $value[0];
		endif;
	}
	
	add_shortcode("cust_radio", "cust_radio");
	
	//Checkbox - a checkbox
	function cust_check($atts) {
		extract(shortcode_atts(array(
			"name" => ''
		), $atts));
		//Let's get the value from the custom field
		$value = get_post_meta(get_the_id(),$name);
                
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
		
		//var_dump($value); //#DEBUG
                
		//Set checked value and return character for non-logged in users
		$checked = "";
		$return = "";
		if( $value[0] == "on" ):
			$checked = " checked";
			$return = "Yes";
		endif;
		
		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $status != "submitted" && $can_edit && !$printmode ):
			return '<input type="hidden" name="'.$name.'" value="" /><input type="checkbox" class="cust_form_check" name="'.$name.'"'.$checked.' />';
		else:
			return $return;
		endif;
	}
	
	add_shortcode("cust_check", "cust_check");
	

	//Submit - defines the submit button
	function cust_submit($atts){                
                $can_edit = $GLOBALS['cfe_can_edit'];
                if( isset($_POST['printmode']) ):
                    $printmode = $_POST['printmode'];
                elseif( isset($_GET['printmode']) ):
                    $printmode = $_GET['printmode'];
                endif;
                
		extract(shortcode_atts(array(
			"value" => 'Save Values'
		), $atts));
                
                if( is_user_logged_in() && $can_edit && !$printmode ):
                    return '<input class="cust_form_submit" type="submit" name="submit" value="'.$value.'" />';
                endif;
	}
	
	add_shortcode("cust_submit", "cust_submit");
	
	//Printmode - 
	function cust_printmode($atts){                
		$can_edit = $GLOBALS['cfe_can_edit'];
		if( isset($_POST['printmode']) ):
			$printmode = $_POST['printmode'];
		elseif( isset($_GET['printmode']) ):
			$printmode = $_GET['printmode'];
		endif;
                
		extract(shortcode_atts(array(
			"printmode_off" => 'Print Mode',
			"printmode_on" => 'Turn Off Print Mode'
		), $atts));
                
                if( is_user_logged_in() && $can_edit ):
					if( !$printmode ):
	                    return '<form action="#printmode" method="post" class="noprint printmode_button">
									<input type="hidden" name="printmode" value="true" />
                                    <input type="submit" name="submit" value="'.$printmode_off.'" />
								</form>
						';
					else:
						return '<form action="#printmode" method="post" class="noprint printmode_button">
                                    <input type="submit" name="submit" value="'.$printmode_on.'" />
								</form>
						';
					endif;
                endif;
	}
	
	add_shortcode("cust_printmode", "cust_printmode");
	
	function cust_printmode_message($atts){
		if( isset($_POST['printmode']) ):
			$printmode = $_POST['printmode'];
		elseif( isset($_GET['printmode']) ):
			$printmode = $_GET['printmode'];
		endif;
		
		extract(shortcode_atts(array(
			"message" => '<strong>Print mode active: </strong>Printing this page will remove any unnecessary content and allow you to take a hard copy of the data in this form.'
		), $atts));
		
		if($printmode):
			return '<a name="printmode"></a> 
            	<p class="noprint print_message">'.$message.'</p>';
		endif;
	}
	
	add_shortcode("cust_printmode_message", "cust_printmode_message");
	
	// Plugin admin screen
	function add_cfe_custom_options()
	{
		add_options_page('CFE Options', 'CFE Options', 'manage_options', 'cfe_functions','cfe_custom_options');
	}
	
	add_action('admin_menu', 'add_cfe_custom_options');
	
	function cfe_custom_options(){
		$form_lock = get_option('form_lock');
		
		$lock_yes = "";
		$lock_no = "";
		
		switch($form_lock){
			case 'yes':
				$lock_yes = ' selected';
				break;
			case 'no';
				$lock_no = ' selected';
				break;
		}
		?>
        <div class="wrap">
			<h2>CFE Options</h2>
            <p>Set various options for the Custom Form Editor plugin</p>
            <form method="post" action="options.php">
				<?php wp_nonce_field('update-options') ?>
                <p><strong>Global Form Lock: </strong><br />
                    <select name="form_lock">
                        <option value="yes"<?php echo $lock_yes; ?>>Yes</option>
                        <option value="no"<?php echo $lock_no; ?>>No</option>
                    </select>
                </p>
                <p><input type="submit" name="Submit" value="Save Options" /></p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="form_lock" />
        	</form>
        </div>
        <?php
	}
	
?>