<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();

global $post;
$post_id = $post->ID;

$images_meta = get_post_meta( $post_id, 'product_gallery' );
$image_gallary = array();

if( isset( $images_meta ) && !empty( $images_meta ) ) {
	if( isset( $images_meta[0] ) ) {
		if( !empty( $images_meta[0] ) && $images_meta[0] != '' ) {
			if( is_array( $images_meta[0] ) && !empty( $images_meta[0] ) ) {
				foreach( $images_meta[0] as $i ){
					if( empty( $i ) ) {
						$image_gallary = get_image_gallery_by_attachments( $post_id );
						break;
					}
					$arr['type'] = 'image_gallery';
					$src = wp_get_attachment_image_src( $i, 'full' );
					$arr['gallery_image'] = $src[0];
					array_push( $image_gallary, $arr );
				}
			} else {
				$image_gallary = get_image_gallery_by_attachments( $post_id );
			}
		} else {
			$image_gallary = get_image_gallery_by_attachments( $post_id );
		}
	} else {
		$image_gallary = get_image_gallery_by_attachments( $post_id );
	}
} else {
	$image_gallary = get_image_gallery_by_attachments( $post_id );
}

$images_for_gallery = array(); //after foreach, this will be he array of all the images in the gallery
$cats = array(); // array of categories

if ( is_array( $image_gallary ) && !empty( $image_gallary ) ) {
	foreach ( $image_gallary as $key => $single_image ) {
		$images_for_gallery[$key] = $single_image['gallery_image'];
	}
}

$images_for_gallery = array_reverse( $images_for_gallery );

$status = get_uf('status');
$price = get_uf('price');
$reference = get_uf('reference');
$first_post_id = get_uf('first_post_id');
$second_post_id = get_uf('second_post_id');
$third_post_id = get_uf('third_post_id');
$fourth_post_id = get_uf('fourth_post_id');

$post_categories = wp_get_post_categories( $post_id );
foreach($post_categories as $c){
	$cat = get_category( $c );
	$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
}

?>

<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">

			<?php 
			while ( have_posts() ){
				the_post();	
				include(locate_template('includes/content-single-antique.php' ) );

			}
			
			?>
			
			<?php if( mfn_opts_get('portfolio-comments') ): ?>
				<div class="section section-page-comments">
					<div class="section_wrapper clearfix">

						<div class="column one comments">
							<?php comments_template( '', true ); ?>
						</div>
						
					</div>
				</div>
			<?php endif; ?>
			
		</div>


		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer(); ?>