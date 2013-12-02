=== Twitter Like Box - Like facebook box but for twitter ===
Contributors: timersys
Donate link: http://www.timersys.com/
Tags: twitter, twitter followers, twitter like box, twitter followers box, twitter fans
Requires at least: 3
Tested up to: 3.6
Stable tag: 1.3.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display your Twitter followers anywhere in the site or use the widget to display it on the sidebar. Like Facebook's Like Box show your followers and a button to follow you. Also you can display people YOU follow instead of followers

== Description ==

Display your Twitter followers anywhere in the site or use the widget to display it on the sidebar.Like Faebook's Like Box show your followers and a button to follow you. Also you can display people YOU follow instead of followers by simple changing settings on the widget. Colors are also editable in the admin settings page.

To call the Twitter like box anyplace on your theme use:

`<?php twitter_like_box($username='chifliiiii') ?>`

Also you can change the total users to display and show users you follow by applying false to show followers

`<?php twitter_like_box($username='chifliiiii', $total=25, $show_followers = 'false') ?>`

Also you can call the widget in any page by using shortcodes:

[TLB username="chifliiiii" total="33" width="50%"]

This plugin dont use cron jobs or anything that will overload your server like other plugins. It uses the new functions of wordpress (transient API) to cache results for 6 hours instead of query Twitter every time that a page is refreshed or visited on your site

CHECK FOR INSTRUCTIONS INSIDE THE PLGUIN

= Plugin's Official Site =

Twitter Like Box ([http://www.timersys.com/plugins-wordpress/twitter-like-box/](http://www.timersys.com/plugins-wordpress/twitter-like-box/))

= Increase your twitter and Facebook followers  =

Increase your Twitter followers with Social PopUP Plugin ([http://wordpress.org/extend/plugins/social-popup/](http://wordpress.org/extend/plugins/social-popup/))

= Github = 

Fork me in https://github.com/timersys/twitter-like-box

== Installation ==

1. Unzip and Upload the directory 'twitter-like-box' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to Widgets; You'll see a new widget called 'Twitter Like Box'. Drag and Drop it in the sidebar.

or

3. You can use the function to include the Twitter like box in your theme like these:

To call the Twitter like box anyplace on your theme use:

`<?php twitter_like_box($username='chifliiiii') ?>`

Also you can change the total users to display and show users you follow by applying false to show followers

`<?php twitter_like_box($username='chifliiiii', $total=25, $show_followers = 'false') ?>`


== Frequently Asked Questions ==

= Why if i include the like box on my theme it gets all over the screen ? =

This widget is intended to work on any sidebar size, so when you call it directly you need to place it in a div container with a fixed width


== Changelog ==

= 1.3.5.2 =

* Updated WP_Plugin_Base class for compatibility with Wordpress Social Invitations Plugin
* Added title attribute to links

= 1.3.5.1 =
* Updated WP_Plugin_Base class for compatibility with Wordpress Social Invitations Plugin
* Fixed php shorthand used on one of the files

= 1.3.5 =
* Updated WP_Plugin_Base class for compatibility with Wordpress Social Invitations Plugin


= 1.3.4 =
* Added alt tag to images
* Fixed translation link
* Fixed problem with error box showing all the time


= 1.3.3 =
* Added more error messages in case oAuth is not configured

= 1.3.2 =
* Fixed Css issue
* Added better errors handling

= 1.3 = 
* Redisigned plugin structure and backend
* Now allow to show more than 100 users on the box
* Added OAuth authorization 
* Changed to v1.1 of Twitter API

= 1.2.2.3 =
* Tiny fix to mention here

= 1.2.2.2 =
* Changed functions to force use of arrays to avois some problems with some accounts
* Added a bit more of error handling in case twitter usernames are wrong or twitter is down
* Corrected tagged version

= 1.2 =
* Added nofollow attribute to links of followers
* Added option to show just images, instead of links to followers
* Closed a missing tag .Thanks to Soonforward for pointing that out

= 1.1 =
* Added link in widget to change settings and style
* Fixed some typos in readme.txt

= 1.0 =
* First version


== Screenshots ==

1. Twitter Like Box
2. Twitter Like Box widget settings