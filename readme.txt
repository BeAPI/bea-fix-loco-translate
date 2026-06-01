=== BEA - Loco Translate Enhancements ===
Contributors: beapi, maximeculea
Donate link: http://paypal.me/BeAPI
Tags: Loco Translate, Loco Translate add-on, mu-plugin
Requires at least: 4.6
Requires php: 7.4
Tested up to: 7.0.0
Stable tag: 1.2.1
License: GPLv3 or later
License URI: https://github.com/BeAPI/bea-fix-loco-translate/blob/master/LICENSE.md

Enhancements for Loco Translate.

== Description ==

Enhancements for Loco Translate.

## How?

This plugin provides 3 targeted enhancements for Loco Translate:

- Include mu-plugins folders in translation sources.
  This specifically targets folder-based mu-plugins, commonly loaded through bootstrap loaders such as `mu-require` or `wp-mu-loader`. The plugin extends source discovery so these mu-plugins can be translated with Loco Translate.
- Allow language creation despite `DISALLOW_FILE_MODS`.
  This plugin keeps language creation available for the Loco Translate context on environments where file modifications are restricted.
- Avoid stale Loco Translate plugin cache data.
  This plugin clears Loco plugin cache data to reduce outdated plugin entries during automated deployment workflows.

Tested with Loco Translate 2.8.4.

## Who?

Created and maintained by [Be API](https://beapi.fr).

This plugin is maintained, but free support is not guaranteed. Please report issues on [GitHub](https://github.com/BeAPI/bea-fix-loco-translate/issues).

To streamline issue triage and responses, support requests are handled on GitHub.

== Installation ==

This plugin works only if the [Loco Translate](https://wordpress.org/plugins/loco-translate/) plugin is installed and activated.

# Requirements

- WordPress 4.6+
- Tested up to 7.0.0.
- Tested with Loco Translate 2.8.4.
- PHP 7.4+

# WordPress

- Download and install using the built-in WordPress plugin installer, or optionally use it as an mu-plugin.
- Activate it in the "Plugins" area of the admin.
- Nothing more.

== Changelog ==

= 1.2.1 - 1 Jun 2026 =
- Add GitHub Actions workflows for CI tests and WordPress.org deployment.
- Enable manual workflow runs with workflow_dispatch and tag validation for releases.
- Bump plugin version to 1.2.1.

= 1.2.0 - 1 Jun 2026 =
- Update plugin wording to a more neutral and professional tone.
- Mark as tested up to WordPress 7.0.0.
- Document compatibility tested with Loco Translate 2.8.4.

= 1.1.1 - 27 Jul 2018 =
- Decrease the php requirement to PHP5.4

= 1.1.0 - 4 Jun 2018 =
- WordPress plugin.
- First version of the plugin.
- Finish readme.
- Add composer.json.
- Init plugin.
