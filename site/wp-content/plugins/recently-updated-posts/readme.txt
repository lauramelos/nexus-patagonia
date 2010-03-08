=== Recently Updated Posts ===
Contributors: f00f
Tags: posts, updated, modified, widget
Tested up to: 2.5.1
Stable tag: 0.4

Shows the most recently modified posts.

== Description ==

Recently Updated Posts finds the posts (and optionally pages) which were modified most recently. You can show them as HTML list or get a PHP array to do treat them how you like it.

== Installation ==

Unzip all files to a folder in your wp-content/plugins directory, go to the plugins page in your admin panel and activate it.

To use the widget, go to presentations page and simply drag it onto the sidebar.

If you do not want to use the widget, use one of the functions hh\_recently\_updated\_posts() or hh\_rup\_get() which will print the posts as HTML UL or return a PHP array respectively. Also have a look at the 'parameters' section.

== Parameters ==

You may pass a number of parameters when calling the function to configure some of the options.

Example: hh\_recently\_updated\_posts($num=5, $skip=0, $skipUnmodifiedPosts=true, $includePages=false, $hideProtectedPosts=true);
Example: hh\_rup\_get($num=5, $skip=0, $skipUnmodifiedPosts=true, $includePages=false, $hideProtectedPosts=true);

The parameters:

$num - sets the number of recent posts to display

$skip - allows skipping of a number of posts before showing the number of posts specified with the $num parameter

$skipUnmodifiedPosts - hide newly published (and yet unmodified) posts

$includePages - allows recent pages to be show with recent posts

$hideProtectedPosts - whether or not to display password protected posts
