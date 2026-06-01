<?php
/*
Plugin Name: BEA - Loco Translate Enhancements
Version: 1.2.1
Plugin URI: https://github.com/BeAPI/bea-fix-loco-translate
Description: Includes mu-plugins translation sources, allows language creation with DISALLOW_FILE_MODS, and prevents stale Loco plugin cache.
Author: Be API
Author URI: https://beapi.fr
Contributors: Maxime Culea

----

Copyright 2018-2026 Be API (human@beapi.fr)

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

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class BEA_Fix_Loco_Translate
 *
 * @author Maxime CULEA
 *
 * @since 1.2.0
 */
class BEA_Fix_Loco_Translate {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'loco_plugins_data', array( $this, 'support_mu_plugins_folder' ) );
		add_action( 'admin_head', array( $this, 'delete_useless_cache' ) );
		add_action( 'plugins_loaded', array( $this, 'force_disallow_file_mods' ) );
	}

	/**
	 * Add mu-plugins folders to translation sources.
	 *
	 * @author Maxime CULEA
	 */
	public function support_mu_plugins_folder( $plugins ) {
		foreach ( get_plugins( '/../mu-plugins' ) as $plugin_file => $data ) {
			if ( dirname( $plugin_file ) != '.' && dirname( $plugin_file ) != 'mu-loader' ) {
				// Skip files located directly in the mu-plugins root.
				$data['basedir']         = loco_constant( 'WPMU_PLUGIN_DIR' );
				$plugins[ $plugin_file ] = $data;
			}
		}

		return $plugins;
	}

	/**
	 * Remove Loco plugin cache to avoid outdated plugin entries.
	 *
	 * @author Maxime CULEA
	 */
	public function delete_useless_cache() {
		if ( ! is_plugin_active( 'loco-translate/loco.php' ) ) {
			return;
		}

		wp_cache_delete( 'plugins', 'loco' );
	}

	/**
	 * Allow language creation in Loco Translate when file mods are disabled.
	 *
	 * @author Maxime CULEA
	 */
	public function force_disallow_file_mods() {
		if ( ! function_exists( 'loco_plugin_version' ) ) {
			return;
		}

		if ( version_compare( loco_plugin_version(), '2.0.16', '>=' ) && function_exists( 'wp_is_file_mod_allowed' ) ) {
			/**
			 * WP 4.8+ and Loco Translate 2.0.16+.
			 * The dedicated file modification check is supported from Loco 2.0.16.
			 *
			 * @see: Loco_fs_FileWriter->disabled();
			 *
			 * @since 1.1.0
			 */
			add_filter(
				'file_mod_allowed',
				function ( $value, $context ) {
					if ( 'download_language_pack' === $context ) {
						return true;
					}

					return $value;
				},
				10,
				2
			);
		} else {
			/** WP 4.8 and older fallback. */
			define( 'LOCO_TEST', true );
			add_filter( 'loco_constant_DISALLOW_FILE_MODS', '__return_false' );
		}
	}
}

new BEA_Fix_Loco_Translate();
