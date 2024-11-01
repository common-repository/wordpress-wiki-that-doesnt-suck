=== WordPress Wiki That Doesn't Suck ===
Contributors: jazzs3quence
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AWM2TG3D4HYQ6
Tags: wiki, custom post types, support
Requires at least: 3.1
Tested up to: 3.5
Stable tag: 0.9.2

A WordPress Wiki that works.

== Description ==

The main purpose of this plugin is to provide a page to display documentation. Not a Wiki *per se*, as it's not intented to be collaborative (although it can be), rather, it just uses Custom Post Types to separate documentation articles from the rest of your content. This plugin came as a result of my undying hatred of MediaWiki.  I know BBCode and HTML and PHP and CSS and still the wiki markup just baffles me.  I can never remember how to do the *simplest* things, like create a freaking link.  Really?  All I want to do is throw a `<a href="...">` in there.  If you're looking at this plugin, you know what I'm talking about.  When this plugin was written the WordPress plugins available for what I want to do either *aren't* what I want to do, or don't work with the latest version of WordPress.  Hence this plugin.

*WordPress Wiki That Doesn't Suck* uses custom post types.  And that's pretty much it.  It creates a new custom post type (`wpwtds_article`) that can be accessed from the *Wiki != suck* menu it adds to your sidebar (!= is "not equal to" in coder jargon).  Wiki articles are posted with the `wiki` slug, so your URLs will look like `http://mydomain.com/wiki/my-cool-article`.

To display your wiki articles on a page, you can either use the included template files (in the `/templates` directory), or you can use the `[wpwtds]` shortcode that's been added in 0.9.

You can see WPWTDS in action at http://museumthemes.com/wiki/ and http://eventespresso.com/support/documentation/

== Installation ==

1. Unpack the zip file and upload to the `/wp-content/plugins/` directory or use the Add New Plugin option in WordPress to install.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Start writing articles!
4. Use the `[wpwtds]` shortcode or the included template file examples to display a list of wiki articles on your page.

== Frequently Asked Questions ==

= Can I change the default slug? =

Not yet.

= Why isn't *x* feature included? =

Basically, I had a specific need, custom post types seemed like the best answer.  Plus, once it was done, I couldn't think of anything else it really needed.

= This isn't a *wiki* really, is it?  I mean, there's no real way to contribute like a real Wiki... =

This wasn't a concern for me, since I just wanted someplace I could post support docs that was public.  That said, generic WordPress user roles will still work, so if you're an *Author* you'll be able to post new wiki articles, same as anything else.

= I've installed it, now what? =

After you install the plugin, wiki articles will use your theme's default `single.php` template file.  You may want to actually *use* your wiki, as in have an actual wiki page, and for that, you'll either need to add a custom template to your theme or use the `[wpwtds]` shortcode. To use the shortcode, all you need to do is add `[wpwtds]` on a post or a page and it will display a list of all your wiki articles.

A default template that you can use is provided if you want to customize the layout. More than likely you'll need to modify it slightly to fit your specific theme.

The best reference I can give you for working with custom post types (if you wanted to make your own wiki main page, for instance) is the <a href="http://codex.wordpress.org/Custom_Post_Types">Custom Post Types</a> article in the <a href="http://codex.wordpress.org">Codex</a>.  The only thing you need to know is that the post types are identified as `wpwtds_article`s.

== Screenshots ==

1. WordPress Wiki That Doesn't Suck articles page
2. Edit/Add New article page
3. Custom HTML editor in WordPress 3.3

== Changelog ==

**Version 0.9.2**

* fixes undefined index notice on edit wiki pages

**Version 0.9.1**

* adds post thumbnail support

**Version 0.9**

* added shortcode to list all wiki articles without having to code anything. Template examples are still in `/templates/` directory, but now you can use `[wpwtds]` to display all your docs in a generic format.

**Version 0.8.1**

* updates/optimizes how the post_meta is saved
* removed changelog (since it's in this readme, it's unnecessary)

**Version 0.8**

* added column displaying Section taxonomy term(s)
* added WordPress 3.3 support for wp_editor for the custom HTML section

**Version 0.7**

* added inline documentation
* added custom HTML meta area that can be used in wiki template files
* added meta area in sample `single-wpwtds_article.php`

**Version 0.6.3**

* removed blank spaces at the end of file (kills 'headers already sent' error)

**Version 0.6.2**

* updated stable tag and tested up to
* updated requirements (since custom post types didn't exist in 2.8)
* fixed menu image and path to menu image
* added post type header image
* added wpwtds_ prefix to create_post_type function
* added wpwtds_section taxonomy to allow wiki posts to be split into different categories
* customized columns
* added example template files to the /templates directory
* added screenshots

**Version 0.6.1**

* updated `readme.txt`

**Version 0.6**

* added `with_front` qualifier to the `rewrite` option to use it's own permalink structure.

**Version 0.5**

* first public release

== Upgrade Notice ==

Nothing to see here.