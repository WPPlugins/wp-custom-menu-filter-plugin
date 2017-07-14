=== WP Custom Menu Filter Plugin ===
Contributors: wpsmith
Donate link: http://wpsmith.net/wp-custom-menu-filter-plugin/#donate
Tags: style, css, login, admin, registration, theme, multisite
Requires at least: 3.0.5
Tested up to: 3.1
Stable tag: 0.1

Displays different menus for visitors and users logged in without using CSS.

== Description ==

WP Custom Menu Filter Plugin uses a new filter hook in WordPress 3.1 that allows Custom Menus to be filtered. Instead of using the generic CSS {display:none;}, this plugin actually excludes nav menu items from even being created. While it uses the CSS class tag in the Custom Menu to determine which custom menu items should be excluded, it does not use CSS to hide the menu items, which savvy users can discover. Instead using one custom menu, the user can hide portions of their custom menu to visitors (even the savvy ones) while revealing other portions of their menu to users who are logged in.

== Installation ==

1. Upload the `wp-custom-menu-filter-plugin` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Locate the 'Custom Menu Filter Settings' item on the 'Appearance' menu
4. Change your options, and you are good to go!

== Frequently Asked Questions ==

= How do I add a CSS class to my custom menu? =
1. At `/wp-admin/nav-menus.php` found under Appearance > Menus. Then select Screen Options at the top right. Then check CSS Classes. Then click Screen Options to hide the menu again.
2. Click down arrow beside the menu item. You should see an input box for CSS Classes (Optional).
3. Fill in with whatever CSS class you'd like. It is space-separated, so be sure to enter each class as one item (e.g., admin, admin-class, NOT admin class).
4. Click Save Menu
5. Be sure to assign a menu to a theme location as this will determine whether the menu appears in the filter settings page. Click Save.
6. Navigate to `wp-admin/themes.php?page=wps-filter-menu` which is found under Appearance > Custom Menu Filter Settings
7. Enter CSS Class Name that you'd like to have filtered out.

Again this plugin does not use CSS classes to hide menu items. Instead it uses the CSS Class information on the backend to filter these items out all together.



