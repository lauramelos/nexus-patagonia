<?php
/*
 Template Name: Inicio
*/

get_header();


?>
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
	<!--div id="content" class="narrowcolumn" role="main"-->
<div class="center_content">
        <div class="center_left">

          <div class="welcome_box">
              <h1> <?php  
              $post_ID=get_the_ID();
              
              echo get_post_meta($post_ID, 'subtitulo', true);?></h1>
              <?php
              $args = 'displayTitle=false displayStyle="DT_TEASER_MORE" titleBefore="<h3>" titleAfter="</h3>"  more="continue&raquo"';
              if(function_exists('iinclude_page')) iinclude_page(5,'displaytitle=false&more=Leer más');
              ?>
         </div>

          <div class="features">
            <h3>Servicios</h3>
            <ol class="list">
              <?php $args = array(
                                  'depth'        => 1,
                                  'show_date'    => '',
                                  'date_format'  => get_option('date_format'),
                                  'child_of'     => 7,
                                  'exclude'      => '',
                                  'include'      => '',
                                  'title_li'     => false,
                                  'echo'         => 1,
                                  'authors'      => '',
                                  'sort_column'  => 'menu_order, post_title',
                                  'link_before'  => '',
                                  'link_after'   => '',
                                  'exclude_tree' => '' );
              wp_list_pages( $args );
              ?>
            </ol>
         </div>


         <div class="features">
            <h3>Novedades</h3>

            <?php
            $posts = get_posts('numberposts=3&order=DES&orderby=post_date');
            foreach ($posts as $post) : start_wp(); ?>


           <div class="news_box">
                    <div class="news_icon">
                      <acronym class="published" title="<?php the_time('F jS, Y'); ?>">
                        <span class="pub-month"><?php the_time('M'); ?></span>
                        <span class="pub-date"><?php the_time('j'); ?></span>
                      </acronym>
                    </div>
                    <div class="news_content">
                      <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>
                      
                      <?php the_excerpt(); ?>
             </div>
                </div>
            <?php
            endforeach;
            ?>

      
               

         </div>
</div>
<?php endwhile; ?>
  	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

<?php get_sidebar(); ?>
 <div class="clear"></div>
 </div>
<?php get_footer(); ?>