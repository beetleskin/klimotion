=== Inline Attachments ===

Contributors: BASICS09, nonverbla
Plugin Name: Inline Attachments
Plugin URI: http://www.nonverbla.de/blog/wordpress-plugin-inline-attachments/
Tags: attachments, inline, thickbox, meta box, adjust, options, admin, bulk, delete, image, checkbox, ajax, media, upload
Author URI: http://www.nonverbla.de/
Author: BASICS09, nonverbla
Donate link: http://www.nonverbla.de/blog/wordpress-plugin-inline-attachments/
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.0.3
Version: 1.0.2

Put the focus in the admin area of Wordpress on media files. Functionally and visually.

== Description ==

= Short Version (aka Features Overview) =

* Adds the attachments screen that's usually reached trough the thickbox popup directly to a meta box inside the post edit screen
* Provides an interface to hide unnecessary stuff on the media screen (e.g. "Insert Into Post" Button, Size, Alignment, …)
* Adds a checkbox to every attachment and a button to delete all checked Attachments at once
* Posts, Pages and Custom Post Types supported (selectable)
* AJAX-Autosaving of all changes you make to media metadata (e.g. menu order, title, description, …)
* If uploading a bulk of files, maintains the order they had when you selected them on your machine
* Button to invert the current order of media files (first becomes last, second becomes second last, …)
* Designed to seamlessly blend in with the native Wordpress admin style

= Long Version =

I very often use WordPress as a CMS for clients like artists or photographers, who want to present a big amount of media files on their websites. Most of the time, the media files actually are the very center of these websites.

To reflect this focus on media files in the admin area, I developed the plugin "Inline Attachments". It adds a Meta Box to the post edit screen, that holds the media-uploads panel inside an iframe. If no Media Files are attached to a post yet, it shows the upload tab, otherwise it shows the list of attached media files.

In the plugin settings, you can select all post types, where the Inline Attachments Meta Box should be displayed, and you can specify a name for each (e.g. ”Photographs“ or ”Images“). Default is ”Media Files“.

Another thing with mainly media-driven websites is, that you normally don't write a text post and put some images into the text flow. Because of this, you wouldn't need the options for Image Size, Alignment, Link URL and so on in the attachment screen. With Inline Attachments, you can easily select which options should be visible to the admin for editing media files.

Also, I always thought, deleting a bunch of media files in the attachments screen was quite a hassle. You had to expand every item you wanted to delete, press delete and even confirm that, phew! That's why I included the functionality of my plugin [Attachments Bulk Delete](http://www.nonverbla.de/blog/wordpress-plugin-inline-attachments/). I like having everything in the same place.

If you have comments or questions, please go to the official plugin page: [www.nonverbla.de/blog/wordpress-plugin-inline-attachments](http://www.nonverbla.de/blog/wordpress-plugin-inline-attachments/)

= Tested on =

* (OSX) Safari :)
* (OSX) Firefox :)
* (OSX) Chrome :)
* (OSX) Opera :)
* (WIN) Firefox :)
* (WIN) Chrome :)
* (WIN) IE 9 :)
* (WIN) IE 8 :)
* (WIN) IE 7 :/

= Translations (included with the plugin) =

* French (by Olivier)
* German (by me)

= Please Note =

* Due to it's very different approach, *Inline Attachments* **does not** support the functionality of the plugin *Attachments*.
* I found no possible way to catch the "Insert into Post" javascript-action. But I don't like this button, anyway, so just don't activate it ;)

= Please rate the plugin and post comments, if anything doesn't work as expected. =

== Installation ==

1. Install easily with the WordPress plugin control panel or manually download the plugin and upload the extracted folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the plugin settings page called “Inline Attachments” and select the post types you want to have it activated for. Also select all the fields you want to enable for media editing.
3. Go to the post edit sceen, and here it is, your inline attachments meta box.

== Screenshots ==

1. If activated, Inline Attachments displays all attachments of a post inside the post edit screen
2. The Inline Attachments Options Screen

== Changelog ==

= 1.0.3 =
* Bugfix with animating the height of the iframe
= 1.0.2 =
* Bugfix with header information
= 1.0.1 =
* Bugfix for compatibility with wp 3.3.1
= 1.0 =
* Release.
= 0.9.9 =
* Fixed a bug when redirecting to upload panel after deleting all attachments at once.
= 0.9.8 =
* Improvements on the automatic height adjustment of the iframe
* BugFix for auto-ajax-saving on blur of textareas and text inputs
= 0.9.7 =
* Don't show the bulk delete checkbox on media.php, just on media-upload.php
* Also disable .image_url on media.php, if File URL is disabled.
= 0.9.6 =
* Maintains the order of uploaded files now, and doesn't reverse it like it used to do
* Added functionality to invert order of attachments with one click
* Added option to also hide the “show / hide” buttons in the media screen
= 0.9.5 =
* Added an option to hide all Thickbox-Buttons
* WP 3.3 compatibility check
= 0.9.4 =
* Added localization and German translation
* Added the functionality of my plugin [Attachments Bulk Delete](http://www.nonverbla.de/blog/wordpress-plugin-inline-attachments/). I like having everything in the same place ;)
= 0.9.3 =
* Added ajax-saving of attachments order and input fields on change
= 0.9.2 =
* Added automatic saving of changed attachments properties on post save / update
* Optimized the styling while dragging an attachment to change sort order.
= 0.9.1 =
* Added plugin initialization also for post-new.php, not just post.php, so that it also works on newly created posts



