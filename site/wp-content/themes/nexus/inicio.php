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
            <div class="title">Valores</div>

                    <ul class="list">
                    <li><span>1</span><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</a></li>
                    <li><span>2</span><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</a></li>
                    <li><span>3</span><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</a></li>
                    <li><span>4</span><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</a></li>
                    </ul>
         </div>


         <div class="features">
            <div class="title">Novedades</div>
                <div class="news_box">
                    <div class="news_icon"></div>
                    <div class="news_content">
                    “Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </div>
                </div>
                <div class="news_box">
                    <div class="news_icon"></div>
                    <div class="news_content">
                    “Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </div>
                </div>

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