<?php
namespace htrxuan\hdsocials;

// Prevent direct file access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 */
final class Core {

    /**
     * Instance of this class.
     *
     * @var Core
     */
    private static $instance = null;

    /**
     * Get instance of this class.
     *
     * @return Core
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files.
     */
    private function includes() {
        // Will include admin and frontend classes here.
        require_once HDSOCIALS_PLUGIN_DIR . 'includes/class-hdsocials-hours.php';
        require_once HDSOCIALS_PLUGIN_DIR . 'includes/class-hdsocials-admin.php';
        require_once HDSOCIALS_PLUGIN_DIR . 'includes/class-hdsocials-frontend.php';
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        add_filter('plugin_action_links_' . plugin_basename(HDSOCIALS_PLUGIN_FILE), [$this, 'add_action_links']);
        
        // Initialize Admin and Frontend if they exist.
        if (is_admin()) {
            Admin::get_instance();
        }
        Frontend::get_instance();
    }

    /**
     * Add links on the Plugins screen.
     *
     * @param array $links Existing plugin action links.
     * @return array
     */
    public function add_action_links($links) {
        $settings_link = '<a href="options-general.php?page=hdwebmobile-socials-floating">' . esc_html__('Settings', 'hdwebmobile-socials-floating') . '</a>';
        $donate_link = '<a href="https://paypal.me/htrxuan/20" target="_blank" style="color:#d54e21;font-weight:bold;">' . esc_html__('Donate', 'hdwebmobile-socials-floating') . '</a>';

        array_unshift($links, $donate_link);
        array_unshift($links, $settings_link);

        return $links;
    }
}
