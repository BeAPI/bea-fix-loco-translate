<?php
/*
 Plugin Name: BEA - Fix Loco Translate
 Version: 1.1.1
 Plugin URI: https://github.com/BeAPI/bea-fix-loco-translate
 Description: Improve Loco Translate's plugin behaviour.
 Author: Be API Technical team
 Author URI: https://beapi.fr
 Contributors: Maxime Culea
 ----
 Copyright 2018 Be API Technical team (human@beapi.fr)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Improve Loco Translate :
 * - Add mu-plugins folders to be translated with Loco Translate
 * - Allow lang creation in Loco Translate despite `DISALLOW_FILE_MODS`
 * - Don't cache Loco Translate plugins
 *
 * Class BEA_Fix_Loco_Translate
 *
 * @author Maxime CULEA
 *
 * Version 1.1.0
 */
class BEA_Fix_Loco_Translate {
	function __construct() {
		add_filter( 'loco_plugins_data', [ $this, 'support_mu_plugins_folder' ] );
		add_action( 'admin_head', [ $this, 'delete_useless_cache' ] );
		add_action( 'plugins_loaded', [ $this, 'force_disallow_file_mods' ] );
	}

	/**
	 * Manage to add mu plugins folders to be translated
	 *
	 * @author Maxime CULEA
	 */
	function support_mu_plugins_folder( $plugins ) {
		foreach ( get_plugins( '/../mu-plugins' ) as $plugin_file => $data ) {
			if ( dirname( $plugin_file ) != '.' && dirname( $plugin_file ) != 'mu-loader' ) {
				// skip files directly at root
				$data['basedir']         = loco_constant( 'WPMU_PLUGIN_DIR' );
				$plugins[ $plugin_file ] = $data;
			}
		}

		return $plugins;
	}

	/**
	 * Don't use cached loco plugins. If WP does't cache, it has a good reason ! How detect a deleted plugin ?
	 *
	 * @author Maxime CULEA
	 */
	function delete_useless_cache() {
		if ( ! is_plugin_active( 'loco-translate/loco.php' ) ) {
			return;
		}

		wp_cache_delete( 'plugins', 'loco' );
	}

	/**
	 * Allow lang creation in Loco Translate despite DISALLOW_FILE_MODS
	 *
	 * @author Maxime CULEA
	 */
	function force_disallow_file_mods() {
		if ( ! function_exists( 'loco_plugin_version' ) ) {
			return;
		}

		if ( '2.0.16' <= loco_plugin_version() && function_exists( 'wp_is_file_mod_allowed' ) ) {
			/**
			 * WP 4.8+ & LOCO 2.0.16+
			 * As only supported since 2.0.16 for LOCO
			 *
			 * @since Version 1.1.0
			 */
			add_filter( 'file_mod_allowed', function ( $value, $context ) {
				if ( $context == 'download_language_pack' ) {
					return true;
				}

				return $value;
			}, 10, 2 );
		} else {
			/** WP 4.8- */
			define( 'LOCO_TEST', true );
			add_filter( 'loco_constant_' . 'DISALLOW_FILE_MODS', '__return_false' );
		}
	}
}

new BEA_Fix_Loco_Translate();
