=== HDWebmobile Socials Floating ===
Contributors: htrxuan
Donate link: https://paypal.me/htrxuan/20
Tags: social, floating, social icons, business hours, office hours
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The only floating social bar that knows when you are open. Set your business hours to dim or hide your chat buttons when you're offline.

== Description ==

HDWebMobile Socials Floating is more than just a sleek, glassmorphic floating bar with icons for major social networks. It is the **only social bar that includes a smart Business Hours feature** directly built-in.

Are you tired of getting WhatsApp or Messenger requests at 2 AM? Our plugin solves this. You can define your weekly business hours and timezone. Outside of those hours, your direct contact buttons (WhatsApp, Messenger, Phone, etc.) can automatically dim, turn into a "Closed" badge, or completely disappear. 

It also automatically generates deep links for platforms like WhatsApp, Zalo, and Messenger to make connecting with your users effortless.

= Features =
*   **Unique: Smart Business Hours:** Configure your weekly open/close times in your local timezone.
*   **Unique: Offline States:** Dim, badge ("Closed"), or hide specific icons automatically outside working hours.
*   Display up to 11 popular social networks including WhatsApp, Telegram, Facebook, X (Twitter), Instagram, and more.
*   Drag-and-drop ordering to easily sort the icons.
*   Premium, glassmorphism design with smooth hover animations.
*   8 different screen positions (Left Center, Top Right, Bottom Center, etc.).
*   Automatic deep link generation for Messenger, Telegram, WhatsApp, Zalo, and Mobile numbers.
*   Configurable floating background color.
*   Add a custom SVG icon and link.

= Enjoying this plugin? =
If it helps your site, please consider [leaving an honest review on WordPress.org](https://wordpress.org/support/plugin/hdwebmobile-socials-floating/reviews/#new-post) -- it helps other WordPress users find it. Found a problem or have an idea? Please [open a support topic](https://wordpress.org/support/plugin/hdwebmobile-socials-floating/) first so it can be fixed.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure the plugin by going to Settings > Socials Floating.
4. Input your social links, select your preferred background color, choose a position, and save the settings.

== How to Use ==

= 1. Add your social links =
Go to **Settings > Socials Floating** (Screenshot 1). Under **Social Links**, paste the full URL for any network you want to show (Facebook, Instagram, LinkedIn, YouTube, TikTok, X/Twitter) — leave a field blank to hide that icon entirely. WhatsApp, Zalo, and Mobile just need a phone number (with country code, no `+` or spaces); Messenger and Telegram just need a username — the plugin builds the correct `wa.me`/`t.me`/etc. link for you automatically.

= 2. Choose appearance and position =
Pick a **Background Color** and a **Position** (one of 8 screen corners/edges). Turn on **Hover to Expand** if you want the bar to collapse into a single toggle button that fans out its icons on hover/tap, instead of always showing every icon at once (Screenshot 2 shows the expanded state).

= 3. Reorder the icons =
Every icon row in Social Links has a `☰` drag handle on the right — drag rows up or down to change the order icons appear in on the frontend.

= 4. Turn on Business Hours (optional) =
Tick **Enable Business Hours** and pick your timezone (this is used instead of your WordPress general timezone setting, so it stays correct regardless of server config). Set each day of the week to **Open** (with start/end times), **Closed**, or **24 hours**.

= 5. Decide what happens outside business hours =
* **Offline Appearance** — choose whether affected icons go **Dimmed** (grayscale + disabled click), **Hidden** (removed entirely), or get a **Red "Closed" Badge** (Screenshot 2 shows the dimmed state on WhatsApp/Messenger/Zalo/Telegram/Mobile while Facebook and X stay active).
* **Apply To These Icons** — tick only the direct-contact channels you don't want people messaging at 2 AM (WhatsApp, Messenger, etc.); leave profile-only links like Facebook or Instagram unticked so they stay clickable at all times.
* **Offline Tooltip Message** — optional custom text shown on hover, e.g. "Back online at 9 AM".

= 6. Save and check the frontend =
Click **Save Changes**, then view any page on your site. The bar appears in your chosen position; if Business Hours is on, icons automatically switch between their online and offline appearance as the clock crosses your configured open/close times — no further action needed.

== Frequently Asked Questions ==

= How do the Business Hours work? =
In the Settings page, enable "Business Hours" and select your timezone. Then, define when your business is open for each day of the week. You can choose which icons are affected (e.g., only hide WhatsApp, but leave Facebook visible) and how they should appear when offline (Dimmed, Hidden, or with a Red Badge).

= Do I need to enter the full URL for WhatsApp or Telegram? =
No! Simply enter your phone number (for WhatsApp/Zalo) or your username (for Telegram/Messenger). The plugin automatically generates the correct links (`wa.me`, `t.me`, etc.) to open the respective apps.

= How do I reorder the icons? =
On the settings page, simply drag and drop the `☰` handle next to any social network to reorder them exactly how you like.

== Screenshots ==

1. The full settings screen: appearance, Business Hours weekly schedule, offline behavior, and social links.
2. The expanded floating bar on the frontend — active icons at full color, direct-contact icons dimmed outside business hours.

== Changelog ==

= 1.1.3 =
* Confirmed compatibility with WordPress 7.1.

= 1.1.2 =
* Confirmed compatibility with WordPress 7.0.

= 1.1.1 =
* Compliance: Removed the user-entered Custom CSS setting and frontend custom CSS injection to align with WordPress.org plugin guidelines.

= 1.1.0 =
* Feature: Introduced smart Business Hours. Automatically dim, badge, or hide real-time contact channels (WhatsApp, Messenger, Phone, etc.) outside of your working hours.
* Tweak: Added visual offline states and custom tooltips.

= 1.0.0 =
* Initial release.
