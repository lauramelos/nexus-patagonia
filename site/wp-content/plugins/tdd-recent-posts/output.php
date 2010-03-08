<?php


		//This is yet another database call just to make SURE that we have the variables we need. I think this is redundant, but until I know exactly what is being sent where, its the safest way.

		$options = get_option('tddrecentposts');

		$returnnum = $options['returnnum'];
		$lengthof = $options['lengthof'];
		$title = $options['title'];
		$truncate_excerpts = $options['truncate_excerpts'];
		$hard_truncate = $options['hard_truncate'];

		global $wpdb;


		$sqllimit = 1000 + $lengthof;
		// SQLlimit sets the amount of data to get from the post. +250 is a guess for how much random HTML could sit before the content since all HTML content gets stripped out. Its not perfect, but I wanted to really limit DB queries.

		$before = '<li><dl>';
		$after = '</dl></li>';

		//Hide posts that require a password to view
		$hide_pass_post = true;

		//Set to "1" to skip always skip the most recent post, etc. Not in the admin section yet.
		$skip_posts = 0;

		//show_excerpts is not an admin option because the point of this plugin is to show excerpts (otherwise use the built-in WP widget. However, it can be toggled here to turn off ALL post content previews. This does not rely on the 'excerpt' built into WP page/post authoring.
		$show_excerpts = true;

		$include_pages = false;


		//Figure out what the current time is so that we are only getting results that are published in the past
		$time_difference = get_settings('gmt_offset');
		$now = gmdate("Y-m-d H:i:s",time());


		//Below is a great CF for the SQL query. Fairly easy to interpret, though.
		$request = $wpdb->prepare("SELECT ID, post_title, post_date, post_excerpt,LEFT(post_content,$sqllimit) AS short_post_content FROM $wpdb->posts WHERE post_status = 'publish' ");
		if($hide_pass_post) $request .= "AND post_password ='' ";
		if($include_pages) $request .= "AND (post_type='post' OR post_type='page') ";
		else $request .= "AND post_type='post' ";
		$request .= "AND post_date_gmt < '$now' ORDER BY post_date DESC LIMIT $skip_posts, $returnnum";

		//Send the request to the DB and capture the result in $posts
		$posts = $wpdb->get_results($request);

		//This starts our list. Its important to maintain the ".=" instead of "=" because the main php file for this function may have already written content to $output
		$output .= '<ul class="tddrecentposts">';

		//Seperate_counter allows us, if needed for future flexibility, count how many rows have been proccessed.
		$separate_counter = 0;

		if($posts) {
			foreach ($posts as $post) {
				$separate_counter = $separate_counter + 1;

				//Define some variables for use below
				$post_title = stripslashes($post->post_title);
				$permalink = get_permalink($post->ID);
				$postdate = date("M j, Y",strtotime($post->post_date));

				//Output the title and link for each entry in the list
				$output .= $before . '<dt><a href="' . $permalink . '" rel="bookmark" title="' . htmlspecialchars($post_title, ENT_COMPAT) . '">' . $post_title . '</a></dt><dd class="sidebardate">'. $postdate ."</dd>";

				//post_excerpt refers to the excerpt function built into WP post/page writing. If an excerpt already exists we don't need to create one, we'll just use that
				if($show_excerpts == 'true' && $post->post_excerpt != '') {

				/*deal with Post Excerpts */

					if ($truncate_excerpts == 0) {
						$post_excerpt = stripslashes($post->post_excerpt);
						$output.= PHP_EOL.'<dd>' . $post_excerpt . ' ... </dd>';
					} else if($hard_truncate == 1) {

						$string = $post->post_excerpt;
						$string = substr($string,0,$lengthof);
						$output .= PHP_EOL.'<dd>'.$string.'</dd>';

					} else if($truncate_excerpts == 1) {
						$string = $post->post_excerpt;
						$break = ".";
						$pad = ". ...";

						if(strlen($string) <= $lengthof) {
						//Do nothing, $string is OK to be placed in $output without truncating it because its already smaller than the length we want to truncate to
						} else  {
							// is $break present between $lengthof and the end of the string?
							if(false !== ($breakpoint = strpos($string, $break, $lengthof))) {
								if($breakpoint < strlen($string) - 1) {
									$string = substr($string, 0, $breakpoint) . $pad;
								}
							}
						}
						$output.= PHP_EOL.'<dd>' . $string . ' </dd>';
						}


				} elseif ($show_excerpts == 'true') {

			/*deal with posts w/o post_excerpts */

				if ($hard_truncate == 1) {
					$string = strip_tags(stripslashes($post->short_post_content));
					$string = substr($string,0,$lengthof);
					$output .= PHP_EOL.'<dd>'.$string.'</dd>';

				} else {

					//We are investigate $string -- this is the full $sqllimit.
					$string = strip_tags(stripslashes($post->short_post_content));

					//$break allows us to break the $string at a defined (rather than arbitrary) point. In this case we are breaking at a period, usually the end of a sentence. $pad is what we will add at the end to indicate that there is more. In this case, it's three periods or elipsis.
					$break = ".";
					$pad = ". ...";
						if(strlen($string) <= $lengthof) {

						//Do nothing, $string is OK to be placed in $output without truncating it because its already smaller than the length we want to truncate to

						} else  {

						 // is $break present between $lengthof and the end of the string?

							 if(false !== ($breakpoint = strpos($string, $break, $lengthof))) {
							 	if($breakpoint < strlen($string) - 1) {
							 		$string = substr($string, 0, $breakpoint) . $pad;
							 	}
							 }
						}
					//Now that were done hacking away at the $string, lets send it to the output.
					$output .=  "<dd>".$string."</dd>";

					}
				}


				//Were all done now. PHP_EOL = end of line. Makes the HTML source look nice.
				if($separate_counter <= $returnnum) {
					$output .= $after . PHP_EOL . PHP_EOL;
				}
			}

			$output .= '</ul>';

		} else {
			$output .= $before . "None found" . $after . '</ul>';
		}
?>