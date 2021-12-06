<?php
/**
 * The search template file.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();

// $translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
// $translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
// $translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');

// Class
$portfolio_classes 	= '';
$section_class 		= array();


// Class | Layout
if( $_GET && key_exists('mfn-p', $_GET) ){
	$portfolio_classes .= $_GET['mfn-p']; // demo
} else {
	$portfolio_classes .= mfn_opts_get( 'portfolio-layout', 'grid' );
}

if( $portfolio_classes == 'list' ) $section_class[] = 'full-width';


// Class | Columns
if( $_GET && key_exists('mfn-pc', $_GET) ){
	$portfolio_classes .= ' col-'. $_GET['mfn-pc']; // demo
} else {
	$portfolio_classes .= ' col-'. mfn_opts_get( 'portfolio-columns', 3 );
}


if( $_GET && key_exists('mfn-pfw', $_GET) )	$section_class[] = 'full-width'; // demo
if( mfn_opts_get('portfolio-full-width') )	$section_class[] = 'full-width';
$section_class = implode( ' ', $section_class );


// Isotope
if( $_GET && key_exists('mfn-iso', $_GET) ){
	$iso = true; // demo
} elseif(  mfn_opts_get( 'portfolio-isotope' ) ) {
	$iso = true;
} else {
	$iso = false;
}


// Ajax | load more
$load_more = mfn_opts_get('portfolio-load-more');


// Translate
$translate['filter'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-filter','Filter by') : __('Filter by','betheme');
$translate['all'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
?>


<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group" style="width:100% !important;">
		
			<div class="section">
				<div class="section_wrapper clearfix">
				
					<div class="column one column_portfolio">	
						<div class="portfolio_wrapper isotope_wrapper">
			
							<div class="posts_group classic">
								<?php

								echo '<ul class="portfolio_group lm_wrapper isotope '. $portfolio_classes .'">';
							 		echo romanov_antiques_archive( $portfolio_query );
								echo '</ul>';
								
									/*
									while ( have_posts() ):

										
										the_post();
										?>
										<div id="post-<?php the_ID(); ?>" <?php post_class( array('post-item', 'clearfix', 'no-img') ); ?>>
											
											<div class="post-desc-wrapper">
												<div class="post-desc">
												
													<?php if( mfn_opts_get( 'blog-meta' ) ): ?>
														<div class="post-meta clearfix">
															<div class="author-date">
																<span class="author"><span><?php echo $translate['published']; ?> </span><i class="icon-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a></span>
																<span class="date"><span><?php echo $translate['at']; ?> </span><i class="icon-clock"></i> <?php echo get_the_date(); ?></span>
															</div>
														</div>
													<?php  endif; ?>
													
													<?php if ( has_post_thumbnail() ) : ?>
														<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
														<?php the_post_thumbnail( array(550, 309) ); ?>
														</a>
													<?php endif; ?>
													<div class="post-title search-post-title">
														<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
													</div>
													
													<div class="post-excerpt">
														<?php the_excerpt(); ?>
													</div>
	
													<div class="post-footer">
														<div class="post-links search-read-more-link">
															<i class="icon-doc-text"></i> <a href="<?php the_permalink(); ?>" class="post-more search-read-more"><?php echo $translate['readmore']; ?></a>
														</div>
													</div>
						
												</div>
											</div>
										</div>
										<?php
									endwhile;
									*/
								?>
							</div>
					
							<?php	
								// pagination
								if(function_exists( 'mfn_pagination' )):
									echo mfn_pagination();
								else:
									?>
										<div class="nav-next"><?php next_posts_link(__('&larr; Older Entries', 'betheme')) ?></div>
										<div class="nav-previous"><?php previous_posts_link(__('Newer Entries &rarr;', 'betheme')) ?></div>
									<?php
								endif;
							?>
					
						</div>
					</div>
					
				</div>
			</div>
			
		</div>

	</div>
</div>

<?php get_footer(); ?>