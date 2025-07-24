<div class="col2">

	<div align="center">
<a href="http://www.fenuxe.com/subscribe">
<img src="<?php bloginfo('stylesheet_directory'); ?>/images/300x64Subbanner.jpg" alt="Subscribe to Fenuxe Magazine" width="300px" height="64px" />
</a>
</div>	

    <?php if (get_option('woo_ad_mpu_disable') == "false") include (TEMPLATEPATH . "/ads/mpu_ad.php"); ?>

	
<?php dynamic_sidebar(1); ?> 
<?php if (get_option('woo_ad_mpu_disable') == "false") include (TEMPLATEPATH . "/ads/mpu_ad2.php"); ?>
	

	<div class="fix"></div>
	
	<?php if (get_option('woo_tabs') == "false") { ?>

	<div id="tabs">

		

		<ul class="wooTabs tabs">

			<li><a href="#feat"><?php _e('Latest ',woothemes); ?></a></li>

			<li><a href="#pop"><?php _e('Popular ',woothemes); ?></a></li>

            <li><a href="#comm"><?php _e('Comments ',woothemes); ?></a></li>

			<li><a href="#tagcloud"><?php _e('Tags ',woothemes); ?></a></li>

            <li><a href="#sub"><?php _e('Subscribe ',woothemes); ?></a></li>

		</ul>	

		

		<div class="fix"></div>

		

		<div class="inside">

		 <div id="pop">

			<ul>

                <?php include(TEMPLATEPATH . '/includes/popular.php' ); ?>                    

			</ul>

           </div>

           

         <div id="feat"> 

	        <ul>

				<?php 

					$the_query = new WP_Query('cat=' . $ex_feat . '&showposts=10&orderby=post_date&order=desc');	

					while ($the_query->have_posts()) : $the_query->the_post(); $do_not_duplicate = $post->ID;

				?>

				<li>

                <?php woo_get_image('image',35,35,'thumbnail',90,$post->ID,'src',1,0,'','',true,false,false); ?>

                <a title="<?php _e('Permalink to ',woothemes); ?> <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>

                <div style="clear:both"></div>

                </li>

				<?php endwhile; ?>		

			</ul>

          </div>

          <div id="comm">  

			<ul>

                <?php include(TEMPLATEPATH . '/includes/comments.php' ); ?>                    

			</ul>

	      </div>

			<div id="tagcloud">

                <div>

				    <?php wp_tag_cloud('smallest=12&largest=20'); ?>

                </div>

			</div>

		

        <div id="sub">

	        <ul>

				<li><h3>Stay up to date</h3><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><img src="<?php bloginfo('template_directory'); ?>/images/ico-rss.gif" alt="RSS"  width="61"  height="61" /></a></li>

				<li><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>"><?php _e('Subscribe to the RSS feed',woothemes); ?></a></li>

				<li><a href="http://www.feedburner.com/fb/a/emailverifySubmit?feedId=<?php $feedburner_id = get_option('woo_feedburner_id'); echo $feedburner_id; ?>" 	target="_blank"><?php _e('Subscribe to the feed via email',woothemes); ?></a></li>

			</ul>            

        </div>

		</div>

		

	</div>

	

	<div class="fix" style="height:15px !important;"></div>

	

	<?php } ?>  

	<!-- TABS END -->

	


	

	<div class="fix"></div>

    
<!--/subcol-->

<div class="fix"></div>


</div><!--/col2-->

