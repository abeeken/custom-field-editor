<?php
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
?>