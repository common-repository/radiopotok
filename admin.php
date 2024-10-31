<?php
add_action( 'admin_menu', 'radiopotok_admin_menu' );
radiopotok_admin_warnings();
	
function radiopotok_admin_init() {
    global $wp_version;
    
    // all admin functions are disabled in old versions
    if ( !function_exists('is_multisite') && version_compare( $wp_version, '3.0', '<' ) ) {
        
        function radiopotok_version_warning() {
            echo "
            <div class='updated fade'><p><strong>".sprintf(__('Radiopotok %s requires WordPress 3.0 or higher.')) ."</strong> ".sprintf(__('Please <a href="%s">upgrade WordPress</a> to a current version</a>.'), 'http://codex.wordpress.org/Upgrading_WordPress'). "</p></div>
            ";
        }
        add_action('admin_notices', 'radiopotok_version_warning'); 
        
        return; 
    }
}
add_action('admin_init', 'radiopotok_admin_init');

function radiopotok_admin_warnings() {
    $script_hash = get_option( 'radiopotok_script_hash' );
    if ($script_hash == '') {
		echo "
		<div class='updated fade'><p><strong>".__('Radiopotok is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter script ID</a> for it to work.'), "admin.php?page=radiopotok-config")."</p></div>
		";
    };
}

function radiopotok_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/radiopotok.php' ) ) {
		$links[] = '<a href="plugins.php?page=radiopotok-config">'.__('Настройки').'</a>';
	}

	return $links;
}
add_filter( 'plugin_action_links', 'radiopotok_plugin_action_links', 10, 2 );

function radiopotok_conf() {
	global $wpcom_api_key;

	if ( isset($_POST['submit']) ) {
		if ( function_exists('current_user_can') && !current_user_can('manage_options') )
			die(__('Cheatin&#8217; uh?'));

		$script_hash = $_POST['script_hash'];
		update_option('radiopotok_script_hash', $script_hash);

		if ( isset( $_POST['radiopotok_script_theme'] ) )
			update_option( 'radiopotok_script_theme', 'dark' );
		else
			update_option( 'radiopotok_script_theme', 'light' );
	};
?>

<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<h2><?php _e('Radiopotok Configuration'); ?></h2>
<div class="narrow">

<form action="" method="post" id="radiopotok-conf" style="margin: auto; width: 400px; ">
	<h3><label for="script_hash"><?php _e('script ID'); ?></label></h3>
	<p><input id="script_hash" name="script_hash" type="text" size="32" maxlength="32" value="<?php echo get_option('radiopotok_script_hash'); ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /></p>
	<p><label><input name="radiopotok_script_theme" id="radiopotok_script_theme" value="true" type="checkbox" <?php if ( 'dark' == get_option('radiopotok_script_theme') ) echo ' checked="checked" '; ?> /> <?php _e('Темная тема оформления.'); ?></label></p>
	<p class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>
</form>


</div>
</div>
<?php
}

function radiopotok_admin_menu() {
	if ( class_exists( 'Jetpack' ) ) {
		add_action( 'jetpack_admin_menu', 'radiopotok_load_menu' );
	} else {
		radiopotok_load_menu();
	}
}

function radiopotok_load_menu() {
	if ( class_exists( 'Jetpack' ) ) {
		add_submenu_page( 'jetpack', __( 'Radiopotok Configuration' ), __( 'Радиопоток' ), 'manage_options', 'radiopotok-config', 'radiopotok_conf' );
	} else {
		add_submenu_page('plugins.php', __('Radiopotok Configuration'), __('Радиопоток'), 'manage_options', 'radiopotok-config', 'radiopotok_conf');
	}
}
