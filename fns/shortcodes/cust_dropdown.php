<?php
    //Dropdown - renders a select box to do multiple choice options
	function cust_dropdown($atts){
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
		$drops = explode("|", $values);
		
		//var_dump($value); //#DEBUG
                
		//Set output string
		$output = '<select name="'.$name.'">';
		
		//Itterate through the options
		foreach ($drops as $drop):
			//var_dump($radio); //#DEBUG
			$output .= '<option value="'.$drop.'"';
			if($value[0] == $drop):
				$output .= ' selected';
			endif;
			$output .= ' /> '.$drop.'</option>';
		endforeach;
		
		$output .= "</select>";

		//Only give us an input field if the user is logged in. If not, just return the value
		if( is_user_logged_in() && $can_edit && !$printmode):
			return $output;
		else:
			return $value[0];
		endif;
	}

	add_shortcode("cust_dropdown", "cust_dropdown");
?>