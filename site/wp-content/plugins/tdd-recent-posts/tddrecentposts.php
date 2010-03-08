<?php
/*
Plugin Name: TDD Recent Posts
Version: 1.2
Plugin URI: http://tddewey.com/tdd-recent-posts-wordpress-plugin
Description: A recent-posts widget that displays a small amount of the post text
Author: Taylor Dewey
Author URI: http://www.tddewey.com
*/
/*
Plugin template written by Trevor Creech (http://trevorcreech.com)
Other than the plugin template, copyright (c) 2008 Taylor D. Dewey (td@tddewey.com)

This software is distributed under the following license:
GNU General Public License (GPL) version 3
http://www.gnu.org/licenses/gpl.html

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/



//Uncomment this next line to set $pluginpath to the directory where the plugin resides.  This is very handy if you need to refer to other files included in the directory (images, for example).  The final slash is added, so use it like this: $image = $pluginpath . 'filename.png';  I found this handy line in the Sidebar Widget's code.

$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_settings('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';


//First off, we are going to set up the options used in this plugin if the plugin is being activated and they don't already exist
register_activation_hook( __FILE__, 'td_install');

function td_install() {

            $options = get_option('tddrecentposts');

            //If the options array is not true (because there is nothing there, because get_options is empty or doens't exist let's add our defaults.

            if(!$options) {

            //Set up the default options
            $title_default 	=	'Recent Posts';
            $returnnum_default	=	5;		//Number of rows to return. Default default is 5
            $lengthof_default	=	50;		//# of characters in the post content excerprt. Default default is 50
			$hard_truncate_default = 0; // Truncate number of characters "exactly". Default is off, we truncate to a '.'
			$truncate_excerpts_default = 0; //Truncate excerpts, too. Default is off.
			$ver = '1.2';

            $options2 = array('title'=>$title_default, 'returnnum'=>$returnnum_default, 'lengthof'=>$lengthof_default, 'hard_truncate'=>$hard_truncate_default, 'truncate_excerpts'=>$truncate_excerpts_default, 'ver'=>$ver);

            add_option('tddrecentposts',$options2);


            } else if(!$options['ver'] || $options['ver'] != 1.2) {
			//Update the database with our new variables for this installation


			//New Vars
			$hard_truncate_default = 0; // Truncate number of characters "exactly". Default is off, we truncate to a '.'
			$truncate_excerpts_default = 0; //Truncate excerpts, too. Default is off.
           	$ver = '1.2';

			$options2['hard_truncate'] = $hard_truncate_default;
			$options2['truncate_excerpts'] = $truncate_excerpts_default;
			$options2['ver'] = $ver;

			update_option('tddrecentposts',array_merge($options,$options2));

			}

}


//uncomment the next line if you want the plugin to uninstall itself from the database. This will be an option in future releases
//register_deactivation_hook( __FILE__, 'td_uninstall');
function td_uninstall() {

          delete_option('tddrecentposts');

            }





//Anything you echo in this function will be placed in the pages header.  To use this function, you must uncomment this line at the bottom of this file: add_action('wp_head', 'tddrecentposts_header');


//This is not used for this plugin at this time.
function tddrecentposts_header()
{

	//External javascript file in the plugin directory
	echo '<script type="text/javascript" src="'. $pluginpath . 'filename.js"></script>';

	//Embedded javascpript
	echo '<script type="text/javascript">
		//some javascript code
		'. /* some php code */ '' . '
	</script>';

	//External css file in the plugin directory
	echo '<link rel="stylesheet" href="'.$pluginpath .'filename.css" type="text/css" media="screen" />';

	//Embedded css
	echo '<style type="text/css">
		/* some CSS */
	</style>';
}

//This is a wrapper for the main function, which grabs the parameters from a direct function call, or from the options database.  The first parameter is important, because it allows you to have the direct data returned.  I use this to insert a plugin contents into a post with the content_tddrecentposts function. If you don't pass any other arguments, the options will be pulled from those set in the options panel.
function tddrecentposts($echo = 'true') {
	$options = get_option('tddrecentposts');

	$title = (($title != '') ? $title : $options['title']);
	$returnnum = (($returnnum != '') ? $returnnum : $options['returnnum']);
	$lengthof = (($lengthof != '') ? $lengthof : $options['lengthof']);

	if($echo)
	{
		echo tddrecentposts_return ($title, $returnnum, $lengthof);
	}
	else
	{
		return tddrecentposts_return ($title, $returnnum, $lengthof);
	}
}

//This is the heart of the plugin, where you get to write your own php code.  I'm afraid I can't help you with that, as it will be completely unique to your plugin.  Just make sure to return your output, instead of echoing it.  The parameters will be passed directly from the tddrecentposts function, so you don't need to use get_options().
function tddrecentposts_return ($title, $returnnum, $lengthof)
{
	global $pluginpath;

//The file output.php deals with calculating and returning $output. It's probably not the best way to segment everything, but it works for now.

require_once('output.php');

	return($output);
}

//This function creates a backend option panel for the plugin.  It stores the options using the wordpress get_option function.
function tddrecentposts_control()
{
		$options = get_option('tddrecentposts');

		/*This shouldnt be neccessary since there was a on activation hook at the very top
		if ( !is_array($options) )
		{
			//This array sets the default options for the plugin when it is first activated.
			$options = array('title'=>'Recent News', 'returnnum'=>'5', 'lengthof'=>'50');
		}*/

		if ( $_POST['tddrecentposts-submit'] )
		{
			$options['title'] = strip_tags(stripslashes($_POST['tddrecentposts-title']));

			//Evaluate the two other parameters to ensure they are numeric and fall within the specified range

			if (is_numeric($_POST['tddrecentposts-returnnum']) && $_POST['tddrecentposts-returnnum'] <= 15){
			$options['returnnum'] = $_POST['tddrecentposts-returnnum'];
			}

			if (is_numeric($_POST['tddrecentposts-lengthof']) && $_POST['tddrecentposts-lengthof'] <= 200){
			$options['lengthof'] = $_POST['tddrecentposts-lengthof'];
			}

			if (!$_POST['tddrecentposts-hard_truncate']){
			$options['hard_truncate'] = 0;
			} else {
			$options['hard_truncate'] = 1;
			}

			if ($_POST['tddrecentposts-truncate_excerpts'] != 1){
			$options['truncate_excerpts'] = 0;
			} else {
			$options['truncate_excerpts'] = 1;
			}

			update_option('tddrecentposts', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);

		echo '<p style="text-align:right;"><label for="tddrecentposts-title">Title:</label><br /> <input id="tddrecentposts-title" name="tddrecentposts-title" type="text" value="'.$title.'" /></p>';

		//You need one of these for each option/parameter.  You can use input boxes, radio buttons, checkboxes, etc.
		echo '<p style="text-align:right;"><label for="tddrecentposts-returnnum">Number of entries to display (max 15)</label><br /> <input  id="tddrecentposts-returnnum" name="tddrecentposts-returnnum" type="text" size="2" maxlength="2" value="'.$options['returnnum'].'" /></p>';
		echo '<p style="text-align:right;"><label for="tddrecentposts-lengthof">Length of the preview text (in characters, maximum of 200)</label><br /> <input id="tddrecentposts-lengthof" name="tddrecentposts-lengthof" type="text" size="3" maxlength="3" value="'.$options['lengthof'].'" /></p>';
		if($options['hard_truncate'] == 1){
			$hard_truncate_checked = 'checked="checked"';
			}
		echo '<p style="text-align:right;"><label for="tddrecentposts-hard_truncate">Hard Truncate<br />(break at <em>exactly</em> the number of chars above)</label><br /> <input id="tddrecentposts-hard_truncate" name="tddrecentposts-hard_truncate" type="checkbox" '. $hard_truncate_checked.' value="1" /></p>';
				if($options['truncate_excerpts'] == 1){
					$truncate_excerpts_checked = 'checked="checked"';
			}
		echo '<p style="text-align:right;"><label for="tddrecentposts-truncate_excerpts">Truncate the excerpts, too</label><br /> <input id="tddrecentposts-truncate_excerpts" name="tddrecentposts-truncate_excerpts" type="checkbox" '. $truncate_excerpts_checked.' value="1" /></p>';


		echo 'Thanks for using TDD Recent Posts. The database version is: '. $options['ver'];
		echo '<input type="hidden" id="tddrecentposts-submit" name="tddrecentposts-submit" value="1" />';
	}

//This function is a wrapper for all the widget specific functions
//You can find out more about widgets here: http://automattic.com/code/widgets/
function widget_tddrecentposts_init()
{
	if (!function_exists('register_sidebar_widget'))
		return;

	//This displays the plugin's output as a widget.  You shouldn't need to modify it.
	function widget_tddrecentposts($args)
	{
		extract($args);

		$options = get_option('tddrecentposts');
		$title = $options['title'];

		echo $before_widget;
		echo $before_title . $title . $after_title;
		tddrecentposts();
		echo $after_widget;
	}



	register_sidebar_widget('TDD Recent Posts', 'widget_tddrecentposts');
	//You'll need to modify these two numbers to get the widget control the right size for your options.  250 is a good width, but you'll need to change the 200 depending on how many options you add
	register_widget_control('TDD Recent Posts', 'tddrecentposts_control', 250, 200);
}

//Uncomment this if you want the options panel to appear under the Admin Options interface
//add_action('admin_menu', 'tddrecentposts_addMenu');

//Uncomment this is you need to include some code in the header
//add_action('wp_head', 'tddrecentposts_header');

//Uncomment this if you want the token to be called using a token in a post (<!--tddrecentposts-->)
//add_filter('the_content', 'content_tddrecentposts');

//You can comment this out if you're not creating this as a widget
add_action('plugins_loaded', 'widget_tddrecentposts_init');

?>
