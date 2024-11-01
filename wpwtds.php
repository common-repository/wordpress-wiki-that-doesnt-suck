<?php
/*
Plugin Name: WordPress Wiki That Doesn't Suck (WPWTDS)
Description: A WordPress Wiki that works.
Version: 0.9.2
Author: Arcane Palette Creative Design
Author URI: http://arcanepalette.com/
License: gpl3
*/

/* here are some constants */
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

define('WPWTDS_PATH',WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

/**
 *
 * WPWTDS Post Type
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.1
 *
 * creates a custom post type with a bunch of defined parameters
 *
 */
function wpwtds_create_post_type() {
  register_post_type( 'wpwtds_article',
    array(
      'labels' => array(
		'name' => __( 'Wiki != suck' ),
		'singular_name' => __( 'Article' ),
		'add_new' => __( 'Post New Wiki' ),
		'add_new_item' => __( 'Add New Article' ),
		'edit' => __( 'Edit' ),
		'edit_item' => __( 'Edit Wiki' ),
		'new_item' => __( 'New Wiki' ),
		'view' => __( 'View Wiki' ),
		'view_item' => __( 'View Wiki' ),
		'search_items' => __( 'Search Articles' ),
		'not_found' => __( 'No articles found' ),
		'not_found_in_trash' => __( 'No articles found in Trash' ),
		'parent' => __( 'Parent Wiki' ),
      ),
      'public' => true,
	  'rewrite' => array('slug' => 'wiki','with_front' => 'false'),
	  'supports' => array('title','editor','revisions','author', 'thumbnail')
    )
  );
}
add_action( 'init', 'wpwtds_create_post_type' );

/**
 * WordPress Wiki That Doesn't Suck Taxonomy
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.6.2
 *
 * Adds a taxonomy to allow the ability to split wiki articles up into 'sections'
 */
function wpwtds_taxonomy() {
	register_taxonomy( 'wiki_section', 'wpwtds_article',
		array(
			 'hierarchical' => true,
			 'labels' => array( 'name' => 'Sections', 'singular_name' => 'Section', 'add_new_item' => 'Add New Section' ),
			 'query_var' => 'wiki_section',
			 'rewrite' => array( 'slug' => 'section' )
		)
	);
}
add_action( 'init', 'wpwtds_taxonomy' );

/**
 * WordPress Wiki That Doesn't Suck HTML meta box
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.7
 *
 * This adds the call to the WPWTDS HTML area in the next section
 */
function wpwtds_htmlarea_meta() {
	add_meta_box('wpwtds-html','Custom HTML','wpwtds_htmlarea','wpwtds_article','normal','low');
}
add_action('admin_menu','wpwtds_htmlarea_meta');

/**
 *
 * WPWTDS HTML area
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.7
 *
 * This adds a meta box with an HTML textarea input.  This can then be placed wherever you want with get_post_meta
 * but the theory here is that you could do an "in this section" sort of block at the start of the document that includes
 * anchor links to headings within the document.
 */
function wpwtds_htmlarea() {
    global $post;

	echo '<input type="hidden" name="wpwtds_noncename" id="wpwtds_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	$wpwtds_html = get_post_meta($post->ID, 'wpwtds_html', true);
	echo '<p>';
	/* support for WordPress 3.3 wp_editor, added 0.8 */
	if ( function_exists( 'wp_editor' ) ) {
		wp_editor($wpwtds_html,'wpwtds_html');
	/* if wp_editor doesn't exist, use the old textarea instead */
	} else {
			echo '<textarea style="width: 90%;" rows="5" name="wpwtds_html">'.htmlspecialchars($wpwtds_html).'</textarea><br />';
	}
	echo 'You can add any custom HTML you like in this area and output it however you want using <tt>get_post_meta</tt>. The inteded use for this is to create an "In this section" area where you can have an unordered list with anchor links to sections within your single wiki pages, but you use this however you want.</p>';
}

/**
 *
 * WPWTDS Save Article
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.7
 *
 * Saves the meta data when the article is saved
 *
 */
function wpwtds_save_article($post_id, $post) {
    $nonce = isset( $_POST['wpwtds_noncename'] ) ? $_POST['wpwtds_noncename'] : 'insert_clever_phrase_here';
    if ( !wp_verify_nonce( $nonce, plugin_basename(__FILE__) )) {
	return $post->ID;
	}
	/* confirm user is allowed to save page/post */
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ))
		return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	}

	$meta_keys = array( 'wpwtds_html' => 'text' );

	/* Add values of $mydata as custom fields */
	foreach ($meta_keys as $meta_key => $type) {
		if( $post->post_type == 'revision' )
			return;
		if ( isset( $_POST[ $meta_key ] ) ) {
			if ( $type == 'text' ) {
				$value = wp_kses_post( $_POST[ $meta_key ] );
			}

			update_post_meta( $post->ID, $meta_key, $value );
		} else {
			delete_post_meta( $post->ID, $meta_key );
		}
	}
}

add_action('save_post', 'wpwtds_save_article', 1, 2);

/**
 *
 * WordPress Wiki That Doesn't Suck article columns
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.6.2
 *
 * Customizes the columns that appear on the Wiki != suck page
 */
add_filter('manage_edit-wpwtds_article_columns','wpwtds_edit_wpwtds_article_columns');
function wpwtds_edit_wpwtds_article_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Article',
		'wiki_section' => 'Section',
		'date' => 'Date Published'
	);
	return $columns;

}

/**
 *
 * WordPress Wiki That Doesn't Suck section column
 * @package wordpress-wiki-that-doesn't-suck
 * @since 0.8
 * @link http://devpress.com/blog/custom-columns-for-custom-post-types/
 * this adds the content from the custom 'section' column
 *
 */

add_action('manage_wpwtds_article_posts_custom_column', 'wpwtds_manage_wpwtds_article_columns',10,2);
function wpwtds_manage_wpwtds_article_columns( $column, $post_id ) {
	global $post;
	switch($column) {
		case 'wiki_section' :
			$terms = get_the_terms( $post_id, 'wiki_section' );
			// if there are terms...
			if (!empty($terms)) {
				$out = array();
				foreach ($terms as $term) {
					$out[] = sprintf( '<a href="%s">%s</a>',
					esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'wiki_section' => $term->slug ), 'edit.php' ) ),
					esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'wiki_section', 'display' ) )
					);
				}
				echo join (', ', $out);
			} else { // if no terms exist
				echo 'No Section Found';
			}
		break;
	}
}

/**
 *
 * WPWTDS Menu Icon
 *
 * @package wordpress-wiki-that-doesnt-suck
 * @since 0.5
 *
 * Adds a custom menu icon for the wpwtds_article post type
 *
 */
function wpwtds_icon() {
    _e('
    <style type="text/css" media="screen">
        #menu-posts-wpwtds_article .wp-menu-image {
            background: url('.WPWTDS_PATH.'/images/wiki.png) no-repeat bottom !important;
			opacity: 0.7;
			-moz-opacity: 0.7;
			-webkit-opacity: 0.7;
			filter:alpha(opacity=70)
        }
	#menu-posts-wpwtds_article:hover .wp-menu-image, #menu-posts-wpwtds_article.wp-has-current-submenu .wp-menu-image {
			opacity: 1.0;
			-moz-opacity: 1.0;
			-webkit-opacity: 1.0;
			filter:alpha(opacity=100)
        }
    </style>');

 }
add_action( 'admin_head', 'wpwtds_icon' );

 /**
  *
  * WPWTDS Header Icon
  *
  * @package wordpress-wiki-that-doesnt-suck
  * @since 0.6.2
  *
  * Adds a custom header icon on the Wiki != suck page
  *
  */
 function wpwtds_header() {
        global $post_type;
	?>
	<style>
	<?php if ($post_type == 'wpwtds_article') :
	_e('#icon-edit { background: url('.WPWTDS_PATH.'/images/wpwtds.png) no-repeat!important; } #icon-edit.icon32 { width: 50px!important; }');
	endif; ?>
        </style>
<?php }
add_action('admin_head', 'wpwtds_header');

/**
 * WPWTDS Shortcode
 * @author Chris Reynolds
 * @since 0.9
 * @package wordpress-wiki-that-doesnt-suck
 * adds a shortcode to display a list of wiki articles
 * Usage [wpwtds]
 */
function wpwtds_shortcode() {
	global $wp_query;
	$temp = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();
	$showposts = -1;
	$args = array(
		'post_type' => 'wpwtds_article',
		'posts_per_page' => $showposts,
	);

	$before_post = null;
	$after_post = null;
	query_posts($args); ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<?php if ( has_post_thumbnail() ) :
		$before_post = '<div class="media">';
		$before_post .= '<a href="' . get_permalink() . '" rel="bookmark" title="' . the_title_attribute( array( 'before' => __('Permanent Link to ', 'wpwtds'), 'after' => '', 'echo' => 0 ) ) . '" class="pull-left alignleft">';
		$before_post .= get_the_post_thumbnail($post->ID,'thumbnail');
		$before_post .= '</a>';
		$before_post .= '<div class="media-body">';
		$after_post = '</div>';
		$after_post .= '</div>';
		endif;
	?>
		<section class="<?php post_class(); ?>" id="post-<?php the_ID(); ?>">
			<?php echo $before_post; ?>
			<h2 class="the_title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf( __( 'Permanent Link to %s', 'wpwtds' ), the_title_attribute() ); ?>"><?php the_title(); ?></a></h2>
			<div class="excerpt"><?php the_excerpt(); ?></div>
			<div class="postmetadata"><?php echo sprintf( __( 'added on %s', 'wpwtds' ), get_the_time('j F Y') ); ?></div>
			<?php echo $after_post;
			if ( $before_post )
				$before_post = null;
			if ( $after_post )
				$after_post = null;
			?>
		</section>
	<?php endwhile; ?>
	<?php $wp_query = null; $wp_query = $temp;
	wp_reset_query();
}
add_shortcode('wpwtds', 'wpwtds_shortcode');
?>
