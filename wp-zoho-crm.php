<?php
/*********************************************************************************
Plugin Name: WP Zoho CRM
Plugin URI: http://www.smackcoders.com
Description: Easy Lead capture Zoho Crm Webforms and Contacts synchronization
Version: 1.3
Author: smackcoders.com
Author URI: http://www.smackcoders.com

 * Easy Lead capture Vtiger Webforms and Contacts synchronization is a tool
 * for capturing leads and contacts to VtigerCRM from WordPress developed by
 * Smackcoder. Copyright (C) 2013 Smackcoders.
 *
 * Easy Lead capture Vtiger Webforms and Contacts synchronization is free
 * software; you can redistribute it and/or modify it under the terms of the GNU
 * Affero General Public License version 3 as published by the Free Software
 * Foundation with the addition of the following permission added to Section 15
 * as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK IN WHICH THE
 * COPYRIGHT IS OWNED BY Smackcoders, FEasy Lead capture Vtiger Webforms and
 * Contacts synchronization  DISCLAIMS THE WARRANTY OF NON INFRINGEMENT OF THIRD
 * PARTY RIGHTS.
 *
 * Easy Lead capture Vtiger Webforms and Contacts synchronization is
 * distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE.  See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Smackcoders at email address info@smackcoders.com.
 *
 * The interactive user interfaces in original and modified versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the Easy Lead capture
 * Vtiger Webforms and Contacts synchronization copyright notice. If the
 * display of the logo is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Copyright Smackcoders. 2013.
 * All rights reserved".
 ********************************************************************************/

// $de = delete_plugins('wp-leads-builder-any-crm/index.php');
// print_r($de);
//die();
//do_action( 'delete_plugin', 'wp-leads-builder-any-crm/index.php');

global $plugin_url_wp_tiger;
$plugin_url_wp_tiger = plugins_url('', __FILE__);
global $plugin_dir_wp_tiger;
$plugin_dir_wp_tiger = plugin_dir_path(__FILE__);


// Debug enable/disable 
$get_settings = get_option("smack_vtlc_settings");
if($get_settings['wp_tiger_smack_debug'] != 'on') {
	error_reporting(0);
	ini_set('display_errors', 'Off');
}

require_once("{$plugin_dir_wp_tiger}/SmackWPVT.php");
require_once("{$plugin_dir_wp_tiger}/SmackZohoApi.php");
// require_once("{$plugin_dir_wp_tiger}/smack-vtlc-shortcodes.php");
 //require_once("{$plugin_dir_wp_tiger}/navMenu.php");
 require_once("{$plugin_dir_wp_tiger}/SmackWPAdminPages.php");
// require_once("{$plugin_dir_wp_tiger}/CaptureRegisteringUsers.php");


//register_activation_hook( __FILE__, array( 'SmackWPVT', 'unzip_leads_builder' ) );
 

//register_activation_hook( __FILE__, array( 'SmackWPVT', 'activate_leads_builder' ) );
$active_plugins = get_option( "active_plugins" );
if(!in_array( "wp-leads-builder-any-crm/index.php", $active_plugins) ) {
add_action('init', array('SmackWPVT', 'unzip_leads_builder'));
add_action('init', array('SmackWPVT', 'activate_leads_builder'));
}

add_action('init', array('SmackWPVT', 'init'));
//register_activation_hook( __FILE__, array( 'SmackWPVT', 'hereWPtiger' ) );



//add_action('delete_plugin', 'wp-leads-builder-any-crm/index.php');


function wptiger_activation_redirect( $plugin ) {

        exit( wp_redirect( admin_url( 'admin.php?page=wp-zoho-crm' ) ) );
    
}

add_action( 'activated_plugin', 'wptiger_activation_redirect' );


register_deactivation_hook(__FILE__, 'wptiger_deactivate');

// Admin menu settings
function wptigermenu() {
	global $plugin_url_wp_tiger;
	add_menu_page('WPTiger Settings', 'WP Zoho CRM', 'manage_options', 'wp-zoho-crm', 'wptiger_settings', "{$plugin_url_wp_tiger}/images/icon.png");
}

function LoadWpTigerScript() {
	global $plugin_url_wp_tiger;
	wp_enqueue_script("wp-tiger-script", "{$plugin_url_wp_tiger}/js/smack-vtlc-scripts.js", array("jquery"));
	wp_enqueue_style("wp-tiger-css", "{$plugin_url_wp_tiger}/css/smack-vtlc-css.css");
	//sweetalert
	wp_enqueue_style("sweet-alert-css", "{$plugin_url_wp_tiger}/css/sweetalert.css");
	wp_enqueue_script("sweet-alert-js", "{$plugin_url_wp_tiger}/js/sweetalert-dev.js", array("jquery"));
	//wp_enqueue_script('sweet-alert-js');
	
}

function wptiger_deactivate() {
	delete_option('smack_vtlc_settings');
	delete_option('smack_vtlc_field_settings');
	delete_option('smack_vtlc_widget_field_settings');
	delete_option('wp-tiger-contact-form-attempts');
	delete_option('wp-tiger-contact-widget-form-attempts');
}

function SmackWPTigertestAccess() {
	global $plugin_dir_wp_tiger;
	require_once("{$plugin_dir_wp_tiger}/test-access.php");
	die;
}

add_action('wp_ajax_SmackWPTigertestAccess', 'SmackWPTigertestAccess');
add_action('wp_ajax_SmackWPTigerDeActivate', 'SmackWPTigerDeActivate');
add_action('wp_ajax_SmackWPTigerToLb', 'SmackWPTigerToLb');
add_action('wp_ajax_ActivateLeadsBuilder', 'ActivateLeadsBuilder');

function SmackWPTigertestVtigerAccess() {
	global $plugin_dir_wp_tiger;
	require_once("{$plugin_dir_wp_tiger}/test-vtiger-access.php");
	die;
}

function SmackWPTigerDeActivate()
{
   deactivate_plugins('wp-zoho-crm/wp-zoho-crm.php');
   $dea = array('wp-zoho-crm/wp-zoho-crm.php');
   delete_plugins($dea);
   echo "success";
   die();
}

function SmackWPTigerToLb()
{
  require_once(ABSPATH . 'wp-admin/includes/file.php');
    define('FS_METHOD', 'direct');
      WP_Filesystem();
    global $plugin_dir_wp_tiger;
    $zip = $plugin_dir_wp_tiger.'wp-leads-builder-any-crm.zip';
    $pl = wp_normalize_path( WP_PLUGIN_DIR );
    $da = unzip_file($zip, $pl);
    if(is_wp_error($da)){
      echo "fail";
      die();
    }
    echo $da;
    die();
}

function ActivateLeadsBuilder()
{
  include_once(ABSPATH.'wp-admin/includes/plugin.php');
   $activate = activate_plugin('wp-leads-builder-any-crm/index.php');
   echo "success";
   die();
}

add_action('wp_ajax_SmackWPTigertestVtigerAccess', 'SmackWPTigertestVtigerAccess');

function wptiger_settings() {

	$AdminPages = new SmackWPAdminPages();
        // Auto plugin enabler
  ?> 
  <?php

        $get_activate_plugin_list = get_option('active_plugins');
        if(in_array('wp-leads-builder-any-crm/index.php', $get_activate_plugin_list)) { ?>

  <div style="padding: 20px">   
  <h1 style="padding-bottom: 30px">Dear Users!</h1>   
<h1 style="text-align: center;padding-bottom: 30px">WP Zoho CRM is now Leads builder for any CRM</h1> 

<p style="font-size: 17px;  word-spacing: 3px; padding-bottom: 30px">For easy maintenance we merged couple of plugins and more advanced features together. So you can enjoy all benefits in WP Leads builder for any CRM plugin. You can access whole Zoho CRM integration and features via admin side bar menu under 
Leads Builder For Any CRM >> CRM Configuration >> Choose Zoho CRM from drop down.
   </p>

		<div class="updated notice" style="font-size: 18px">
        	<p style="font-size: 17px;word-spacing: 3px;">
        		WP Leads builder for any CRM (includes WP Zoho CRM features) has been successfully installed and you can click proceed to remove old files and folder. </p>
            
            <p style="text-align: center;">
            <input  type="button" class="button-primary" name="delete_WP_tiger" id="delete_WP_tiger" onclick="deactivate_wp_tig()" value="Proceed"/>
            </p>
		</div>
    </div>

		
      <?php } else { ?>
      <div style="padding: 20px;">      
      <h1 style="padding-bottom: 30px">Dear User!</h1>
<h1 style="text-align: center;padding-bottom: 30px">WP Zoho CRM is now Leads builder for any CRM</h1> 

<p style="font-size: 17px;  word-spacing: 3px;padding-bottom: 30px">For easy maintenance we merged couple of plugins and more advanced features together. So to enjoy benefits of WP Leads builder for any CRM plugin Zoho CRM features please follow one of the method below.
   </p>
    <div class="error settings-error notice">
<p style="font-size: 17px;word-spacing: 3px;">
<strong>
  Cannot proceed. WP Zoho CRM requires the following plugin to be installed or updated: <a href="https://wordpress.org/plugins/wp-leads-builder-any-crm/" target="blank" style="text-decoration: none;">Leads Builder For Any CRM</a> </strong>
  </p>
  <p id="wp_tiger_error" style="font-size: 17px;word-spacing: 3px;display: none;"> Error : Could not create directory</p>
  <p style="text-align: center;">
  <a class="button-primary" style="margin-top:1em;" onclick="migrate_to_lb()" >Go Install Plugin</a> </p>

   </div>
   <div class="notice notice-warning is-dismissible">
<p style="font-size: 17px;word-spacing: 3px;"> Note: ensure proper permission otherwise you may need to set right permission.
   </p> </div>
    </div>


      <?php
      }
}

function migrate_leadbuild() {

	require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
      	$plugin['source'] = 'https://downloads.wordpress.org/plugin/wp-leads-builder-any-crm.1.1.zip';
    	$source = ( 'upload' == $type ) ? $this->default_path . $plugin['source'] : $plugin['source'];
    	/** Create a new instance of Plugin_Upgrader */
     	$upgrader = new Plugin_Upgrader( $skin = new Plugin_Installer_Skin( compact( 'type', 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
   	/** Perform the action and install the plugin from the $source urldecode() */
     	$upgrader->install( $source );
  	/** Flush plugins cache so we can make sure that the installed plugins list is always up to date */
     	wp_cache_flush();
      	$plugin_activate = $upgrader->plugin_info(); // Grab the plugin info from the Plugin_Upgrader method
 	$activate = activate_plugin( $plugin_activate ); // Activate the plugin
	if ( !is_wp_error( $activate ) )
                deactivate_plugins('wp-zoho-crm/wp-zoho-crm.php');//Deactivate tiger plugin
    	$this->populate_file_path(); // Re-populate the file path now that the plugin has been installed and activated
   	if ( is_wp_error( $activate ) ) {
        	echo '<div id="message" class="error"><p>' . $activate->get_error_message() . '</p></div>';
            	echo '<p><a href="' . add_query_arg( 'page', $this->menu, admin_url( $this->parent_url_slug ) ) . '" title="' . esc_attr( $this->strings['return'] ) . '" target="_parent">' . __( 'Return to Required Plugins Installer', $this->domain ) . '</a></p>';
             	return true; // End it here if there is an error with automatic activation
      	}
     	else {
           	echo '<p>' . $this->strings['plugin_activated'] . '</p>';
     	}

}
