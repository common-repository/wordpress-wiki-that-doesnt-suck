<?php 
/*
	Template Name: Wiki 
*/
?>
<?php get_header(); ?>
<div class="content">
            <h2>Support Wiki</h2>
			<div class="alignright"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
            <div class="clear"></div>
            <?php $loop = new WP_Query( array( 'post_type' => 'wpwtds_article', 'posts_per_page' => 10 ) );
            while ( $loop->have_posts() ) : $loop->the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">				
				<h3 class="the_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
				added on <?php the_time('j F Y') ?>
                <div class="clear"></div>
    </div>

            <?php endwhile; ?>
    	<div class="clear"></div>

	</div>

<?php get_sidebar(); ?>          
<div class="clear"></div>
<?php get_footer(); ?>