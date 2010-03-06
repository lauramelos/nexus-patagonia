<?php
/*
 Template Name: Inicio
*/

get_header(); ?>

	<!--div id="content" class="narrowcolumn" role="main"-->
<div class="center_content">
        <div class="center_left">

          <div class="welcome_box">
            
            <p class="welcome">
             <?php 
              $args = 'displayTitle=false displayStyle="DT_TEASER_MORE" titleBefore="<h3>" titleAfter="</h3>"  more="continue&raquo"';
             if(function_exists('iinclude_page')) iinclude_page(5,$args); ?>

              <span class="orange">Lorem ipsum dolor sit amet, consectetur adipisicing elit </span><br/>

              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a dui non diam porttitor semper vitae ut massa. Proin augue enim, feugiat ac sodales eget, accumsan sed arcu. Donec elit lorem, congue nec dapibus vitae, imperdiet iaculis risus. Nam lacus odio, varius nec malesuada quis, aliquam a felis.  

            </p>
            <a class="read_more" href="#">leer más</a>
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

<?php get_sidebar(); ?>
 <div class="clear"></div>
 </div>
<?php get_footer(); ?>