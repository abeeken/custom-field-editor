<?php
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