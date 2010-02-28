<?php
/**
 * @package WordPress
 * @subpackage Nexus
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /-->

<style type="text/css" media="screen"></style>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="main_container">
  
    <div id="header">
      
    	<div class="logo"><a href="<?php echo get_option('home'); ?>/"><img src="<?php bloginfo('template_url'); ?>/images/logo.png" border="0" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>" /></a></div>
        <div class="description"><img src="<?php bloginfo('template_url'); ?>/images/description.png" border="0" alt="<?php bloginfo('description'); ?>" title="<?php bloginfo('description'); ?>" /></div>
        <!--div id="circle"></div> <div id="esquina"></div-->
    </div>

  <div class="menu">

          <ul>

              <!--<li><a href="<?php bloginfo('url'); ?>">Home</a></li>-->
					<?php wp_list_pages('title_li=&depth=1'); ?>
            <!--li class="selected"><a href="#">home</a></li>
                <li><a href="#">about</a></li>
                <li><a href="#">demo</a></li>
                <li><a href="#">license</a></li>
                <li><a href="#">modules</a></li>
                <li><a href="#">themes</a></li>
                <li><a href="#">contact</a></li-->
          </ul>
        </div>