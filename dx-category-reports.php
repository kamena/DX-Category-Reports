<?php
/**
 * Plugin Name: DX Category Reports
 * Description: List a number of posts for each category per month
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 0.8
 * Text Domain: dxcr
 * License: GPL2

 Copyright 2015 mpeshev (email : mpeshev AT devrix DOT com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

if ( ! class_exists( 'DX_Category_Reports' ) ) {

	include_once 'inc/database-time-manager.php';
	
	/**
	 * The main Category Reports class
	 * 
	 * @author nofearinc
	 *
	 */
	class DX_Category_Reports {
		
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_options_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_media' ) );
		}
		
		/**
		 * Create the options page that does the magic
		 */
		public function register_options_page() {
			add_options_page( __( "DX Category Reports", 'dxcr' ), __( "DX Category Reports", 'dxcr' ), 'edit_themes', 'dx-category-reports', array( $this, 'register_options_page_cb' ) );
		}
		
		public function register_options_page_cb() {
			include_once 'inc/templates/admin-template.php';
		}
		
		/**
		 * Enqueue the styling for the reports admin page
		 * 
		 * @param $hook admin hook for the screen options map
		 */
		public function enqueue_media( $hook ) {
			if ( 'admin_page_dx-category-reports' === $hook ) {
				wp_enqueue_style( 'admin-category-reports', plugins_url( 'assets/css/category-reports.css', __FILE__ ) );
			}
		}
	}
	
	new DX_Category_Reports();
}
