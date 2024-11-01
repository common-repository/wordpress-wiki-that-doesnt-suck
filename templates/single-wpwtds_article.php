<?php 
/*
	This is the single post template
*/
?>
<?php get_header(); ?>
<div class="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
				<div class="post" id="post-<?php the_ID(); ?>">				

				<h2 class="the_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                <div class="clear"></div>
				<?php if (get_post_meta($post->ID, 'wpwtds_html')) { ?>
					<div id="in-this-article">
						<?php echo get_post_meta($post->ID,'wpwtds_html',true); ?>
					</div>
				<?php } ?>
				<div class="entry">
					<?php the_content('Read more &raquo;'); ?>
				</div>
                Posted on <?php the_time('j F Y') ?><br />
                <div class="clear"></div>
    <p><?php edit_post_link('Edit this entry','','.'); ?></p>

    	<div class="clear"></div>
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
		</div>
        <div class="clear"></div>        
				</div>
	</div>

		
        <?php endwhile; endif; ?>
<?php get_sidebar(); ?>          
<div class="clear"></div>
<?php get_footer(); ?>