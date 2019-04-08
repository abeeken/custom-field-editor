<?php
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
?>