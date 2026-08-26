<?php

/**
 * Plugin Name: HDWebmobile Socials Floating
 * Plugin URI:  https://hdwebmobile.com/plugins/hdwebmobile-socials-floating/
 * Description: A premium, beautifully animated floating social media icon bar for WordPress.
 * Version:     1.1.3
 * Author:      htrxuan - Han Tran
 * Author URI:  https://hdwebmobile.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hdwebmobile-socials-floating
 * Domain Path: /languages
 */

namespace htrxuan\hdsocials;

// Prevent direct file access.
if (!defined('ABSPATH')) {
    exit;
}

// Define constants.
define('HDSOCIALS_VERSION', '1.1.3');
define('HDSOCIALS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HDSOCIALS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HDSOCIALS_PLUGIN_FILE', __FILE__);

// Autoloader function (basic) or just require core files directly.
require_once HDSOCIALS_PLUGIN_DIR . 'includes/class-hdsocials-core.php';

/**
 * Initialize the plugin.
 */
function hdsocials_init()
{
    Core::get_instance();
}

add_action('plugins_loaded', 'htrxuan\hdsocials\hdsocials_init');
