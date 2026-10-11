<?php

namespace htrxuan\hdsocials;

// Prevent direct file access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Class
 */
class Admin
{

    /**
     * Instance of this class.
     *
     * @var Admin
     */
    private static $instance = null;

    /**
     * Get instance of this class.
     *
     * @return Admin
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        add_action('admin_menu', [$this, 'add_plugin_page']);
        add_action('admin_init', [$this, 'page_init']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Enqueue admin scripts and styles.
     */
    public function enqueue_scripts($hook)
    {
        if ($hook !== 'settings_page_hdwebmobile-socials-floating') {
            return;
        }
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('hdsocials-admin-css', HDSOCIALS_PLUGIN_URL . 'assets/css/admin.css', [], HDSOCIALS_VERSION);
        wp_enqueue_script('hdsocials-admin-js', HDSOCIALS_PLUGIN_URL . 'assets/js/admin.js', ['wp-color-picker', 'jquery', 'jquery-ui-sortable'], HDSOCIALS_VERSION, true);
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_options_page(
            __('Socials Floating Settings', 'hdwebmobile-socials-floating'),
            __('Socials Floating', 'hdwebmobile-socials-floating'),
            'manage_options',
            'hdwebmobile-socials-floating',
            [$this, 'create_admin_page']
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
?>
        <div class="wrap hdsocials-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p>
                <?php esc_html_e('Configure the links and appearance for your floating social bar.', 'hdwebmobile-socials-floating'); ?><br>
                <?php esc_html_e('Love this plugin? Please consider supporting its development!', 'hdwebmobile-socials-floating'); ?>
                <a href="https://paypal.me/htrxuan/20" target="_blank" style="color:#d54e21; font-weight:bold;">
                    <?php esc_html_e('Donate via PayPal', 'hdwebmobile-socials-floating'); ?> &rarr;
                </a>
            </p>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('hdsocials_option_group');
                // This prints out all sections and fields
                do_settings_sections('hdwebmobile-socials-floating');
                submit_button();
                ?>
            </form>
            <?php $this->render_sibling_plugins_panel('hdwebmobile-socials-floating'); ?>
        </div>
        <?php
    }

    /**
     * A self-contained "more plugins by this author" panel, shown at the bottom of this
     * plugin's own settings page only (never a global admin notice or dashboard widget) --
     * stays within WordPress.org's guidelines for author self-promotion.
     */
    private function render_sibling_plugins_panel($exclude_slug)
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = array(
            'hdwebmobile-mix-match-bundles' => array(
                'name' => 'Mix & Match Bundles',
                'file' => 'hdwebmobile-mix-match-bundles/hdwebmobile-mix-match-bundles.php',
                'description' => __('Build-a-box bundles for WooCommerce.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-back-in-stock-waitlist' => array(
                'name' => 'Back In Stock & Waitlist',
                'file' => 'hdwebmobile-back-in-stock-waitlist/hdwebmobile-back-in-stock-waitlist.php',
                'description' => __('Notify customers the moment an out-of-stock product is back.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-spin-and-win' => array(
                'name' => 'Spin & Win',
            array('name' => 'Vendor Marketplace', 'file' => 'hdwebmobile-vendor-marketplace/hdwebmobile-vendor-marketplace.php', 'description' => 'Turn your store into a multi-vendor marketplace -- vendors can only ever touch their own products, orders, and earnings, enforced by WordPress\'s own core capability system.'),
                'file' => 'hdwebmobile-spin-and-win/hdwebmobile-spin-and-win.php',
                'description' => __('A gamified discount-wheel popup with server-enforced one spin per email.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-abandoned-cart-recovery' => array(
                'name' => 'Abandoned Cart Recovery',
                'file' => 'hdwebmobile-abandoned-cart-recovery/hdwebmobile-abandoned-cart-recovery.php',
                'description' => __('Recover lost sales with automatic cart-recovery emails.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-live-shopping-with-agora' => array(
                'name' => 'Live Shopping with Agora',
                'file' => 'hdwebmobile-live-shopping-with-agora/hdwebmobile-live-shopping-with-agora.php',
                'description' => __('Sell live on livestream video with real-time WooCommerce checkout.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-loyalty-points' => array(
                'name' => 'Loyalty Points & Store Credit',
                'file' => 'hdwebmobile-loyalty-points/hdwebmobile-loyalty-points.php',
                'description' => __('Customers earn points on orders and redeem them for a discount -- balance always computed from an audit-logged ledger.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-media-folder-roles' => array(
                'name' => 'Media Folder Roles',
                'file' => 'hdwebmobile-media-folder-roles/hd-media-folder-roles.php',
                'description' => __('Organize the Media Library into folders with per-role permissions.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-socials-floating' => array(
                'name' => 'Socials Floating',
                'file' => 'hdwebmobile-socials-floating/hdwebmobile-socials-floating.php',
                'description' => __('A floating social-media contact bar for your site.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-wishlist' => array(
                'name' => 'Wishlist',
                'file' => 'hdwebmobile-wishlist/hdwebmobile-wishlist.php',
                'description' => __('A wishlist that works with block-based Shop pages and guest browsing.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-formula-pricing' => array(
                'name' => 'Formula Pricing',
                'file' => 'hdwebmobile-formula-pricing/hdwebmobile-formula-pricing.php',
                'description' => __('Price products from customer-entered numbers via a safe formula -- no eval().', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-frequently-bought-together' => array(
                'name' => 'Frequently Bought Together',
                'file' => 'hdwebmobile-frequently-bought-together/hdwebmobile-frequently-bought-together.php',
                'description' => __('An Amazon-style widget that adds several related products in one click.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-checkout-delivery-scheduler' => array(
                'name' => 'Checkout Delivery Scheduler',
                'file' => 'hdwebmobile-checkout-delivery-scheduler/hdwebmobile-checkout-delivery-scheduler.php',
                'description' => __('Let customers pick a delivery date and time slot at checkout.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-product-options' => array(
                'name' => 'Product Options & Add-ons',
            array('name' => 'Request a Quote', 'file' => 'hdwebmobile-request-a-quote/hdwebmobile-request-a-quote.php', 'description' => 'Let customers request a custom price on any product -- only your own signed-off quote, viewed by its owner or an unguessable token, can ever become a cart price.'),
                'file' => 'hdwebmobile-product-options-add-ons/hdwebmobile-product-options-add-ons.php',
                'description' => __('Add paid text, dropdown, and checkbox options to products -- flat pricing only, no formulas.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-invoices-packing-slips' => array(
                'name' => 'Invoices & Packing Slips',
            array('name' => 'License Key Delivery', 'file' => 'hdwebmobile-license-key-delivery/hdwebmobile-license-key-delivery.php', 'description' => 'Sell software license keys through WooCommerce -- keys are pasted or generated in wp-admin, never imported from an uploaded file.'),
                'file' => 'hdwebmobile-invoices-packing-slips/hdwebmobile-invoices-packing-slips.php',
                'description' => __('On-demand invoice and packing-slip documents, printable to PDF from any browser.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-gift-cards' => array(
                'name' => 'Gift Cards',
                'file' => 'hdwebmobile-gift-cards/hdwebmobile-gift-cards.php',
                'description' => __('Sell digital gift cards redeemed through WooCommerce\'s own native coupon field.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-shipment-tracking' => array(
                'name' => 'Shipment Tracking',
                'file' => 'hdwebmobile-shipment-tracking/hdwebmobile-shipment-tracking.php',
                'description' => __('Add a carrier and tracking number to any order and show it to the customer.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-order-export' => array(
                'name' => 'Order Export',
                'file' => 'hdwebmobile-order-export/hdwebmobile-order-export.php',
                'description' => __('Export orders to CSV, filtered by date range and status.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-photo-video-reviews' => array(
                'name' => 'Photo & Video Reviews',
                'file' => 'hdwebmobile-photo-video-reviews/hdwebmobile-photo-video-reviews.php',
                'description' => __('Let customers attach photos and short videos to their product reviews.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-simple-subscriptions' => array(
                'name' => 'Simple Subscriptions',
                'file' => 'hdwebmobile-simple-subscriptions/hdwebmobile-simple-subscriptions.php',
                'description' => __('Sell recurring products -- renewal state is always plain scalar columns, never a serialized blob.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-product-enquiry' => array(
                'name' => 'Product Enquiry',
                'file' => 'hdwebmobile-product-enquiry/hdwebmobile-product-enquiry.php',
                'description' => __('A pre-sale "ask a question" form for any product -- every field is escaped at the point of output.', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-product-file-upload' => array(
                'name' => 'Product File Upload',
                'file' => 'hdwebmobile-product-file-upload/hdwebmobile-product-file-upload.php',
                'description' => __('Let customers upload a file with a product -- accepted types are always a hardcoded safe list, never "allow all".', 'hdwebmobile-socials-floating'),
            ),
            'hdwebmobile-otp-login' => array(
                'name' => 'OTP Login',
                'file' => 'hdwebmobile-otp-login/hdwebmobile-otp-login.php',
                'description' => __('Passwordless email one-time-code login -- a session is only ever created after a hashed, single-use code is verified.', 'hdwebmobile-socials-floating'),
            ),
        );

        unset($plugins[$exclude_slug]);
        ?>
        <div class="hdwebmobile-sibling-plugins" style="margin-top:2em;padding:1.5em;background:#fff;border:1px solid #dcdcde;border-radius:4px;max-width:800px;">
            <h2 style="margin-top:0;"><?php esc_html_e('More WooCommerce Plugins by HDWebmobile', 'hdwebmobile-socials-floating'); ?></h2>
            <table class="widefat striped" style="margin-top:1em;">
                <tbody>
                    <?php foreach ($plugins as $plugin) : ?>
                        <?php $active = is_plugin_active($plugin['file']); ?>
                        <?php $installed = file_exists(WP_PLUGIN_DIR . '/' . $plugin['file']); ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($plugin['name']); ?></strong><br />
                                <span style="opacity:.75;"><?php echo esc_html($plugin['description']); ?></span>
                            </td>
                            <td style="text-align:right;white-space:nowrap;vertical-align:middle;">
                                <?php if ($active) : ?>
                                    <span style="color:#1a7f37;">&#10003; <?php esc_html_e('Active', 'hdwebmobile-socials-floating'); ?></span>
                                <?php elseif ($installed) : ?>
                                    <a href="<?php echo esc_url(admin_url('plugins.php')); ?>" class="button button-small"><?php esc_html_e('Activate', 'hdwebmobile-socials-floating'); ?></a>
                                <?php else : ?>
                                    <a href="https://hdwebmobile.com/plugins/" target="_blank" rel="noopener noreferrer" class="button button-small"><?php esc_html_e('Learn More', 'hdwebmobile-socials-floating'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'hdsocials_option_group',
            'hdsocials_options',
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
                'default'           => [],
            ]
        );

        add_settings_section(
            'hdsocials_setting_section_appearance',
            __('Appearance Settings', 'hdwebmobile-socials-floating'),
            [$this, 'print_appearance_section_info'],
            'hdwebmobile-socials-floating'
        );

        add_settings_field(
            'bg_color',
            __('Background Color', 'hdwebmobile-socials-floating'),
            [$this, 'bg_color_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_appearance'
        );

        add_settings_field(
            'position',
            __('Position', 'hdwebmobile-socials-floating'),
            [$this, 'position_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_appearance'
        );

        add_settings_field(
            'hdsocials_hover_expand',
            esc_html__('Hover to Expand', 'hdwebmobile-socials-floating'),
            [$this, 'hover_expand_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_appearance'
        );

        // Business Hours Section
        add_settings_section(
            'hdsocials_setting_section_bh',
            __('Business Hours (Offline State)', 'hdwebmobile-socials-floating'),
            [$this, 'print_bh_section_info'],
            'hdwebmobile-socials-floating'
        );

        add_settings_field(
            'bh_enabled',
            __('Enable Business Hours', 'hdwebmobile-socials-floating'),
            [$this, 'bh_enabled_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_field(
            'bh_timezone',
            __('Timezone', 'hdwebmobile-socials-floating'),
            [$this, 'bh_timezone_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_field(
            'bh_schedule',
            __('Opening Hours', 'hdwebmobile-socials-floating'),
            [$this, 'bh_schedule_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_field(
            'bh_offline_style',
            __('Offline Appearance', 'hdwebmobile-socials-floating'),
            [$this, 'bh_offline_style_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_field(
            'bh_offline_tooltip',
            __('Offline Tooltip Message', 'hdwebmobile-socials-floating'),
            [$this, 'bh_offline_tooltip_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_field(
            'bh_apply_to',
            __('Apply To These Icons', 'hdwebmobile-socials-floating'),
            [$this, 'bh_apply_to_callback'],
            'hdwebmobile-socials-floating',
            'hdsocials_setting_section_bh'
        );

        add_settings_section(
            'hdsocials_setting_section_links',
            __('Social Links', 'hdwebmobile-socials-floating'),
            [$this, 'print_links_section_info'],
            'hdwebmobile-socials-floating'
        );

        $options = self::get_options();

        $socials = [
            'facebook'  => ['label' => __('Facebook URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['facebook_order']],
            'messenger' => ['label' => __('Messenger Username/ID', 'hdwebmobile-socials-floating'), 'type' => 'text', 'placeholder' => 'username', 'desc' => __('Input your Facebook page ID or username.', 'hdwebmobile-socials-floating'), 'order' => $options['messenger_order']],
            'twitter'   => ['label' => __('X (Twitter) URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['twitter_order']],
            'instagram' => ['label' => __('Instagram URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['instagram_order']],
            'linkedin'  => ['label' => __('LinkedIn URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['linkedin_order']],
            'youtube'   => ['label' => __('YouTube URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['youtube_order']],
            'tiktok'    => ['label' => __('TikTok URL', 'hdwebmobile-socials-floating'), 'type' => 'url', 'placeholder' => 'https://...', 'desc' => '', 'order' => $options['tiktok_order']],
            'whatsapp'  => ['label' => __('WhatsApp Number', 'hdwebmobile-socials-floating'), 'type' => 'text', 'placeholder' => '1234567890', 'desc' => __('Enter number with country code, without + or spaces.', 'hdwebmobile-socials-floating'), 'order' => $options['whatsapp_order']],
            'zalo'      => ['label' => __('Zalo Phone Number', 'hdwebmobile-socials-floating'), 'type' => 'text', 'placeholder' => '0912345678', 'desc' => __('Enter your Zalo phone number.', 'hdwebmobile-socials-floating'), 'order' => $options['zalo_order']],
            'telegram'  => ['label' => __('Telegram Username', 'hdwebmobile-socials-floating'), 'type' => 'text', 'placeholder' => 'username', 'desc' => __('Enter username without @.', 'hdwebmobile-socials-floating'), 'order' => $options['telegram_order']],
            'mobile'    => ['label' => __('Mobile Number', 'hdwebmobile-socials-floating'), 'type' => 'text', 'placeholder' => '0912345678', 'desc' => __('Enter phone number for click-to-call.', 'hdwebmobile-socials-floating'), 'order' => $options['mobile_order']],
            'custom'    => ['label' => __('Custom Link', 'hdwebmobile-socials-floating'), 'type' => 'custom', 'placeholder' => 'https://...', 'desc' => __('Enter custom URL and paste raw SVG code below it.', 'hdwebmobile-socials-floating'), 'order' => $options['custom_order']],
        ];

        uasort($socials, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        foreach ($socials as $id => $data) {
            add_settings_field(
                $id,
                $data['label'],
                [$this, 'social_field_callback'],
                'hdwebmobile-socials-floating',
                'hdsocials_setting_section_links',
                ['id' => $id, 'data' => $data]
            );
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = [];
        $options   = get_option('hdsocials_options');

        // Sanitize bg_color with the most restrictive function that fits: sanitize_hex_color().
        if (isset($input['bg_color'])) {
            $new_input['bg_color'] = sanitize_hex_color($input['bg_color']);
        }

        // Validate position against an explicit allowlist — reject anything not in it.
        $allowed_positions = ['left', 'right', 'bottom_left', 'bottom', 'bottom_right', 'top_left', 'top', 'top_right'];
        if (isset($input['position']) && in_array($input['position'], $allowed_positions, true)) {
            $new_input['position'] = $input['position'];
        }

        // Sanitize URL fields with esc_url_raw() to store clean, valid URLs.
        $url_fields = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'custom_url'];
        foreach ($url_fields as $field) {
            if (isset($input[$field])) {
                $new_input[$field] = esc_url_raw($input[$field]);
            }
        }

        // Sanitize plain text fields (usernames, phone numbers, etc.).
        $text_fields = ['messenger', 'whatsapp', 'zalo', 'telegram', 'mobile'];
        foreach ($text_fields as $field) {
            if (isset($input[$field])) {
                $new_input[$field] = sanitize_text_field($input[$field]);
            }
        }

        $order_fields = ['facebook_order', 'messenger_order', 'twitter_order', 'instagram_order', 'linkedin_order', 'youtube_order', 'tiktok_order', 'whatsapp_order', 'zalo_order', 'telegram_order', 'mobile_order', 'custom_order'];
        foreach ($order_fields as $field) {
            if (isset($input[$field])) {
                $new_input[$field] = intval($input[$field]);
            }
        }

        if (isset($input['custom_icon'])) {
            // Use wp_kses to allow safe SVG markup
            $allowed_html = [
                'svg'   => [
                    'class'           => true,
                    'aria-hidden'     => true,
                    'aria-labelledby' => true,
                    'role'            => true,
                    'xmlns'           => true,
                    'width'           => true,
                    'height'          => true,
                    'viewbox'         => true, // Lowercase viewbox for kses
                ],
                'g'     => ['fill' => true, 'transform' => true],
                'title' => ['title' => true],
                'path'  => [
                    'd'    => true,
                    'fill' => true,
                ],
            ];
            $new_input['custom_icon'] = wp_kses($input['custom_icon'], $allowed_html);
        }

        $new_input['hover_expand'] = isset($input['hover_expand']) ? 1 : 0;

        // Business Hours sanitization
        $new_input['bh_enabled'] = isset($input['bh_enabled']) ? 1 : 0;

        if (isset($input['bh_timezone'])) {
            $timezone_identifiers = timezone_identifiers_list();
            if (in_array($input['bh_timezone'], $timezone_identifiers, true)) {
                $new_input['bh_timezone'] = $input['bh_timezone'];
            } else {
                $new_input['bh_timezone'] = 'UTC';
            }
        }

        if (isset($input['bh_schedule']) && is_array($input['bh_schedule'])) {
            $allowed_days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            $allowed_statuses = ['open', 'closed', '24h'];
            $new_schedule = [];
            foreach ($allowed_days as $day) {
                if (isset($input['bh_schedule'][$day]) && is_array($input['bh_schedule'][$day])) {
                    $day_input = $input['bh_schedule'][$day];
                    $status = isset($day_input['status']) && in_array($day_input['status'], $allowed_statuses, true) ? $day_input['status'] : 'open';

                    // Simple regex for HH:MM
                    $open  = isset($day_input['open']) && preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $day_input['open']) ? $day_input['open'] : '09:00';
                    $close = isset($day_input['close']) && preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $day_input['close']) ? $day_input['close'] : '17:00';

                    $new_schedule[$day] = [
                        'status' => $status,
                        'open'   => $open,
                        'close'  => $close,
                    ];
                }
            }
            $new_input['bh_schedule'] = $new_schedule;
        }

        if (isset($input['bh_offline_style'])) {
            $allowed_styles = ['dim', 'hide', 'badge'];
            if (in_array($input['bh_offline_style'], $allowed_styles, true)) {
                $new_input['bh_offline_style'] = $input['bh_offline_style'];
            }
        }

        if (isset($input['bh_offline_tooltip'])) {
            $new_input['bh_offline_tooltip'] = sanitize_text_field($input['bh_offline_tooltip']);
        }

        if (isset($input['bh_apply_to']) && is_array($input['bh_apply_to'])) {
            $allowed_networks = ['facebook', 'messenger', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'whatsapp', 'zalo', 'telegram', 'mobile', 'custom'];
            $new_input['bh_apply_to'] = [];
            foreach ($input['bh_apply_to'] as $net) {
                if (in_array($net, $allowed_networks, true)) {
                    $new_input['bh_apply_to'][] = $net;
                }
            }
        } else {
            $new_input['bh_apply_to'] = []; // Empty if nothing checked
        }

        return $new_input;
    }

    /**
     * Print appearance section text
     */
    public function print_appearance_section_info()
    {
        esc_html_e('Choose the colors and position for the floating bar.', 'hdwebmobile-socials-floating');
    }

    /**
     * Print links section text
     */
    public function print_links_section_info()
    {
        echo '<p>' . esc_html__('Enter the full URLs for the social platforms you want to display. Leave blank to hide.', 'hdwebmobile-socials-floating') . '</p>';
        echo '<p>' . esc_html__('Drag the ☰ handle on the right of each row to reorder the icons. The order here reflects the order they appear on your site.', 'hdwebmobile-socials-floating') . '</p>';
    }

    /**
     * Print Business Hours section text
     */
    public function print_bh_section_info()
    {
        echo '<p>' . esc_html__('Control when your icons are visible. Useful to prevent messaging requests outside of your working hours.', 'hdwebmobile-socials-floating') . '</p>';
    }

    /**
     * Get options with defaults
     */
    public static function get_options()
    {
        $defaults = [
            'bg_color'      => '#000000',
            'position'      => 'left',
            'hover_expand'  => 0,
            'facebook'      => '',
            'facebook_order' => 1,
            'messenger'     => '',
            'messenger_order' => 2,
            'twitter'       => '',
            'twitter_order' => 3,
            'instagram'     => '',
            'instagram_order' => 4,
            'linkedin'      => '',
            'linkedin_order' => 5,
            'youtube'       => '',
            'youtube_order' => 6,
            'tiktok'        => '',
            'tiktok_order'  => 7,
            'whatsapp'      => '',
            'whatsapp_order' => 8,
            'zalo'          => '',
            'zalo_order'    => 9,
            'telegram'      => '',
            'telegram_order' => 10,
            'mobile'        => '',
            'mobile_order'  => 11,
            'custom_url'    => '',
            'custom_order'  => 12,
            'custom_icon'   => '',
            'bh_enabled'         => 0,
            'bh_timezone'        => 'UTC',
            'bh_schedule'        => \htrxuan\hdsocials\Hours::default_schedule(),
            'bh_offline_style'   => 'dim',
            'bh_offline_tooltip' => '',
            'bh_apply_to'        => ['whatsapp', 'messenger', 'zalo', 'telegram', 'mobile']
        ];
        return wp_parse_args(get_option('hdsocials_options', []), $defaults);
    }

    /**
     * Background Color callback
     */
    public function bg_color_callback()
    {
        $options = self::get_options();
        printf(
            '<input type="text" id="bg_color" name="hdsocials_options[bg_color]" value="%s" class="hdsocials-color-picker" />',
            esc_attr($options['bg_color'])
        );
    }

    /**
     * Position callback
     */
    public function position_callback()
    {
        $options = self::get_options();
        $positions = [
            'left'         => __('Left Center', 'hdwebmobile-socials-floating'),
            'right'        => __('Right Center', 'hdwebmobile-socials-floating'),
            'bottom_left'  => __('Bottom Left', 'hdwebmobile-socials-floating'),
            'bottom'       => __('Bottom Center', 'hdwebmobile-socials-floating'),
            'bottom_right' => __('Bottom Right', 'hdwebmobile-socials-floating'),
            'top_left'     => __('Top Left', 'hdwebmobile-socials-floating'),
            'top'          => __('Top Center', 'hdwebmobile-socials-floating'),
            'top_right'    => __('Top Right', 'hdwebmobile-socials-floating'),
        ];

        echo '<select id="position" name="hdsocials_options[position]">';
        foreach ($positions as $val => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($val),
                selected($options['position'], $val, false),
                esc_html($label)
            );
        }
        echo '</select>';
    }

    /**
     * Hover Expand callback
     */
    public function hover_expand_callback()
    {
        $options = self::get_options();
        printf(
            '<input type="checkbox" name="hdsocials_options[hover_expand]" value="1" %s />',
            checked(1, $options['hover_expand'], false)
        );
        echo '<p class="description">' . esc_html__('Collapse the social bar behind a toggle button that opens on hover with a smooth animation.', 'hdwebmobile-socials-floating') . '</p>';
    }

    /**
     * Social link callback
     */
    public function social_field_callback($args)
    {
        $options = self::get_options();
        $id = $args['id'];
        $data = $args['data'];

        echo '<div style="display:flex; align-items:flex-start; gap:10px;">';

        if ($id === 'custom') {
            echo '<div style="flex-grow:1;">';
            printf(
                '<input type="url" id="custom_url" name="hdsocials_options[custom_url]" value="%1$s" class="regular-text" placeholder="https://..." style="margin-bottom: 10px; display: block;" />',
                esc_attr($options['custom_url'])
            );
            printf(
                '<textarea id="custom_icon" name="hdsocials_options[custom_icon]" rows="3" class="large-text code" placeholder="<svg>...</svg>">%s</textarea>',
                esc_textarea($options['custom_icon'])
            );
            echo '</div>';
        } else {
            printf(
                '<input type="%1$s" id="%2$s" name="hdsocials_options[%2$s]" value="%3$s" class="regular-text" placeholder="%4$s" />',
                esc_attr($data['type']),
                esc_attr($id),
                esc_attr($options[$id]),
                esc_attr($data['placeholder'])
            );
        }

        printf(
            '<input type="hidden" name="hdsocials_options[%s_order]" value="%d" class="hdsocials-order-input" />',
            esc_attr($id === 'custom' ? 'custom' : $id),
            intval($options[($id === 'custom' ? 'custom' : $id) . '_order'])
        );

        // Build the inline style separately so it can be escaped as an attribute value.
        $handle_style = 'cursor: move; padding: 4px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 4px; display:inline-flex; align-items:center;';
        if ($id === 'custom') {
            $handle_style .= ' margin-top: 4px;';
        }
        echo '<span class="hdsocials-drag-handle" title="' . esc_attr__('Drag to reorder', 'hdwebmobile-socials-floating') . '" style="' . esc_attr($handle_style) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></span>';

        echo '</div>';

        if (!empty($data['desc'])) {
            echo '<p class="description" style="margin-top: 5px;">' . esc_html($data['desc']) . '</p>';
        }
    }

    /**
     * Callback for Business Hours Enable
     */
    public function bh_enabled_callback()
    {
        $options = self::get_options();
        printf(
            '<input type="checkbox" id="bh_enabled" name="hdsocials_options[bh_enabled]" value="1" %s />',
            checked(1, $options['bh_enabled'], false)
        );
        echo '<label for="bh_enabled">' . esc_html__('Activate Business Hours limitation', 'hdwebmobile-socials-floating') . '</label>';
    }

    /**
     * Callback for Timezone
     */
    public function bh_timezone_callback()
    {
        $options = self::get_options();
        $tzlist = timezone_identifiers_list();
        echo '<select name="hdsocials_options[bh_timezone]" class="regular-text">';
        foreach ($tzlist as $tz) {
            printf(
                '<option value="%1$s" %2$s>%1$s</option>',
                esc_attr($tz),
                selected($options['bh_timezone'], $tz, false)
            );
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__('Select the timezone for your business hours (ignores WordPress general settings for reliability).', 'hdwebmobile-socials-floating') . '</p>';
    }

    /**
     * Callback for Schedule Setup
     */
    public function bh_schedule_callback()
    {
        $options = self::get_options();
        $schedule = $options['bh_schedule'];
        $days = [
            'mon' => __('Monday', 'hdwebmobile-socials-floating'),
            'tue' => __('Tuesday', 'hdwebmobile-socials-floating'),
            'wed' => __('Wednesday', 'hdwebmobile-socials-floating'),
            'thu' => __('Thursday', 'hdwebmobile-socials-floating'),
            'fri' => __('Friday', 'hdwebmobile-socials-floating'),
            'sat' => __('Saturday', 'hdwebmobile-socials-floating'),
            'sun' => __('Sunday', 'hdwebmobile-socials-floating')
        ];

        echo '<table class="hdsocials-bh-schedule">';
        foreach ($days as $key => $label) {
            $day_data = isset($schedule[$key]) ? $schedule[$key] : ['status' => 'open', 'open' => '09:00', 'close' => '17:00'];
            $status = $day_data['status'];
        ?>
            <tr>
                <td style="width: 120px; font-weight: 600;"><?php echo esc_html($label); ?></td>
                <td>
                    <div class="hdsocials-bh-radios">
                        <label><input type="radio" name="hdsocials_options[bh_schedule][<?php echo esc_attr($key); ?>][status]" value="open" class="hdsocials-bh-status" <?php checked($status, 'open'); ?>> <?php esc_html_e('Open', 'hdwebmobile-socials-floating'); ?></label>
                        <label><input type="radio" name="hdsocials_options[bh_schedule][<?php echo esc_attr($key); ?>][status]" value="closed" class="hdsocials-bh-status" <?php checked($status, 'closed'); ?>> <?php esc_html_e('Closed', 'hdwebmobile-socials-floating'); ?></label>
                        <label><input type="radio" name="hdsocials_options[bh_schedule][<?php echo esc_attr($key); ?>][status]" value="24h" class="hdsocials-bh-status" <?php checked($status, '24h'); ?>> <?php esc_html_e('24 hours', 'hdwebmobile-socials-floating'); ?></label>
                    </div>
                </td>
                <td class="hdsocials-bh-times" style="<?php echo ($status !== 'open') ? 'opacity:0.4; pointer-events:none;' : ''; ?>">
                    <input type="time" name="hdsocials_options[bh_schedule][<?php echo esc_attr($key); ?>][open]" value="<?php echo esc_attr($day_data['open']); ?>" />
                    <span> - </span>
                    <input type="time" name="hdsocials_options[bh_schedule][<?php echo esc_attr($key); ?>][close]" value="<?php echo esc_attr($day_data['close']); ?>" />
                </td>
            </tr>
        <?php
        }
        echo '</table>';
    }

    /**
     * Callback for Offline Style
     */
    public function bh_offline_style_callback()
    {
        $options = self::get_options();
        $style = $options['bh_offline_style'];
        ?>
        <label><input type="radio" name="hdsocials_options[bh_offline_style]" value="dim" <?php checked($style, 'dim'); ?>> <?php esc_html_e('Dimmed (Disabled click and grayscale)', 'hdwebmobile-socials-floating'); ?></label><br>
        <label><input type="radio" name="hdsocials_options[bh_offline_style]" value="hide" <?php checked($style, 'hide'); ?>> <?php esc_html_e('Hidden (Icon disappears entirely)', 'hdwebmobile-socials-floating'); ?></label><br>
        <label><input type="radio" name="hdsocials_options[bh_offline_style]" value="badge" <?php checked($style, 'badge'); ?>> <?php esc_html_e('Red Badge (Shows "Closed" badge and dims)', 'hdwebmobile-socials-floating'); ?></label>
<?php
    }

    /**
     * Callback for Offline Tooltip Message
     */
    public function bh_offline_tooltip_callback()
    {
        $options = self::get_options();
        printf(
            '<input type="text" name="hdsocials_options[bh_offline_tooltip]" value="%s" class="regular-text" placeholder="%s" />',
            esc_attr($options['bh_offline_tooltip']),
            esc_attr__('e.g. Back online at 9 AM', 'hdwebmobile-socials-floating')
        );
        echo '<p class="description">' . esc_html__('Shown when a user hovers over a dimmed or badged offline icon.', 'hdwebmobile-socials-floating') . '</p>';
    }

    /**
     * Callback for Apply To target icons
     */
    public function bh_apply_to_callback()
    {
        $options = self::get_options();
        $apply_to = $options['bh_apply_to'];
        $networks = [
            'whatsapp'  => 'WhatsApp',
            'messenger' => 'Messenger',
            'zalo'      => 'Zalo',
            'telegram'  => 'Telegram',
            'mobile'    => 'Mobile (Call)',
            'facebook'  => 'Facebook',
            'twitter'   => 'X (Twitter)',
            'instagram' => 'Instagram',
            'linkedin'  => 'LinkedIn',
            'youtube'   => 'YouTube',
            'tiktok'    => 'TikTok',
            'custom'    => 'Custom Link'
        ];

        echo '<div style="display:flex; flex-wrap:wrap; gap:10px; max-width:600px;">';
        foreach ($networks as $key => $label) {
            echo '<label style="margin-right:15px;"><input type="checkbox" name="hdsocials_options[bh_apply_to][]" value="' . esc_attr($key) . '" ' . checked(in_array($key, $apply_to, true), true, false) . '> ' . esc_html($label) . '</label>';
        }
        echo '</div>';
        echo '<p class="description">' . esc_html__('Select which communication channels are restricted by your business hours. Pure profile links usually remain visible.', 'hdwebmobile-socials-floating') . '</p>';
    }
}
