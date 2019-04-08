<?php
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
?>