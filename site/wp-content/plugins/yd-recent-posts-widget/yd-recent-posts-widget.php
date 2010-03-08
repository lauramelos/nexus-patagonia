<?php
/**
 * @package YD_Recent-Posts-Widget
 * @author Yann Dubois
 * @version 0.8.4
 */

/*
 Plugin Name: YD Recent Posts
 Plugin URI: http://www.yann.com/wp-plugins/yd-recent-posts-widget
 Description: Installs a new sidebar widget that can display the recent posts with automatic thumbnail images. Highly customizable allowing different settings on the home page. Uses cache to avoid multiple database queries. You can choose to list all recent posts, or only list recent posts marked with a specific tag. You can display a different selection on the home page and on other pages. You can also insert a list of recent posts or previous posts with thumbnails in your templates and not use the widget.
 Author: Yann Dubois
 Version: 0.8.4
 Author URI: http://www.yann.com/
 */

/**
 * @copyright 2009-2010  Yann Dubois  ( email : yann _at_ abc.fr )
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 Revision 0.2:
 - In 0.1 the cache sometimes wouldn't expire even when new content was added -> fixed in 0.2
 - I18n + POT + French .mo files
 Revision 0.3:
 - Date display and format are now optional (lines 142-147 of v.0.2)
 Revision 0.4:
 - Initialize / reset default options properly
 - Get default date format from WP options
 - Bugfix: in WP2.7 the widget was called when in admin mode, giving strange results
 - Created/Added a default thumbnail image + set it as default during init
 Revision 0.5:
 - Added function and special tag to display te list outside of a widget
 - Added feature to skip posts already displayed on home page
 Revision 0.6:
 - No warnings in debug mode (hopefully?)
 - Supports pre-existing WordPress 2.0+ and 2.6+ thumbnails
 Revision 0.7:
 - Supports an optional specific cache and settings for usage within templates
 - Added the new "display_yd_previous_posts_here()" function!
 - Fixed WP_query redefinition / is_home() status loss issue
 - Fixed the private post display issue
 Revision 0.8:
 - New option to keep HTML formatting inside the post excerpts
 - New options to get rid of [...] and/or {...} special tags
 - New (default) option to display the list as a set of ul, li tags
 - Previous posts widget (beta)
 Revision 0.8.1:
 - Bugfix foreach() line 448
 - Bugfix warning line 198
 Revision 0.8.2:
 - Bugfix </div> in admin page
 - YD WP functions become generic ( if( !function_exists() ) )
 Revision 0.8.3:
 - Better default stylesheet
 - Bugfix: list sometimes included pages
  Revision 0.8.4:
 - Russian version (credit: FatCow)
 - Updated doc (compatibility)
 */
/**
 *	TODO:
 *  - Fix stylesheet for NC (color of links, bullets with FFx)
 *	- Native Timthumb support
 *  - Optional disable of home-page skip
 *  - Randomization?
 *  - Support selection by category
 *  - Option to only list posts of same category as current page
 *  - Exclude images by directory
 *  - Support multiple instances of the widget
 *	- Clear other pages template caches? (currently only clears homepage) -> yd_clear_all_cache( $widget ) function
 *  - Be able to choose any thumnail image size
 *	- Generate CSS file dynamically at plugin install, depending on blog existing thumbnail size setting (?)
 *	- Plugin info RSS in options page
 */

/** Install or reset plugin defaults **/
function yd_rp_plugin_reset( $force ) {
	/** Init values **/
	$yd_rp_plugin_version	= "0.8.4";
	$default_image_width	= 60;
	$default_image_height	= 60;
	$default_image_style	= 'padding-right:5px;padding-bottom:5px;float:left;';
	$default_date_format	= __('F j', 'yd-recent-posts-widget');
	$default_bottomlink		= 'http://www.yann.com/wp-plugins/yd-recent-posts-widget';
	$default_bottomtext		= '<br/><small>[&rarr;YD Recent Posts Widget]</small>';
	$default_thumbnail_img	= 'http://www.yann.com/yd-recent-posts-widget-v090-logo.gif';
	$newoption				= 'widget_yd_rp';
	$newvalue				= '';
	if( $df = get_option( 'date_format' ) ) $default_date_format = $df;
	/** TODO **/
	//$default_image_width = get_option( 'thumbnail_size_w' ) ? get_option( 'thumbnail_size_w' ) : $default_image_width;
	//$default_image_height = get_option( 'thumbnail_size_h' ) ? get_option( 'thumbnail_size_h' ) : $default_image_height;
	// ...this would need to generate the CSS file dynamically at plugin init
	$default_image_style =	'width:' . $default_image_width . 'px;' .
							'height:' . $default_image_height . 'px;' . $default_image_style;
	$prev_options = get_option( $newoption );
	if( ( isset( $force ) && $force ) || !isset($prev_options['plugin_version']) ) {
		// those default options are set-up at plugin first-install or manual reset only
		// they will not be changed when the plugin is just upgraded or deactivated/reactivated
		$newvalue['plugin_version'] = $yd_rp_version;
		$newvalue[1]['image_style'] = $default_image_style;
		$newvalue[1]['date_format'] = $default_date_format;
		$newvalue[1]['home_bottomlink'] = $default_bottomlink;
		$newvalue[1]['home_bottomtext'] = $default_bottomtext;
		$newvalue[1]['default_image'] = $default_thumbnail_img;
		$newvalue[1]['load_css'] = 1;
		$newvalue[0]['keep_html'] = 0; // don't strip html formatting from excerpts
		$newvalue[0]['strip_sqbt'] = 0; // strip special square bracket-enclosed tags
		$newvalue[0]['strip_clbt'] = 0; // strip special curly bracket-enclosed tags
		$newvalue[0]['display_ul'] = 1; // display as a ul li list (default)
		if( $prev_options ) {
			update_option( $newoption, $newvalue );
		} else {
			add_option( $newoption, $newvalue );
		}
	}
}
register_activation_hook(__FILE__, 'yd_rp_plugin_reset');

/** Create Text Domain For Translations **/
add_action('init', 'yd_rp_plugin_textdomain');
function yd_rp_plugin_textdomain() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain(
		'yd-recent-posts-widget', 
		'wp-content/plugins/' . $plugin_dir, $plugin_dir 
	);
}

/** Create custom admin menu page **/
add_action('admin_menu', 'yd_rp_plugin_menu');
function yd_rp_plugin_menu() {
	add_options_page(
	__('YD Recent Posts Options',
		'yd-recent-posts-widget'), 
	__('YDRecentPosts', 'yd-recent-posts-widget'),
	8,
	__FILE__,
		'yd_rp_plugin_options'
		);
}
function yd_rp_plugin_options() {
	echo '<div class="wrap">';
	echo '<div style="float:right;">'
	. '<img src="http://www.yann.com/yd-recent-posts-widget-v04-logo.gif" alt="YD logo" />'
	. '</div>';
	if( isset( $_GET["do"] ) ) {
		echo '<p>' . __('Action:', 'yd-recent-posts-widget') . ' '
		. __('I should now', 'yd-recent-posts-widget') . ' ' . $_GET["do"] . '.</p>';
		if(			$_GET["do"] == __('Clear cache', 'yd-recent-posts-widget') ) {
			clear_yd_widget_cache( 'widget_yd_rp_home' );
			clear_yd_widget_cache( 'widget_yd_rp_page' );
			clear_yd_widget_cache( 'widget_yd_rp_hometemplate1' ); // TODO? Clear other pages template cache?
			echo '<p>' . __('Caches are cleared', 'yd-recent-posts-widget') . '</p>';
		} elseif(	$_GET["do"] == __('Reset widget options', 'yd-recent-posts-widget') ) {
			yd_rp_plugin_reset( 'force' );
			echo '<p>' . __('Widget options are reset', 'yd-recent-posts-widget') . '</p>';
		} elseif(	$_GET["do"] == __('Update widget options', 'yd-recent-posts-widget') ) {
			yd_rp_plugin_update_options();
			echo '<p>' . __('Widget options are updated', 'yd-recent-posts-widget') . '</p>';
		}
	} else {
		echo '<p>'
		. '<a href="http://www.yann.com/wp-plugins/yd-recent-posts-widget" target="_blank" title="Plugin FAQ">';
		echo __('Welcome to YD recent Posts Admin Page.', 'yd-recent-posts-widget')
		. '</a></p>';
	}
	$options = get_option( 'widget_yd_rp' );
	$i = 0;
	echo '</div>';
	//---
	echo '<div class="wrap">';
	echo '<form method="get">';
	echo __('Display as a ul / li list', 'yd-recent-posts-widget') .
		'<input type="checkbox" name="yd_rp-display_ul-0" value="1" ';
	if( $options[$i]["display_ul"] == 1 ) echo 'checked="checked" ';
	echo "><br />";
	echo __('Keep HTML formatting in excerpts', 'yd-recent-posts-widget') .
		'<input type="checkbox" name="yd_rp-keep_html-0" value="1" ';
	if( $options[$i]["keep_html"] == 1 ) echo 'checked="checked" ';
	echo "><br />";
	echo __('Strip [square bracket-enclosed] special tags', 'yd-recent-posts-widget') .
		'<input type="checkbox" name="yd_rp-strip_sqbt-0" value="1" ';
	if( $options[$i]["strip_sqbt"] == 1 ) echo 'checked="checked" ';
	echo "><br />";
	echo __('Strip {curly bracket-enclosed} special tags', 'yd-recent-posts-widget') .
		'<input type="checkbox" name="yd_rp-strip_clbt-0" value="1" ';
	if( $options[$i]["strip_clbt"] == 1 ) echo 'checked="checked" ';
	echo "><br />";
	echo '<input type="submit" name="do" value="' . __('Update widget options', 'yd-recent-posts-widget') . '"><br/>';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
	echo '</form></div>';
	//---
	echo '<div class="wrap"><form method="get">';
	echo '<input type="submit" name="do" value="' . __('Clear cache', 'yd-recent-posts-widget') . '"><br/>';
	echo '<input type="submit" name="do" value="' . __('Reset widget options', 'yd-recent-posts-widget') . '"><br/>';
	echo '<input type="hidden" name="page" value="' . $_GET["page"] . '">';
	echo '</form>';
	echo '</div>';
	echo '<div class="wrap">';
	echo '<p>' . __('Homepage cache content:', 'yd-recent-posts-widget') . '</p>';
	echo '<div class="yd_rp_widget"><ul>' . get_yd_widget_cache( 'widget_yd_rp_home' ) . '</ul></div>';
	echo '</div>';
	echo '<div class="wrap">';
	echo '<p>' . __('Other pages cache content:', 'yd-recent-posts-widget') . '</p>';
	echo '<div class="yd_rp_widget"><ul>' . get_yd_widget_cache( 'widget_yd_rp_page' ) . '</ul></div>';
	echo '</div>';
}

/** Update display options of the options admin page **/
function yd_rp_plugin_update_options(){
	$to_update = Array(
		'display_ul',
		'keep_html',
		'strip_sqbt',
		'strip_clbt'
	);
	if( yd_update_options( 'widget_yd_rp', 0, $to_update, $_GET, 'yd_rp-' ) ) {
		clear_yd_widget_cache( 'widget_yd_rp_home' );
		clear_yd_widget_cache( 'widget_yd_rp_page' );
		clear_yd_widget_cache( 'widget_yd_rp_hometemplate1' ); // TODO? Clear other pages template cache?
	}
}

/** Display with PHP outside widget functions **/
function display_yd_recent_posts_here( $echo = TRUE, $cache_name = NULL, $spec_query = NULL ) {
	$html = '';
	$html .= '<ul>';
	$html .= widget_yd_rp( $echo, $cache_name, $spec_query );
	$html .= '</ul>';
	if( isset( $echo ) && $echo !== FALSE ) {
		echo $html;
	} else {
		return $html;
	}
}
function display_yd_previous_posts_here( $nb_posts = 0 ) {
	global $paged;
	if( $nb_posts === 0 ) $nb_posts = get_option( 'posts_per_page');
	$spec_query = 'post_type=post&showposts=' . $nb_posts . '&offset=' . ( get_option( 'posts_per_page') * $paged );
	display_yd_recent_posts_here( TRUE, 'hometemplate' . $paged, $spec_query );
}

/** Display inside content **/
function yd_recent_posts_generate( $content ) {
	if (strpos($content, "<!-- YDRPW -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- YDRPW -->', display_yd_recent_posts_here( FALSE ), $content);
	}
	return $content;
}
add_filter('the_content', 'yd_recent_posts_generate');

/** Widget function: previous posts **/
function widget_yd_pp( $args, $cache_name = NULL, $spec_query = NULL ) {
	global $paged;
	if( $nb_posts === 0 ) $nb_posts = get_option( 'posts_per_page');
	$spec_query = 'post_type=post&showposts=' . $nb_posts . '&offset=' . ( get_option( 'posts_per_page') * $paged );
	widget_yd_rp( TRUE, 'previoustemplate' . $paged, $spec_query );
}

/** Widget function: recent posts **/
function widget_yd_rp( $args, $cache_name = NULL, $spec_query = NULL ) {
	if( isset( $args ) && $args === FALSE ) {
		$echo = FALSE;
	} else {
		if( is_array( $args ) ) extract( $args );
		$echo = TRUE;
	}
	$default_cutlength = 128;
	global $wpdb;
	global $user_level;
	$plugin_dir = 'yd-recent-posts-widget';
	$options = get_option('widget_yd_rp');
	$current_querycount = get_num_queries();
	$html = '';
	$i = 1;
	if( is_admin() ) return;
	if( $spec_query ) {
		$my_wp_query = new WP_Query($spec_query);
	} else {
		if( is_home() ) {
			//echo "HOME<br/>";
			$title = $options[$i]["home_title"];
			if( $options[$i]["home_tag"] ) {
				$my_wp_query = new WP_Query(
					"post_type=post&showposts=" . $options[$i]["home_showposts"]
				. "&offset=0&tag=" . $options[$i]["home_tag"]
				);
			} else {
				$nb_to_skip = get_option('posts_per_page');
				$my_wp_query = new WP_Query(
					"post_type=post&showposts=" . $options[$i]["home_showposts"]
				. "&offset=" . $nb_to_skip
				);
			}
			if ( $options[$i]["home_datemeta"] ) {
				$date_type = $options[$i]["home_datemeta"];
			} else {
				$date_type = 'post_date';
			}
			$bottom_text = $options[$i]["home_bottomtext"];
			$bottom_link = $options[$i]["home_bottomlink"];
			$list_type = 'home';
		} else {
			//echo "PAGE<br/>";
			$title = $options[$i]["opage_title"];
			$my_wp_query = new WP_Query(
				"post_type=post&showposts=" . $options[$i]["opage_showposts"] 
			. "&offset=0&tag=" . $options[$i]["opage_tag"]
			);
			$date_type = 'post_date';
			$bottom_text = $options[$i]["opage_bottomtext"];
			$bottom_link = $options[$i]["opage_bottomlink"];
			$list_type = 'page';
		}
	}
	//specific overloaded usage within templates... (new in 0.7)
	if( $cache_name ) {
		$list_type = $cache_name;
		$title = '';
		$date_type = 'post_date';
		$bottom_text = '';
		$bottom_link = '';
	}
	//
	$title_cutlength = $options[$i]["title_cutlength"] ? $options[$i]["title_cutlength"] : $default_cutlength;
	$abstract_cutlength = $options[$i]["abstract_cutlength"] ? $options[$i]["abstract_cutlength"] : $default_cutlength;
	$image_style = $options[$i]["image_style"];
	$default_image = $options[$i]["default_image"];
	$load_css = $options[$i]["load_css"];
	if( !check_yd_widget_cache( 'widget_yd_rp_' . $list_type ) ) {
		//query_posts( $my_wp_query );
		$html .= $before_widget;
		if( $load_css )
		$html .= '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/' . $plugin_dir . '/css/yd_rp.css" />';
		if( $title )
		$html .= $before_title . $title . $after_title;
		$html .= '<div class="yd_rp_widget">';
		if ( $my_wp_query->have_posts() ) {
			if( $options[0]["display_ul"] ) $html .= '<ul>';
			while ( $my_wp_query->have_posts() ) {
				$my_wp_query->the_post();
				/** new in 0.7: make sure not to display private posts **/
				if( get_post_status() != 'publish' ) continue;
				$post = get_post( get_the_id() );
				if( $post->post_password != '' ) continue;

				// -- thumbnails ! --
				$link = '<a href="' . get_permalink() .'" rel="bookmark" title="' . __('Permanent link to:', 'yd-recent-posts-widget') . ' ' . get_the_title( '', '', FALSE ) . '">';

				//WP2.7 (?)
				$values = get_post_custom_values("thumb");
				$tn_url = $values[0];

				//Get first attachment
				if( !$tn_url ) $tn_url = $wpdb->get_var( "SELECT guid FROM " . $wpdb->posts . " WHERE post_type='attachment' and post_mime_type like 'image/%' and post_parent = " . get_the_id() . " and guid != '' LIMIT 1" );

				//Try to find first image in html
				if( !$tn_url ) {
					preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"]/', get_the_content(), $matches );
					$tn_url = $matches[1];
				}

				//WP2.0+ (wp-admin/includes/image.php:71-:75) .thumbnail file extension
				$extension = '.thumbnail';
				$tn_url = yd_check_thumbpath( $tn_url, $extension );

				//WP2.5+
				$extension = "-" . get_option( 'thumbnail_size_w' ) . 'x' . get_option( 'thumbnail_size_h' );
				$tn_url = yd_check_thumbpath( $tn_url, $extension );

				if( !$tn_url ) $tn_url = $default_image;

				if( $options[0]["display_ul"] ) $html .= '<li>';
				$html .= '<h4>';
				$html .= $link . '<img src="' . $tn_url . '" style="' . $image_style . '" alt="' . get_the_title() . '" />';
				//$html .= '</a>';
				$cont = get_the_content();
				if( $options[0]['strip_sqbt'] ) $cont = preg_replace( "/\[[^\]]+\]/", '', $cont );
				if( $options[0]['strip_clbt'] ) $cont = preg_replace( "/{[^}]+}/", '', $cont );
				if( $options[0]['keep_html'] ) {
					$cont = preg_replace( "/<img[^>]+>/i", '', $cont ); // get rid of images in excerpt
					$summary = yd_cake_truncate( $cont, $abstract_cutlength, '', false, true );
				} else {
					$summary = yd_clean_cut( $cont, $abstract_cutlength );
				}
				if( $options[$i]["display_date"] ) {
					if( $date_type != 'post_date' ) {
						$date = get_post_meta( get_the_id(), $date_type, true );
					} else {
						$date = get_the_time( $options[$i]["date_format"] );
					}
					$date .= __(' :', 'yd-recent-posts-widget') . ' ';
				} else {
					$date = '';
				}
				//$html .= '<a href="' . get_permalink() . '" rel="bookmark" title="' . get_the_title() . '">';
				$html .= yd_clean_cut( ( $date . get_the_title() ), $title_cutlength );
				$html .= '</a></h4>';
				$html .= '<div class="yd_rp_excerpt">' . $summary . $link . '...&nbsp;&raquo;</a></div>';
				if( $options[0]["display_ul"] ) {
					$html .= '</li>';
				} else {
					$html .= '<br clear="all" />';
				}
			}
			if( $options[0]["display_ul"] ) $html .= '</ul>';
		}
		$html .= '<a href="' . $bottom_link . '">' . $bottom_text . '</a>';
		$html .= '</div>' . $after_widget;
		update_yd_widget_cache( 'widget_yd_rp_' . $list_type, $html );
	} else {
		//echo "FROM CACHE<br/>";
		$html = get_yd_widget_cache( 'widget_yd_rp_' . $list_type );
	}
	//if( $user_level > 0 ) $html .= ( get_num_queries() - $current_querycount ) . '&nbsp;queries.<br />';
	if( $echo ) {
		echo $html;
	} else {
		return $html;
	}
}

/** Widget options **/
function widget_yd_rp_control($number) {
	$options = get_option( 'widget_yd_rp' );
	$to_update = Array(
		'home_title',
		'home_tag',
		'home_showposts',
		'home_datemeta',
		'home_bottomtext',
		'home_bottomlink',

		'opage_title',
		'opage_tag',
		'opage_showposts',
		'opage_bottomtext',
		'opage_bottomlink',

		'title_cutlength',
		'abstract_cutlength',
		'load_css',
		'image_style',
		'default_image',
		'display_date',
		'date_format'
	);
	if ( $_POST["yd_rp-submit-$number"] ) {
		if( yd_update_options( 'widget_yd_rp', $number, $to_update, $_POST, 'yd_rp-' ) ) {
			clear_yd_widget_cache( 'widget_yd_rp_home' );
			clear_yd_widget_cache( 'widget_yd_rp_page' );
			clear_yd_widget_cache( 'widget_yd_rp_hometemplate1' ); // TODO? Clear other pages template cache?
		}
	}
	foreach( $to_update as $key ) {
		$v[$key] = htmlspecialchars( $options[$number][$key], ENT_QUOTES );
	}
	?>
<div style="float: right"><a
	href="http://www.yann.com/wp-plugins/yd-recent-posts-widget"
	title="Help!" target="_blank">?</a></div>
<strong><?php echo __('Home page widget title:', 'yd-recent-posts-widget') ?></strong>
<br />
<input
	style="width: 450px;" id="yd_rp-home_title-<?php echo "$number"; ?>"
	name="yd_rp-home_title-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_title']; ?>" />
<br />
	<?php echo __('Home page tag:', 'yd-recent-posts-widget') ?>
<input style="width: 100px;"
	id="yd_rp-home_tag-<?php echo "$number"; ?>"
	name="yd_rp-home_tag-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_tag']; ?>" />
	<?php echo __('Home no. of posts:', 'yd-recent-posts-widget') ?>
<input style="width: 50px;"
	id="yd_rp-home_showposts-<?php echo "$number"; ?>"
	name="yd_rp-home_showposts-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_showposts']; ?>" />
<br />
	<?php echo __('Use special date meta:', 'yd-recent-posts-widget') ?>
<input style="width: 50px;"
	id="yd_rp-home_datemeta-<?php echo "$number"; ?>"
	name="yd_rp-home_datemeta-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_datemeta']; ?>" />
(
	<?php echo __('custom field name', 'yd-recent-posts-widget') ?>
)
<br />
	<?php echo __('Bottom text:', 'yd-recent-posts-widget') ?>
<input style="width: 250px;"
	id="yd_rp-home_bottomtext-<?php echo "$number"; ?>"
	name="yd_rp-home_bottomtext-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_bottomtext']; ?>" />
<br />
-
	<?php echo __('link:', 'yd-recent-posts-widget') ?>
<input style="width: 250px;"
	id="yd_rp-home_bottomlink-<?php echo "$number"; ?>"
	name="yd_rp-home_bottomlink-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['home_bottomlink']; ?>" />
<hr />
<strong><?php echo __('Other pages widget title:', 'yd-recent-posts-widget') ?></strong>
<br />
<input
	style="width: 450px;" id="yd_rp-opage_title-<?php echo "$number"; ?>"
	name="yd_rp-opage_title-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['opage_title']; ?>" />
<br />
	<?php echo __('Other pages tag:', 'yd-recent-posts-widget') ?>
<input style="width: 100px;"
	id="yd_rp-opage_tag-<?php echo "$number"; ?>"
	name="yd_rp-opage_tag-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['opage_tag']; ?>" />
	<?php echo __('Other no. of posts:', 'yd-recent-posts-widget') ?>
<input style="width: 50px;"
	id="yd_rp-opage_showposts-<?php echo "$number"; ?>"
	name="yd_rp-opage_showposts-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['opage_showposts']; ?>" />
<br />
	<?php echo __('Bottom text:', 'yd-recent-posts-widget') ?>
<input style="width: 250px;"
	id="yd_rp-opage_bottomtext-<?php echo "$number"; ?>"
	name="yd_rp-opage_bottomtext-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['opage_bottomtext']; ?>" />
<br />
-
	<?php echo __('link:', 'yd-recent-posts-widget') ?>
<input style="width: 250px;"
	id="yd_rp-opage_bottomlink-<?php echo "$number"; ?>"
	name="yd_rp-opage_bottomlink-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['opage_bottomlink']; ?>" />
<hr />
	<?php echo __('Title cut length:', 'yd-recent-posts-widget') ?>
<input style="width: 50px;"
	id="yd_rp-title_cutlength-<?php echo "$number"; ?>"
	name="yd_rp-title_cutlength-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['title_cutlength']; ?>" />
	<?php echo __('Abstract cut length:', 'yd-recent-posts-widget') ?>
<input style="width: 50px;"
	id="yd_rp-abstract_cutlength-<?php echo "$number"; ?>"
	name="yd_rp-abstract_cutlength-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['abstract_cutlength']; ?>" />
<br />
	<?php echo __('Load CSS:', 'yd-recent-posts-widget') ?>
<input style="width: 15px;" id="yd_rp-load_css-<?php echo "$number"; ?>"
	name="yd_rp-load_css-<?php echo "$number"; ?>" type="checkbox"
	value="1" <?php if( $v['load_css'] ) echo "checked=\"checked\""; ?> />
	<?php echo __('Image CSS Style:', 'yd-recent-posts-widget') ?>
<input style="width: 450px;"
	id="yd_rp-image_style-<?php echo "$number"; ?>"
	name="yd_rp-image_style-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['image_style']; ?>" />
<br />
	<?php echo __('Default image URL:', 'yd-recent-posts-widget') ?>
<input style="width: 300px;"
	id="yd_rp-default_image-<?php echo "$number"; ?>"
	name="yd_rp-default_image-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['default_image']; ?>" />
<br />
	<?php echo __('Display date:', 'yd-recent-posts-widget') ?>
<input style="width: 15px;"
	id="yd_rp-display_date-<?php echo "$number"; ?>"
	name="yd_rp-display_date-<?php echo "$number"; ?>" type="checkbox"
	value="1" <?php if( $v['display_date'] ) echo "checked=\"checked\""; ?> />
	<?php echo __('Date format:', 'yd-recent-posts-widget') ?>
<input style="width: 100px;"
	id="yd_rp-date_format-<?php echo "$number"; ?>"
	name="yd_rp-date_format-<?php echo "$number"; ?>" type="text"
	value="<?php echo $v['date_format']; ?>" />
<input
	type="hidden" id="yd_rp-submit-<?php echo "$number"; ?>"
	name="yd_rp-submit-<?php echo "$number"; ?>" value="1" />
	<?php
}

function widget_rp_init() {
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
	return;
	register_sidebar_widget( __('YD Recent Posts', 'yd-recent-posts-widget'), 'widget_yd_rp' );
	register_sidebar_widget( __('YD Previous Posts', 'yd-recent-posts-widget'), 'widget_yd_pp' );
	register_widget_control( __('YD Recent Posts', 'yd-recent-posts-widget'), 'widget_yd_rp_control', 470, 470, 1 );
	register_widget_control( __('YD Previous Posts', 'yd-recent-posts-widget'), 'widget_yd_rp_control', 470, 470, 1 );
}

// Tell Dynamic Sidebar about our new widget and its control
add_action('plugins_loaded', 'widget_rp_init');

// ============================ Generic YD WP functions ==============================

if( !function_exists( 'yd_update_options' ) ) {
	function yd_update_options( $option_key, $number, $to_update, $fields, $prefix ) {
		$options = $newoptions = get_option( $option_key );
		foreach( $to_update as $key ) {
			$newoptions[$number][$key] = strip_tags( stripslashes( $fields[$prefix . $key . '-' . $number] ) );
			//echo $key . " = " . $prefix . $key . '-' . $number . " = " . $newoptions[$number][$key] . "<br/>";
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option( $option_key, $options );
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if( !function_exists( 'check_yd_widget_cache' ) ) {
	function check_yd_widget_cache( $widg_id ) {
		$option_name = 'yd_cache_' . $widg_id;
		$cache = get_option( $option_name );
		//echo "rev: " . $cache["revision"] . " - " . get_yd_cache_revision() . "<br/>";
		if( $cache["revision"] != get_yd_cache_revision() ) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}

if( !function_exists( 'update_yd_widget_cache' ) ) {
	function update_yd_widget_cache( $widg_id, $html ) {
		//echo "uwc " . $widg_id;
		$option_name = 'yd_cache_' . $widg_id;
		$nvarr["html"] = $html;
		$nvarr["revision"] = get_yd_cache_revision();
		$newvalue = $nvarr;
		if ( get_option( $option_name ) ) {
			update_option( $option_name, $newvalue );
		} else {
			$deprecated=' ';
			$autoload='no';
			add_option($option_name, $newvalue, $deprecated, $autoload);
		}
	}
}

if( !function_exists( 'get_yd_widget_cache' ) ) {
	function get_yd_widget_cache( $widg_id ) {
		$option_name = 'yd_cache_' . $widg_id;
		$nvarr = get_option( $option_name );
		return $nvarr["html"];
	}
}

if( !function_exists( 'clear_yd_widget_cache' ) ) {
	function clear_yd_widget_cache( $widg_id ) {
		$option_name = 'yd_cache_' . $widg_id;
		$nvarr["html"] = __('clear', 'yd-recent-posts-widget');
		$nvarr["revision"] = 0;
		$newvalue = $nvarr;
		if ( get_option( $option_name ) ) {
			update_option( $option_name, $newvalue );
		} else {
			$deprecated=' ';
			$autoload='no';
			add_option($option_name, $newvalue, $deprecated, $autoload);
		}
	}
}

if( !function_exists( 'get_yd_cache_revision' ) ) {
	function get_yd_cache_revision() {
		global $wpdb;
		return $wpdb->get_var( "SELECT max( ID ) FROM " . $wpdb->posts .
			" WHERE post_type = 'post' and post_status = 'publish'" );
	}
}

if( !function_exists( 'yd_clean_cut' ) ) {
	function yd_clean_cut( $string, $cutlength ) {
		$string = substr( strip_tags( $string ), 0, $cutlength );
		if( strlen( $string ) == $cutlength ) {
			$last_blank = strrpos( $string, " " );
			if( $last_blank !== false ) $string = substr( $string, 0, $last_blank );
		}
		return $string;
	}
}

if( !function_exists( 'yd_check_thumbpath' ) ) {
	function yd_check_thumbpath( $tn_url, $extension ) {
		if ( basename( $tn_url ) == $thumb = apply_filters( 'thumbnail_filename', basename( $tn_url ) ) )
		$thumb = preg_replace( '!(\.[^.]+)?$!', $extension . '$1', basename( $tn_url ), 1 );
		$thumburl = str_replace( basename( $tn_url ), $thumb, $tn_url );
		$wud = wp_upload_dir();
		if( isset( $wud["baseurl"] ) ) {
			//WP2.6+ hell!
			$upload_path = $wud["baseurl"];
			$upload_path = preg_replace( "|^" . get_option( 'siteurl' ) . "/|", "", $upload_path );
		} else {
			$upload_path = get_option( 'upload_path' );
		}
		$thumbpath = preg_replace( "|^(.*?)/" . $upload_path . "|", ABSPATH . $upload_path, $thumburl );
		//echo "up: " . $upload_path . " - $thumb - $thumburl - $thumbpath<br/>";
		if( file_exists( $thumbpath ) ) {
			return $thumburl;
		} else {
			return $tn_url;
		}
	}
}

// ============================ Generic other functions ==============================

// clean cut function supporting HTML
// credits http://www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags/
// original credits: http://cakephp.org/
// maybe it breaks with utf-8
/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
if( !function_exists( 'yd_cake_truncate' ) ) {
	function yd_cake_truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
		
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
		
			foreach ($lines as $line_matchings) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1])) {
					// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
						// if tag is a closing tag (f.e. </b>)
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
							unset($open_tags[$pos]);
						}
						// if tag is an opening tag (f.e. <b>)
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
		
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length) {
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity) {
							if ($entity[1]+1-$entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
		
				// if the maximum length is reached, get off the loop
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
	
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
	
		// add the defined ending to the text
		$truncate .= $ending;
		
		if($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
	                $truncate .= '</' . $tag . '>';
		    }
		}
	        
		return $truncate;
	        
	} 
}
?>