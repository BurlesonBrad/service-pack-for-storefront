=== Service Pack for Storefront ===
Contributors: opportus
Tags: woocommerce, storefront, starter-pack, ecommerce, e-commerce, store,
shop, aggregator, contact-form, sidebar, floating-menu, order-tracking,
sharer, slider, store-credit, facebook, newsletter, social-link
Requires at least: 4.4
Tested up to: 4.5.2
Stable tag: 0.1.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8R7Y9R2C79J8

Service Pack for Storefront Beta adds modulable basic functionalities to
your WooCommerce/Storefront eCommerce site.

== Description ==
Includes most of extra basic functionalities a e-commerce should have...

**Modules**:

- Aggregator
- Contact Form
- Dynamic Sidebars
- Floating Menu
- Order Tracking
- Sharer
- Slider
- Store Credits

**Widgets**:

- Facebook Page Plugin
- Newsletter Subscriber
- Social Links

= Aggregator =

Aggregates blog posts to the front page and product reviews to the product categories.
Hooked into the `storefront-before-footer` action.
Requires Storefront Theme.

= Contact Form =

Simple front end contact form that you can include to your contact page with the [spfs_contact_form] short code.
Note that a solution to join email attachment will be implemented soon.

= Dynamic Sidebars =

Display different sidebars accordingly to the current kind of page the user is on:
front page - blog - post - product category - single product - page...
Requires Storefront Theme.

= Floating Menu =

Makes the original Storefront main navigation menu floating when scrolling the page.
Requires Storefront Theme.

= Order Tracking =

Gives to yourself and your customers the possibility to track their orders.
Includes tracking info on "My Account" WooCommerce page.
Includes tracking info in the email that WooCommerce will send to your customer when you switch order status to "Completed".
Includes on the admin "order" page the link to track directly the order from shipper's site.
Requires WooCommerce plugin.

= Sharer =

Gives to your customers the possibility to share directly posts or products on their social accounts.
Actually, sharing is possible only on Facebook, Twitter and Google+.
Note that more possibilities will be implemented in futur releases.
Requires Storefront Theme.

= Slider =

Simple slider which will hook automatically before the content on the WooCommerce front page.
You can include it anywhere else you want with the [spfs_slider] short code.
You can edit easily slides in "Slider" admin menu.
Based on "Flex Slider" by WooThemes.

= Store Credit =

You have now the possibility to create store credits for your customers.
Send to your customer new credit by email..
Displays credits on "My Account" WooCommerce page.
Requires WooCommerce plugin.

= Facebook Page Plugin =

A widget that easily allow users to like your Facebook page and see who else liked your page.

= Newsletter Subscriber =

A widget in which your customers can enter their email address to be registered in your email list database.
Note that actually, it will do only this. A whole newsletter system module will be developped around it and will be included in futur releases.

= Social Links =

Pretty links to your social accounts pages.

= Coming Soon =

- Modulable admin interface for sending newsletter to registered customers using WooCommerce email templates.
- Modulable admin contact form using WooCommerce email templates.

= More details about the plugin and its guidelines =

When I first started to build websites with WordPress, I've spent too many hours with plugins, searching, testing and wondering...
Which will fulfill my needs the best ? Will it be fully compatible with my other plugins ? Huh, why so many functionalities and settings for such a basic thing ? Too bloated, delete it... Etc, etc...
Even when I found a plugin close of my needs, I was still often spending time adapting it to the rest of the system.
So instead to adapt other's plugins, I finally decided to write directly my owns, fitting specific needs and keeping in mind the following key points which are important:

**Compatibility**

When you need a plugin from developer X working hand to hand with a plugin from developer Y, it can get somehow tricky.
So as I see it, why not developing all together basic functionalities that a e-commerce should have anyway ? And what plugin a WordPress e-commerce should minimally have ?

- The awesome WooCommerce plugin and its official Storefront theme
- A good SEO plugin
- A good cache plugin
- And all little (but must have) functionalities that I propose with this plugin...

So all the functionalities that this plugin includes are fully compatible and designed to work hand to hand with WooCommerce and Storefront, the base of your e-sommerce site.
This all together will form a solid and homogeneous solution for your e-commerce on WordPress.

**Simplicity**

Even tough this plugin includes many functionalities, it contains few lines of code and it is very light... This is possible because each functionality (module) try to keep it as simple and straightforward as possible.
Instead of focusing on 100 different needs, proposing 100 settings, I personally prefer a minimalist approach for each functionalities, keeping in mind only what is important. Then, if you ever need, and you have a little knowledge of WordPress, you can easily extend/adapt it to your own needs via many actions and filters hooks that the plugin offers to you... Which should bring us to the next point...

**Flexibility**

All these functionalities are modulable. So if you have, for example, your favorite contact form plugin already installed on your WordPress and want to stick with it, you can deactivate the contact form module by just 2 clicks and never see it on your way anymore, so no conflict and no system ressource spent for nothing.
Though, the plugin can work "buglessly" without WooCommerce or Storefront, most modules depend completely on at least one of them and are automatically disabled in the case their dependency is missing. So WooCommerce and Storefront are highly recommended to exploit this plugin at its maximum.

= Who needs this plugin ? =

- Users who need the required functionalities for their e-commerce and want everything to work well together without getting dirty hands.
- Users who don't like to use many/bloating plugins on their WordPress install and appreciate the KISS philosophy.
- When you build a new e-commerce, it's also very convenient to install most of what you need in a few seconds...

= Notes =

This plugin is kinda new and is still in beta release but as far as I daily use and test it, it's bugless and safe to use for any site in production.
If you find a bug, please report it on the [GitHub](https://github.com/opportus/service-pack-for-storefront/issues "GitHub") repo.
If you'd like to see a specific functionality added to the plugin, please let me know on the [support forum](https://wordpress.org/support/plugin/service-pack-for-storefront "support furom").
Note also that this plugin or its author are in no way affiliated with WooThemes.

= Donation =

I share my work freely with you with real pleasure.
But as you probably guess, the time I use for developing this plugin is the time I take on my little business and the time I could spend with my family or friends instead.
So by advance, huge thanks to the understanding and kind people who will make a little [donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R8R7Y9R2C79J8 "Donate via PayPal"), with whom I'll can use more time developing faster the plugin for you :)
Thanks also to those who won't donate but simply appreciate my work !

== Screenshots ==

1. Service Pack's Settings - Modules activation.
2. Service Pack's Settings - Set here your social pages URLs and the store credit module options.
3. Service Pack's Settings - Order tracking module options.
4. Plugin's widgets and sidebars.
5. Store Credit Module - Send store credits to your customers by email or create them in the "Coupon" section.
6. Slider Module - Create/Edit slides.
7. Order Tracking Module - Choose the shipper and enter the tracking number in the metabox.
8. Aggregator Module - Display blog posts on the front page.
9. Aggregator Module - Display product reviews on the product categories.
10. Contact Form Module - Simple front-end contact form.
11. Order Tracking Module - Display order tracking information on the WooCommerce "My Account" page.
12. Sharer Module - Let your customers share products and posts on their social accounts.
13. Slider Module - Simple responsive slider based on the WooTheme's "FlexSlider".

== Changelog ==
**Version 0.1.2 Beta**

Enhancements:

- Add admin notices on plugin's activation

**Version 0.1.1 Beta**

Enhancements:

- Internationalization

**Version 0.1 Beta**

- Initial release
