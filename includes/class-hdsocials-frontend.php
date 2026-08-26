<?php

namespace htrxuan\hdsocials;

// Prevent direct file access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend Class
 */
class Frontend
{

    /**
     * Instance of this class.
     *
     * @var Frontend
     */
    private static $instance = null;

    /**
     * Get instance of this class.
     *
     * @return Frontend
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
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_footer', [$this, 'render_socials_floating_bar']);
    }

    /**
     * Enqueue styles.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('hdsocials-css', HDSOCIALS_PLUGIN_URL . 'assets/css/hdsocials.css', [], HDSOCIALS_VERSION);

        // Add dynamic CSS for background color
        $options = Admin::get_options();
        // sanitize_hex_color() ensures $bg_color is a valid #rrggbb or #rgb value (or empty).
        $bg_color = sanitize_hex_color($options['bg_color']);

        if ($bg_color) {
            // Calculate brightness to determine icon color
            $hex = str_replace('#', '', $bg_color);
            if (strlen($hex) == 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));

            // Perceived brightness formula
            $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

            $is_light = $brightness > 155; // Threshold for determining if background is light

            // All values below are computed from validated hex input; no user strings are interpolated.
            $icon_color   = $is_light ? '#000000' : '#ffffff';
            $icon_bg      = $is_light ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)';
            $hover_bg     = $is_light ? 'rgba(0, 0, 0, 0.15)' : 'rgba(255, 255, 255, 0.2)';
            $border_color = $is_light ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.2)';

            // $bg_color is validated by sanitize_hex_color(); all other variables are computed
            // from that value and contain no user input — safe for CSS output.
            $dynamic_css = ":root {
                --hdsocials-bg-color: {$bg_color};
                --hdsocials-icon-color: {$icon_color};
                --hdsocials-icon-bg: {$icon_bg};
                --hdsocials-hover-bg: {$hover_bg};
                --hdsocials-border-color: {$border_color};
            }";
            wp_add_inline_style('hdsocials-css', $dynamic_css);
        }
    }

    /**
     * Render the floating bar.
     */
    public function render_socials_floating_bar()
    {
        $options = Admin::get_options();

        $socials_raw = [
            'facebook'  => ['val' => $options['facebook'], 'order' => $options['facebook_order']],
            'messenger' => ['val' => $options['messenger'], 'order' => $options['messenger_order']],
            'twitter'   => ['val' => $options['twitter'], 'order' => $options['twitter_order']],
            'instagram' => ['val' => $options['instagram'], 'order' => $options['instagram_order']],
            'linkedin'  => ['val' => $options['linkedin'], 'order' => $options['linkedin_order']],
            'youtube'   => ['val' => $options['youtube'], 'order' => $options['youtube_order']],
            'tiktok'    => ['val' => $options['tiktok'], 'order' => $options['tiktok_order']],
            'whatsapp'  => ['val' => $options['whatsapp'], 'order' => $options['whatsapp_order']],
            'zalo'      => ['val' => $options['zalo'], 'order' => $options['zalo_order']],
            'telegram'  => ['val' => $options['telegram'], 'order' => $options['telegram_order']],
            'mobile'    => ['val' => $options['mobile'], 'order' => $options['mobile_order']],
            'custom'    => ['val' => $options['custom_url'], 'order' => $options['custom_order']]
        ];

        // Filter out empty ones
        $active_socials = [];
        foreach ($socials_raw as $network => $data) {
            if (!empty($data['val'])) {
                $active_socials[$network] = $data;
            }
        }

        if (empty($active_socials)) {
            return;
        }

        // Sort by order
        uasort($active_socials, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        $position = esc_attr($options['position']);
        $hover_class = !empty($options['hover_expand']) ? ' hdsocials-is-expandable' : '';

        echo '<div class="hdsocials-floating-bar hdsocials-pos-' . esc_attr($position) . esc_attr($hover_class) . '">';

        if (!empty($options['hover_expand'])) {
            echo '<div class="hdsocials-toggle-icon hdsocials-icon hdsocials-toggle">';
            // Support icon (headset)
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="hdsocials-svg-plus"><path d="M12,1C7,1 3,5 3,10V17A3,3 0 0,0 6,20H9V12H5V10A7,7 0 0,1 12,3A7,7 0 0,1 19,10V12H15V20H18A3,3 0 0,0 21,17V10C21,5 17,1 12,1Z" /></svg>';
            echo '</div>';
        }

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
                'fill'            => true,
            ],
            'g'     => ['fill' => true, 'transform' => true],
            'title' => ['title' => true],
            'path'  => [
                'd'    => true,
                'fill' => true,
            ],
            // For custom SVGs that may use comments
            '!--' => true,
        ];

        // Check Business Hours status via the new Hours class.
        $is_open = \htrxuan\hdsocials\Hours::is_open($options);
        $bh_enabled = !empty($options['bh_enabled']);
        $bh_apply_to = isset($options['bh_apply_to']) && is_array($options['bh_apply_to']) ? $options['bh_apply_to'] : [];
        $offline_style = isset($options['bh_offline_style']) ? $options['bh_offline_style'] : 'dim';

        foreach ($active_socials as $network => $data) {
            $val = $data['val'];

            // Check if this specific network is affected by offline hours
            $is_affected = $bh_enabled && in_array($network, $bh_apply_to, true);

            if ($is_affected && !$is_open) {
                if ($offline_style === 'hide') {
                    // Skip rendering this icon completely
                    continue;
                }
            }

            $icon_html = $this->get_svg_icon($network, $options);
            $final_url = $this->get_network_url($network, $val);

            $classes = ['hdsocials-icon', 'hdsocials-' . esc_attr($network)];
            $href = $final_url;
            $tooltip = ucfirst($network);
            $badge_html = '';

            // Apply offline styling if affected and closed
            if ($is_affected && !$is_open) {
                // Prevent click
                $href = '#';

                if ($offline_style === 'badge') {
                    $classes[] = 'hdsocials-offline hdsocials-offline-badge';
                    $badge_html = '<span class="hdsocials-badge">' . esc_html__('Closed', 'hdwebmobile-socials-floating') . '</span>';
                } elseif ($offline_style === 'dim') {
                    $classes[] = 'hdsocials-offline';
                }

                // Override tooltip if custom offline tooltip provided
                if (!empty($options['bh_offline_tooltip'])) {
                    $tooltip = $options['bh_offline_tooltip'];
                }
            }

            printf(
                '<a href="%1$s" class="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s" title="%3$s">%4$s%5$s</a>',
                esc_url($href),
                esc_attr(implode(' ', $classes)),
                esc_attr($tooltip),
                wp_kses($icon_html, $allowed_html), // Safely allow SVGs
                wp_kses_post($badge_html)
            );
        }

        echo '</div>';
    }

    /**
     * Build appropriate URL based on the network and input value.
     */
    private function get_network_url($network, $val)
    {
        switch ($network) {
            case 'messenger':
                return 'https://m.me/' . ltrim($val, '@');
            case 'whatsapp':
                return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $val);
            case 'zalo':
                return 'https://zalo.me/' . preg_replace('/[^0-9]/', '', $val);
            case 'telegram':
                return 'https://t.me/' . ltrim($val, '@');
            case 'mobile':
                return 'tel:' . preg_replace('/[^0-9+]/', '', $val);
            default:
                return $val;
        }
    }

    /**
     * Return SVG HTML for specific networks.
     */
    private function get_svg_icon($network, $options)
    {
        $svgs = [
            'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>',
            'messenger' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.477 2 2 6.146 2 11.26c0 2.923 1.488 5.584 3.916 7.319v3.42l3.582-1.956c8.117 2.235 12.502-3.832 12.502-8.783C22 6.146 17.523 2 12 2zm1.2 12.42-3.15-3.36-6.15 3.36 6.75-7.14 3.15 3.36 6.15-3.36-6.75 7.14z"/></svg>',
            'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm3.98-10.956a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"/></svg>',
            'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zM7.119 20.452H3.554V9h3.565v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.377.55a3.016 3.016 0 0 0-2.122 2.136C0 8.186 0 12 0 12s0 3.814.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.55 9.376.55 9.376.55s7.505 0 9.377-.55a3.016 3.016 0 0 0 2.122-2.136C24 15.814 24 12 24 12s0-3.814-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>',
            'whatsapp' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.662-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>',
            'zalo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24" height="24" fill="currentColor"><path d="M24 4C12.954 4 4 12.954 4 24c0 4.178 1.18 8.08 3.225 11.374L4 44l8.95-2.866A19.875 19.875 0 0 0 24 44c11.046 0 20-8.954 20-20S35.046 4 24 4zm8.5 28.5h-17v-3l10.5-12H15.5v-3h17v3L22 29.5h10.5v3z"/></svg>',
            'telegram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="m20.665 3.717-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42 10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785l3.019-14.228c.309-1.239-.473-1.8-1.282-1.434z"/></svg>',
            'mobile' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57a1.02 1.02 0 0 0-1.02.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.21a.96.96 0 0 0 .25-1A11.36 11.36 0 0 1 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>',
            'custom' => !empty($options['custom_icon']) ? $options['custom_icon'] : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>'
        ];

        return isset($svgs[$network]) ? $svgs[$network] : '';
    }
}
