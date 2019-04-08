<?php
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
?>