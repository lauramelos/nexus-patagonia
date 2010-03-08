=== Plugin Name ===
Contributors: taylorde,ericmann
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=385223
Tags: recent,posts,post,pages,page,excerpt,preview
Requires at least: 2.5
Tested up to: 2.7
Stable tag: 1.2

Simple widget that displays the recent posts with a short content preview. Control the number returned and length of the content preview

== Description ==

This is a very simple plugin that essentially mimics the effect of the "recent posts" widget included with Wordpress, but with the addition of a content preview. The plugin will, by default, display the name of the post (with a link), the date it was published, and a short bit of text from the post.

There is a widget admin panel that allows changing of the title, the number of posts to display, and how many characters to use for the content preview.

Updates:

* 1.2 - Adds two options: "truncate excerpts" allows you to shorten the content that is hand-written in the excerpt field of a new post. "hard truncate" will shorten the preview content to exactly the number of characters you specify.
* 1.1 - Fixed xhtml validation errors that this plugin was causing.



Limitations:

* The plugin strips out any HTML tags from the post before displaying it, so if you are looking to display images -- try a different plugin. Perhaps in future releases this will get more customized.

* At this time the plugin does NOT strip out shortcode (e.g. [shortcode]. Shortcode will not be interpreted, but it will be displayed.

== Installation ==

Same ol'

1. Upload the 'tddrecentposts' folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the widget to your sidebar from Design->Widgets

== Screenshots ==
1. Sample, styled, output of the plugin. You will need to modify your CSS to get the specific look you need
2. Widget admin options.

== Frequently Asked Questions ==

= It looks like garbage in my theme, What's up? =
You need to do some CSS Styling. The plugin uses the ul, li, dl, dt, and dd HTML tags.

The entries are based upon an unordered list with class "tddrecentposts"

In the next version I will probably include more classes to work off of, but right now you can still do some heavy styling with CSS by calling the classname and then the specific tag you want to work off of. For example:

.tddrecentposts dt {
border-bottom: 1px solid #000;
}

= Can I set it to display pages as well as posts? =

Yes, although it is not in the admin menu.
1. Open output.php.
1. Find the line that has the variable `$include_pages` (on or around line 18)
1. Set the variable to say: `$include_pages = true;`

= What about password protected pages? =

Same as above, but set the variable `$hide_pass_post` to false.

= I have a post with a bunch of images before my text and it doesn't display a content preview. What's going on? =

In order to limit the database query that is made, the plugin only retreives 250 characters more than what it needs. If you have more than 250 characters of HTML markup before the content of your post, it will start cutting into what can be displayed. You can increase the default of 250 characters:
1. Open output.php.
1. Find the line that has teh variable `$sqllimit` (on our around line 3)
1. Change `250` to whatever you need (or delete in the following line: `$sqllimit = 250 + $lengthof;`)

= Why can't I display images (or other HTML stuff) in my recent posts preview? =

Right now the plugin strips out any HTML occuring in the post and just outputs text. Perhaps this will be in the next release if the demand is strong enough.

= ________ screwed up OR _________ isn't working... =

Sorry? Plugin dev isn't my full time job. Shoot me an email or post on my website and I'll try to fix it.