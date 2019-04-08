<?php
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
?>