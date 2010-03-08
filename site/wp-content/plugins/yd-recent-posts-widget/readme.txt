=== YD Recent Posts Widget ===
Contributors: ydubois
Donate link: http://www.yann.com/
Tags: widget, recent posts, posts, plugin, sidebar, thumbnail, images, cache, tags, automatic, custom, Post, admin, image, previous posts, template function, template, page, administration, timthumb
Requires at least: 2.0.0
Tested up to: 2.9.1
Stable tag: trunk

Highly customizable sidebar widget: displays latest or previous posts with automatic thumbnail images.
Skips posts already on the home page.


== Description ==

= Recent posts with thumbnails! =

This Wordpress plugin installs a **new sidebar widget** that can display the **latest posts** with **automatic thumbnail images**.
It also creates **new PHP functions** that can be included in any template to **display a posts list with thumbnails**.
It uses pre-resized thumbnail images already generated in WP2.0+, WP2.5+ and WP2.6+.
It also works perfectly with "old" versions of Wordpress that did not support the automatic multiple-format image resizing.
You can choose to list all recent posts, or **only list recent posts marked with a specific tag**.
You can display a **different selection on the home page** and on other pages.
Posts already listed on the home page are automatically "skipped" when the widget is displayed on the home page.

If you don't like the widget or don't use sidebars, you can also **include the list in the content of any page or post** of your blog, 
by simply adding the special `<!-- YDRPW -->` tag, or **include it in a template** with the `<?php display_yd_recent_posts_here() ?>` function.
The list design is **highly customizable** allowing different settings when displayed as a widget on the home page and other blog pages, and when used inside templates. 

The plugin uses **cache** to avoid multiple database query.
It has its own widget control pannel and admin options page.
It is **fully internationalized**.
You can use the provided additional stylesheet, or customize your own.
Base package includes .pot file for translation of the interface, and English, French and Russian versions.
The plugin can be (and is) used to display posts in any language and charset, including Chinese.

= Translation credits =

Thanks to [FatCow](http://www.fatcow.com/ "FatCow") for the Russian translation file.

= Previous posts with thumbnails now also available =

Version 0.7 adds a new template function which displays a list of the previous posts with thumbnails:

Try it on your homepage (`index.php`) template!

Syntax: `<?php display_yd_previous_posts_here() ?>`
Option: you can specify number of posts to shows like this: `<?php display_yd_previous_posts_here( 5 ) ?>`

= Timthumb-compatible version =

Unfortunately, I have no time to work (for free) on this plugin right now.
This is why I cannot release the long-awaited  fully-tested "timthumb enabled" version of the plugin.
For those that are interested in this feature, please checkout (unsupported) [branch version 0.9.0 in the Wordpress SVN](http://plugins.svn.wordpress.org/yd-recent-posts-widget/branches/0.9.0/).
I am using this version of the plugin on my own sites. It works well for me.

= Active support =

Drop me a line on my [YD Recent Posts Widget plugin support site](http://www.yann.com/wp-plugins/yd-recent-posts-widget "Yann Dubois' Recent Post Widget for Wordpress") to report bugs, ask for specific feature or improvement, or just tell me how you're using the plugin.
It's still in an active development stage, with new features coming out on a regular basis.

= Description en Français : =

Ce plug-in Wordpress installe un nouveau widget dans votre barre latérale qui peut afficher les billets récents assortis automatiquement d'une image vignette.
Il fonctionne parfaitement avec les anciennes versions de Wordpress n'intégrant pas la génération automatique d'images multi-formats.
Vous pouvez choisir de lister tous les billets récents, ou de seulement lister ceux qui sont marqués d'un tag précis.
Vous pouvez afficher une sélection distincte sur la page d'accueil et sur les autres pages.
Les billets qui sont déjà affichés sur la page d'accueil n'apparaissent pas dans la liste quand le widget s'affiche en page d'accueil.
Si vous n'aimez pas le principe du widget ou n'utilisez pas de barres latérales, vous pouvez inclure la liste des billets récents n'impore où dans le contenu des pages et billets de votre blog,
simplement en insérant un "tag" spécial.
Le widget est entièrement paramétrable, autorisant des réglages différents entre la page d'accueil et les autres pages du blog.
Il utilise un système de cache pour éviter les requêtes de base de données redondantes.
Il utilise les images thumbnail pré-générées par WP2.0+ et WP2.5+.
Il a son propre panneau de contrôle et sa page d'option dans l'administration.
Il est entièrement internationalisé.
Vous pouvez au choix utiliser la feuille de style fournie, ou personnaliser l'apparence de la liste avec vos propres styles 
La distribution standard inclut le fichier de traduction .pot et les versions française et anglaise.
Le plugin peut fonctionner avec n'importe quelle langue ou jeu de caractères y compris le chinois.
Pour toute aide ou information en français, laissez-moi un commentaire sur le [site de support du plugin YD Recent Posts Widget](http://www.yann.com/wp-plugins/yd-recent-posts-widget "Yann Dubois' Recent Post Widget for Wordpress").

== Installation ==

1. Unzip yd-recent-posts-widget.zip
1. Upload the `yd-recent-posts-widget` directory and all its contents into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the widget admin page to add the widget to one of your sidebars and configure it
1. Use the option 'YDRecentPosts' admin page to clear the cache when you make changes.
1. If you want to include the list in your page content, use the `<!-- YDRPW -->` tag.
1. If you want to include it in your template, use the `<?php display_yd_recent_posts_here() ?>` function.
1. Use the `<?php display_yd_previous_posts_here() ?>` function to display a list of previous posts.
For specific installations, some more information might be found on the [YD Recent Posts Widget plugin support page](http://www.yann.com/wp-plugins/yd-recent-posts-widget "Yann Dubois' Recent Post Widget for Wordpress")


== Frequently Asked Questions ==

= Where should I ask questions? =

http://www.yann.com/wp-plugins/yd-recent-posts-widget

Use comments.

I will answer only on that page so that all users can benefit from the answer. 
So please come back to see the answer or subscribe to that page's post comments.

= Puis-je poser des questions et avoir des docs en français ? =

Oui, l'auteur est français.
("but alors... you are French?")

= I made some changes but they do not show up on the site... =

Remember to use the "Clear cache" button of the YDRecentPosts options page of the admin menu
if you want changes to appear right away on your blog.
Oterwise you will have to wait until content is added to the blog for the cache to expire 
(ie. when you write a new post or page - new comments don't make the cache expire).

= How to display this in the template without using a widget? =

Insert this code into your template:

`<?php display_yd_recent_posts_here() ?>`

= If I don’t want to use the widget, how can I display it in php? =

Same answer as above.

= Can I include the recent post list in my blog content? =

Yes you can include the list in the content of any page or post by using tis special tag:

`<!-- YDRPW -->`

= How do a display a list of previous posts on my homepage? =

Insert this code into your `index.php` homepage template, after the loop:

`<?php display_yd_previous_posts_here() ?>`

= How do I specify the number of previous posts to show in the "previous posts" list? =

Add the optional 'number of posts' parameter when calling the function, for example:

`<?php display_yd_previous_posts_here() ?>` will display the previous 5 posts.

= How do i change the text formatting? =

Try to load the specially provided CSS stylesheet by checking the "load CSS" checkbox in the widget control pannel.
You can either customise this stylesheet which is inside the /css sub-folder of the plugin folder, or add styles to your main stylesheets
for elements of the `<div class="yd_rp_widget">` tag.

= What if I want / don't want to display the date? =

Just check or uncheck the "date" checkbox in the widget control pannel.
You can customize the date display style by using the usual PHP syntax.

= How do I restore the default settings? =

Click on the "restore default settings" button in the YDRecentPosts page of the Options admin menu.


== Screenshots ==

1. An example of the sidebar widget in action
2. Another example of the recent post list rendering
3. The widget control pannel in Wordpress 2.3
4. The widget options admin page in Wordpress 2.7


== Widget control pannel ==

The widget has its own control pannel for setting-up its look and feel. You can administer it from the widgets admin page.
Remember to clear the cache when you make changes, if you want to see them right away (see hereunder).


== Widget options page ==

Use the widget's own option page to clear the cache and reset default settings.
Otherwise, the cache expires only when content is added to the blog or widger control panel options are changed.


== Revisions ==

0.2. The cache did not always expire when new content was added -> Fixed.

0.2. I18n + .PO file + French .MO file.

0.3. Made date display and format optional.

0.4. Now Initializes / resets default options properly

0.4. Now gets default date format from WP options

0.4. Bugfix: in WP2.7 the widget was called when in admin mode, giving strange results

0.4. Created/Added a default thumbnail image + set it as default during init

0.5. Added function and special tag to display the list outside of a widget

0.5. Added feature to skip posts already displayed on home page

0.6. No warnings in debug mode (hopefully?)

0.6. Supports pre-existing WordPress 2.0+ and 2.5+ thumbnails

0.7. Now Supports an optional specific cache and settings for usage within templates + undocumented optional parameters

0.7. Added the new `display_yd_previous_posts_here()` function!

0.7. Fixed WP_query redefinition / `is_home()` status loss issue

0.7. Fixed the private post display issue

0.8. New option to keep HTML formatting inside the post excerpts

0.8. New options to get rid of [...] and/or {...} special tags

0.8. New (default) option to display the list as a set of &lt;ul&gt;, &lt;li&gt; tags

0.8. New default stylesheet

0.8. Bugfixes


== To Do ==

Actually generate the thumbnail image files with the right format if necessary (using GD when available).
Since 0.6 this is not really an issue because we're already using small resized images in WP2.0+.

Clear other pages template caches? (currently only clears homepage when using the clear cache button)


== Did you like it? ==

Drop me a line on http://www.yann.com/wp-plugins/yd-recent-posts-widget

And... *please* rate this plugin --&gt;