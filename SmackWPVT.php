<?php

/*********************************************************************************
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

class SmackWPVT {

	/*
	 * Function to initialize this plugin
	 */
	public static function init() {
		add_action('admin_enqueue_scripts', 'LoadWpTigerScript');
		add_action('admin_menu', 'wptigermenu');
		add_action('user_register', 'wp_tiger_capture_registering_users');
		add_action('after_plugin_row_wp-zoho-crm/wp-zoho-crm.php', array('SmackWPVT', 'plugin_row'));
		add_filter('plugin_action_links_wp-zoho-crm/wp-zoho-crm.php', array('SmackWPVT', 'plugin_settings_link'), 10, 2);
		add_filter( 'custom_menu_order', '__return_true' );
		add_filter( 'menu_order', array('SmackWPVT','smackwptigerfree_change_menu_order') );
	}

	// Move Pages above Media
	public static function smackwptigerfree_change_menu_order ( $menu_order ) {
		return array(
				'index.php',
				'edit.php',
				'edit.php?post_type=page',
				'upload.php',
				'wp-zoho-crm',
			    );
	}

	public static function unzip_leads_builder()
	{
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		define('FS_METHOD', 'direct');
	    WP_Filesystem();
		global $plugin_dir_wp_tiger;
		$zip = $plugin_dir_wp_tiger.'wp-leads-builder-any-crm.zip';
		$pl = wp_normalize_path( WP_PLUGIN_DIR );
		$da = unzip_file($zip, $pl);
	}

	public function activate_leads_builder()
	{
	 include_once(ABSPATH.'wp-admin/includes/plugin.php');
	 $activate = activate_plugin('wp-leads-builder-any-crm/index.php');
	 
	// if($activate)
	 //wp_redirect("admin.php?page=wp-leads-builder-any-crm");
	}

	public function hereWPtiger() 
	{
		wp_redirect("admin.php?page=wp-leads-builder-any-crm");
	}

	/*
	 * Function to get the settings link
	 * @$links string URL for the link
	 * @$file string filename for the link
	 * @return string html links
	 */
	public static function plugin_settings_link($links, $file) {

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=wp-zoho-crm">' . __('Settings', 'wp-tiger') . '</a>');

		return $links;
	}

	/*
	 * Function to get the plugin row
	 * @$plugin_name as string
	 */
	public static function plugin_row($plugin_name) {
		$active_plugins = get_option( "active_plugins" );
		if(in_array( "wp-leads-builder-any-crm/index.php", $active_plugins) ) {
		echo '</tr><tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt" > <p>WP Zoho CRM is now <a href="https://wordpress.org/plugins/wp-leads-builder-any-crm/" target="blank">Leads Builder For Any CRM</a> for advanced features. <a onclick="deactivate_wp_tig()"> Proceed </a> to remove old files of WP Zoho CRM and use Leads Builder For Any CRM </p> </div></td>';
		}
		else{
			echo '</tr><tr class="plugin-update-tr active"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt" > <p>WP Zoho CRM is now <a href="https://wordpress.org/plugins/wp-leads-builder-any-crm/" target="blank">Leads Builder For Any CRM</a> for advanced features. <a href="admin.php?page=wp-zoho-crm"> Proceed </a> to install Leads Builder For Any CRM </p> </div></td>';
		}

	}
}
