=== Kau-Boy's AutoCompleter ===
Contributors: Kau-Boy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6104701
Tags: ajax, search, prototype, scriptaculous, jquery
Requires at least: 2.7
Stable tag: 3.1.3


Integrates a google suggest like search to your blog. It Prototype (Scriptaculous) or the jQuery to search for text within the title and/or the content.

== Description ==

**Kau-Boy's AutoCompleter is deprecated and will be removed from the plugin directory on June 19, 2023, 14 years after its first release. Thanks to everyone who used the plugin, gave constructive feedback, rated it and send me messages on how it helped them with their sites. This was [my very first WordPress plugin, and it started my journey into the WordPress community](https://kau-boys.de/19/allgemein/mein-erster-blog). If you see this message, you are one of few, who still have this plugin installed. But now feel free to remove it from your site. If you still want an auto-completion/suggestion, try out [WP Search Suggest](https://wordpress.org/plugins/wp-search-suggest/), which is still actively maintained. You can find my other plugins [on my WordPress profile page](https://profiles.wordpress.org/kau-boy/#content-plugins).**

As you can choose between Prototype and jQuery, you can just take the framework you prefer or use the most. You can also set your own sytles without losing these settings in case the plugin is updated.
The results are shown right under the search field. The search string is highlighted in the title and/or the content. You can also cutomize the ID of the search field and the number of choices.

A list of all of my plugins can be found on the [WordPress Plugin page](http://kau-boys.de/wordpress-plugins?lang=en "WordPress Plugins") on my blog [kau-boys.de](http://kau-boys.de).

== Screenshots ==

1. Screenshot in new WordPress blog
1. Screenshot of the settings page (also available with German language).

== Installation ==

= Installation through WordPress admin pages: = 

1. Go to the admin page `Plugins -> Add New` 
2. Search for `kau-boy`
3. Choose the action `install`
4. Click on `Install now`
5. Activate the plugin after install has finished (with the link or trough the plugin page)
6. You might have to edit the settings, especially if your search field has an ID other than `s`

= Installation using WordPress admin pages: =

1. Download the plugin zip file
2. Go to the admin page `Plugins -> Add New`
3. Choose the `Upload` link under the `Install Plugins` headline
4. Browse for the zip file and click `Install Now`
5. Activate the plugin after install has finished (with the link or trough the plugin page)
6. You might have to edit the settings, especially if your search field has an ID other than `s`

= Installation using ftp: =

1. Unzip und upload the files to your `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. You might have to edit the settings, especially if your search field has an ID other than `s`

== Change Log ==

* **3.1.2** Revert previous change as it does not fix the issue but might cause it 
* **3.1.1** Replacing appendTo() with prependTo() in jquery.autocompleter to fixing overlay issues
* **3.1** Hide warnings when WP_DEBUG is not active, insert DIV for results on top of body
* **3.0.4** Fixing typo in jQuery URL
* **3.0.3** Fixing typo in jQuery selector
* **3.0.2** Retag as 3.0.2 due to a deploment error on respository 
* **3.0.1** Quick bugfix for Prototype CSS and JavaScript
* **3.0** Use AJAX the way it's described here: http://codex.wordpress.org/AJAX_in_Plugins
* **2.5** Enable plugin for WordPress blogs with the wp-config.php one folder above the root folder.
* **2.4** Fixing html entities in the filter
* **2.3** Fixing the JavaScript file for IE8 in combination with prototype
* **2.2** Deactivate search for admin menu search fields and make it working with relevanssi plugin
* **2.1** Adding new version of jQuery Autocomplete
* **2.0.1** Removing "short open tags" which causes error on blog that don't have "short_open_tag" set to "On"
* **2.0** Enabling autocompletion for multiple inputs on a page, improving truncating of the post content 
* **1.9** Fixing issues using the plugin with PHP4, it now works with qTranslate in "Query Mode" (?lang=en) 
* **1.8** Adding settings for title and content length in results
* **1.7** Replacing jQuery suggest with jQuery autocomplete as the suggest plugin destroys HTML markup 
* **1.6.1** Using the id attribute as default rather than the name attribute
* **1.6** Adding the option to use the value of the name attribute to select the searchfield
* **1.5.2** Adding encoding to `html_entity_decode()` to avoid stripping of XML entities
* **1.5.1** Fixing bug in AJAX repsonse where the resultfields setting hasn't been loaded
* **1.5** Adding setting for choosing searchfields and resultfields 
* **1.4.1** Updating translation file 
* **1.4** Combine styles for jQuery and script.aculo.us, adding encoding setting for multibyte functions
* **1.3.5** Fixing problem with the `html_entity_decode()` converting
* **1.3** Removing comment to avoid php header warnings in autocompleter.js.php
* **1.2** Using `html_entity_decode()` to avoid stripping entities
* **1.1** Stripping wordpress shortcodes to avoid emtpy or wrong results
* **1.0** Enabling plugin to use jQuery OR script.aculo.us, translating settings page to German
* **0.4** Adding form number of choices and custom css to settings
* **0.3** Adding settings page for searchfield ID
* **0.2** First stable release