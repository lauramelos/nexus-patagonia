<?php
/*
Plugin Name: Recently Updated Posts
Plugin URI: http://f00f.de/blog/2007/10/23/recently-updated-posts-plugin.html
Description: Returns a list of the most recently updated posts.
Version: 0.4
Author: Hannes Hofmann
Author URI: http://uwr1.de/

Based upon the plugin Recent Posts v1.1 from Nick Momrik
*/

// Possible $options:
// num : Number of posts to find [default:5]
// skip : Number of posts to skip; useful if you want paging [0]
// skipUnmodifiedPosts : Hide newly published (but yet unmodified) posts [true]
// includePages : Include pages [false]
// hideProtectedPosts : Hide protected posts [true]
// showDate : one of false, true or a date format string [false]
// excludeCategory : IDs of a category that should not show up in the list (comma-separated string) [null]
$hhRupDefaultOptions = array(
	'num'  => 5,
	'skip' => 0,
	'skipUnmodifiedPosts' => true,
	'includePages' => false,
	'hideProtectedPosts' => true,
	'showDate' => 'd.m.',
	'excludeCategory' => null,
);

// Possible $options: @see comment at $hhRupDefaultOptions
function hh_recently_updated_posts2($options = array()) {
//	global $post;
//	// save original post
//	$originalPost =& $post;

	$posts = hh_rup_get2($options);
	print '<ul>';
	foreach($posts as $post) {
		$title_ = wp_specialchars(strip_tags(str_replace('-', '- ', $post->post_title)));
		print '<li>'
			. '<a href="'.get_permalink($post->ID).'" title="'.wp_specialchars(strip_tags($post->post_title)).'">'
			. hh_rup_date($options['showDate'], $post->post_modified)
			. $title_
			. '</a></li>';
	}
	print '</ul>';

//	// restore original post
//	$post =& $originalPost;
}

// Possible $options: @see comment at $hhRupDefaultOptions
function hh_rup_get2(&$options) {
	global $wpdb;
	if (!is_array($options)) {
		return false;
	}
	hh_rup_sanitize_options($options);
	$now = gmdate('Y-m-d H:i:s', time());
	//$wpdb->show_errors(true);

	$select = "SELECT `ID`, `post_title`, `post_modified`, `comment_count`";
	$from   = "FROM `{$wpdb->posts}` AS `p`";
	$where  = "WHERE `post_status` = 'publish'"
		. " AND `post_modified_gmt` != '0000-00-00 00:00:00'"
		. ($options['skipUnmodifiedPosts']
			?  " AND `post_modified_gmt` != `post_date_gmt`" : '')
		. ($options['includePages']
			? " AND (`post_type` = 'post' OR `post_type` = 'page')"
			: " AND `post_type` = 'post'")
		. ($options['hideProtectedPosts']
			? " AND `post_password` = ''" : '')
		. " AND `post_modified_gmt` < '{$now}'";
	$group  = "";
	$order  = "ORDER BY `post_modified_gmt` DESC";
	$limit  = "LIMIT {$options['skip']}, {$options['num']}";

	if ($options['excludeCategory']) {
		$select .= ", GROUP_CONCAT(`tt`.`term_id`) AS `terms`";
		$from   .= " LEFT JOIN `{$wpdb->term_relationships}` AS `tr` ON `tr`.`object_id` = `p`.`ID`"
			 . " LEFT JOIN `{$wpdb->term_taxonomy}` AS `tt` ON `tt`.`term_taxonomy_id` = `tr`.`term_taxonomy_id`";
		$where  .= " AND `tt`.`taxonomy` = 'category'"
			 . " AND `tt`.`term_id` NOT IN ({$options['excludeCategory']})";
		$group  = "GROUP BY `ID`";
	}

	$sql = "{$select} {$from} {$where} {$group} {$order} {$limit}";

	return $wpdb->get_results($sql);
}

// check if all options are set to sane values, otherwise use defaults
function hh_rup_sanitize_options(&$options) {
	global $hhRupDefaultOptions;
	$options = wp_parse_args($options, $hhRupDefaultOptions);
}

function hh_rup_widget_show($args) {
	extract($args);
	$options = get_option('hh_rup_widget');
	$title = empty($options['title']) ? __('Recently Updated Posts') : $options['title'];
	echo $before_widget;
	echo $before_title . $title . $after_title;
	hh_recently_updated_posts2($options);
	echo $after_widget;
}

function hh_rup_widget_control() {
	$options = $newoptions = get_option('hh_rup_widget');
	if ( @$_POST['hh-rup-submit'] ) {
		$newoptions['num'] = strip_tags(stripslashes((int)$_POST['hh-rup-num']));
		$newoptions['title'] = strip_tags(stripslashes($_POST['hh-rup-title']));
		$newoptions['excludeCategory'] = strip_tags(stripslashes($_POST['hh-rup-excludeCategory']));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('hh_rup_widget', $options);
	}
	$title = attribute_escape($options['title']);
	$num = attribute_escape((int)$options['num']);
	$excludeCategory = attribute_escape($options['excludeCategory']);
?>
	<p><label for="hh-rup-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="hh-rup-title" name="hh-rup-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="hh-rup-num"><?php _e('Number of posts:'); ?> <input style="width: 250px;" id="hh-rup-num" name="hh-rup-num" type="text" value="<?php echo $num; ?>" /></label></p>
	<p><label for="hh-rup-excludeCategory"><?php _e('Exclude category:'); ?> <input style="width: 250px;" id="hh-rup-excludeCategory" name="hh-rup-excludeCategory" type="text" value="<?php echo $excludeCategory; ?>" /></label></p>
	<input type="hidden" id="hh-rup-submit" name="hh-rup-submit" value="1" />
<?php
}

function hh_rup_widget_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}
	register_sidebar_widget(__('Recently Updated Posts'), 'hh_rup_widget_show', 'widget-rup');
	register_widget_control(__('Recently Updated Posts'), 'hh_rup_widget_control', 300, 90);
}

function hh_rup_date($showDate, &$date) {
	$timestamp = strtotime($date);
	$dateFmt = '';
	if (true === $showDate) {
		$dateFmt = __('d.m.');
	} else if (is_string($showDate)) {
		$dateFmt = $showDate;
	}
	if ($dateFmt) {
		return date($dateFmt, strtotime($date)) . ': ';
	}

	if (false === $showDate) {
		return '';
	}	
	return '';
}

function hh_rup_date_short(&$date) {
	return date(__('d.m.'), strtotime($date));
}

/* DEPRECATED, use hh_recently_updated_posts2 */
function hh_recently_updated_posts($num = 5, $skip = 0, $skipUnmodifiedPosts = true, $includePages = false, $hideProtectedPosts = true) {
//	global $post;
//	// save original post
//	$originalPost =& $post;

	$posts = hh_rup_get($num, $skip, $skipUnmodifiedPosts, $includePages, $hideProtectedPosts);
	print '<ul>';
	foreach($posts as $post) {
		$title_ = wp_specialchars(strip_tags(str_replace('-', '- ', $post->post_title)));
		print '<li>'.'<a href="'.get_permalink($post->ID).'" title="'.wp_specialchars(strip_tags($post->post_title)).'">'.$title_.'</a></li>';
	}
	print '</ul>';

//	// restore original post
//	$post =& $originalPost;
}

/* DEPRECATED, use hh_rup_get2 */
function hh_rup_get($no_posts = 5, $skip = 0, $skipUnmodifiedPosts = true, $includePages = false, $hideProtectedPosts = true) {
	global $wpdb;
	$now = gmdate('Y-m-d H:i:s', time());
	$sql = "SELECT `ID`, `post_title`, `comment_count` FROM `{$wpdb->posts}`"
		. " WHERE `post_status` = 'publish'"
		. " AND `post_modified_gmt` != '0000-00-00 00:00:00'"
		. ($skipUnmodifiedPosts
			?  " AND `post_modified_gmt` != `post_date_gmt`" : '')
		. ($includePages
			? " AND (`post_type` = 'post' OR `post_type` = 'page')"
			: " AND `post_type` = 'post'")
		. ($hideProtectedPosts
			? " AND `post_password` = ''" : '')
		. " AND `post_modified_gmt` < '{$now}'"
		. " ORDER BY `post_modified_gmt` DESC"
		. " LIMIT {$skip}, {$no_posts}";
	return $wpdb->get_results($sql);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'hh_rup_widget_init');
?>