<?php
/*
Plugin Name: Hide Old Posts
Version: 1.2.1
Description: Hides posts older than given amount of time.
Author: Zaantar
Plugin URI: http://wordpress.org/extend/plugins/hide-old-posts/
Author URI: http://zaantar.eu
License: GPL2
*/

/*****************************************************************************\
		I18N
\*****************************************************************************/


define( 'HOP_TEXTDOMAIN', 'hide-old-posts' );


add_action( 'init', 'hop_load_textdomain' );

function hop_load_textdomain() {
	$plugin_dir = basename( dirname(__FILE__) );
	load_plugin_textdomain( HOP_TEXTDOMAIN, false, $plugin_dir.'/languages' );
}


/*****************************************************************************\
		SETTINGS
\*****************************************************************************/

add_action( 'admin_menu', 'hop_admin_menu' );


function hop_admin_menu() {
	add_submenu_page( 'options-general.php', __( 'Hide Old Posts', WLS_TEXTDOMAIN ), __( 'Hide Old Posts', WLS_TEXTDOMAIN ),
		'manage_options', 'hop-settings', 'hop_settings_page' );
}


function hop_settings_page() {
	
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }
    
    switch( $action ) {
    case 'update-settings':
		hop_update_settings( $_POST['settings'] );
		hop_settings_page_default();
    	break;
    default:
    	hop_settings_page_default();
    }
}


define( 'HOP_SETTINGS', 'hop_settings' );

function hop_get_settings() {
	$defaults = array(
		'show_all_to_cap' => 'manage_options',
		'from_date' => '-2 years',
		'hide_posts_only' => true,
		'show_singular_content' => false
	);
	
	$settings = get_option( HOP_SETTINGS, array() );
	
	return wp_parse_args( $settings, $defaults );
}


function hop_update_settings( $settings ) {
	update_option( HOP_SETTINGS, $settings );
}


function hop_settings_page_default() {
	
	$settings = hop_get_settings();
	extract( $settings );
	
	?>
	<div class="wrap">
		<h2><?php _e( 'Hide Old Posts', HOP_TEXTDOMAIN ); ?></h2>
        <form method="post">
            <input type="hidden" name="action" value="update-settings" />
            <table class="form-table">
                <tr valign="top">
                	<th>
                		<label for="settings[show_all_to_cap]"><?php _e( 'User capability to see all posts', HOP_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="text" name="settings[show_all_to_cap]" value="<?php echo $show_all_to_cap; ?>" />
                	</td>
                	<td><small><?php printf( __( 'Enter capability or %s to exclude posts even for admins.', HOP_TEXTDOMAIN ), '<code>none</code>' ); ?></small></td>
                </tr>
                <tr valign="top">
                	<th>
                		<label for="settings[from_date]"><?php _e( 'How old posts shall be shown to other users?', HOP_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="text" name="settings[from_date]" value="<?php echo $from_date; ?>" />
                	</td>
                	<td>
                		<small><?php printf( __( 'Specify value valid according to %s. Default is "-2 years".', HOP_TEXTDOMAIN ), '<code><a href="http://php.net/manual/en/function.strtotime.php">strtotime</a></code>' ); ?></small>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label for="settings[hide_posts_only]"><?php _e( 'Hide posts only', HOP_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[hide_posts_only]" <?php if( $hide_posts_only ) echo 'checked="checked"'; ?> />
                	</td>
                	<td><small><?php printf( __( 'If unchecked, old pages, attachments etc. will be hidden as well.', HOP_TEXTDOMAIN ), '<code>none</code>' ); ?></small></td>
                </tr>
                <tr valign="top">
                	<th>
                		<label for="settings[show_singular_content]"><?php _e( 'Show singular content', HOP_TEXTDOMAIN ); ?></label>
                	</th>
                	<td>
                		<input type="checkbox" name="settings[show_singular_content]" <?php if( $show_singular_content ) echo 'checked="checked"'; ?> />
                	</td>
                	<td><small><?php printf( __( 'If checked, old posts or pages can be viewed through their url (if %s returns %s), but will not be listed anywhere.', HOP_TEXTDOMAIN ), '<code><a href="https://codex.wordpress.org/Function_Reference/is_singular">is_singular()</a></code>', '<code>true</code>' ); ?></small></td>
                </tr>
			</table>
			<p class="submit">
	            <input class="button-primary" type="submit" value="<?php _e( 'Save', HOP_TEXTDOMAIN ); ?>" />    
	        </p>
		</form>
	</div>
	<?php

}


/*****************************************************************************\
		EXCLUDE POSTS
\*****************************************************************************/

add_filter( 'posts_where', 'hop_exclude_posts' );
add_filter( 'getarchives_where', 'hop_exclude_posts' );


function hop_exclude_posts( $where ) {
	extract( hop_get_settings() );
    
    if( !is_admin()  
    	&& ( $show_all_to_cap == 'none' or !current_user_can( $show_all_to_cap ) ) 
    	&& ( !$show_singular_content || !is_singular() )
    ) {
        if( $hide_posts_only ) {
        	$where .= " AND ( post_date > '".date( 'Y-m-d', strtotime( $from_date ) )."' OR post_type NOT LIKE 'post' ) ";
        } else {
        	$where .= " AND post_date > '".date( 'Y-m-d', strtotime( $from_date ) )."' ";
        }
    }
    return $where;
}



?>
