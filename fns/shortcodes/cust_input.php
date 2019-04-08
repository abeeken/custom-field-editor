<?php
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
?>