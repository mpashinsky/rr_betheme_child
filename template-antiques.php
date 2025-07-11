<?php
/**
 * Template Name: Antiques
 * Description: A Page Template that display portfolio items.
 *
 * @package Betheme
 * @author Muffin Group
 */

get_header(); 

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

<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
		
		
			<div class="extra_content">
				<?php
					if(get_the_ID() == '23') {

 			        	$page_num = (( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1 );
				    	$page_alias = get_simple_page_alias( get_query_var( 'paged' ), $_SERVER['REQUEST_URI'] );
				    	$slider_alias = $page_alias."-main-".$page_num;
            			$slider_shortcode =  get_rev_slider_shortcode_from_alias( $slider_alias );

						if ( revolution_slider_exists( $slider_alias ) ) {
							echo do_shortcode( $slider_shortcode );
						}
					}
					$mfn_builder = new Mfn_Builder_Front(mfn_ID(), true);
					$mfn_builder->show();

				?>
			</div>
			
			
			<div class="section section-filters">
				<div class="section_wrapper clearfix">
				
					<!-- #Filters -->
					<div id="Filters" class="column one <?php if( $iso ) echo 'isotope-filters'; ?>">

						<ul class="filters_buttons">
							<li class="label"><?php echo $translate['filter']; ?></li>
							<li class="categories"><a class="open" href="#"><i class="icon-docs"></i><?php echo $translate['categories']; ?><i class="icon-down-dir"></i></a></li>
							<?php 
								$portfolio_page_id = mfn_wpml_ID( mfn_opts_get( 'portfolio-page' ) );
								echo '<li class="reset"><a class="close" data-rel="*" href="'.get_page_link( $portfolio_page_id ).'"><i class="icon-cancel"></i> '. $translate['all'] .'</a></li>';
							?>
						</ul>
						
						<?php 
							// Category | Current ----
							if( $_GET && key_exists('cat',$_GET) ){
								$current_cat = $_GET['cat'];
							} else {
								$current_cat = false;
							}
						?>
						
						<div class="filters_wrapper" data-cat="<?php echo $current_cat; ?>">
							<ul class="categories">
								<?php 
									// Category | List -------
									if( $portfolio_categories = get_terms('portfolio-types') ){
										foreach( $portfolio_categories as $category ){
											echo '<li class="'. $category->slug .'"><a data-rel=".category-'. $category->slug .'" href="'. get_term_link($category) .'">'. $category->name .'</a></li>';
										}
									}
								?>
								<li class="close"><a href="#"><i class="icon-cancel"></i></a></li>
							</ul>
						</div>
								
					</div>
				
				</div>
			</div>

			
			<div class="section <?php echo $section_class; ?>">
				<div class="section_wrapper clearfix">

					<div class="column one column_portfolio">	
						<div class="portfolio_wrapper isotope_wrapper">
	
							<?php 
								$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
								//$cat_id = get_cat_ID('jewelry-and-antiques');
								$portfolio_args = array( 
									'category__not_in' => '-1',
									'cat' => '170',
									'post_type' 			=> 'antiques',
									'posts_per_page' 		=> mfn_opts_get( 'portfolio-posts', 6 ),
									'paged' 				=> $paged,
									'order' 				=> mfn_opts_get( 'portfolio-order', 'DESC' ),
								    'orderby' 				=> mfn_opts_get( 'portfolio-orderby', 'date' ),
									'ignore_sticky_posts' 	=> 1,
								);
				
								// demo
								if( $_GET && key_exists('mfn-iso', $_GET) ) 						$portfolio_args['posts_per_page'] = -1;
								if( $_GET && key_exists('mfn-p', $_GET) && $_GET['mfn-p']=='list' ) $portfolio_args['posts_per_page'] = 5;
								if( $_GET && key_exists('mfn-pp', $_GET) ) 							$portfolio_args['posts_per_page'] = $_GET['mfn-pp'];
								
								$portfolio_query = new WP_Query( $portfolio_args );
				
							 	echo '<ul class="portfolio_group lm_wrapper isotope '. $portfolio_classes .'">';
							 		echo romanov_antiques_archive( $portfolio_query );
								echo '</ul>';
				
								echo mfn_pagination( $portfolio_query, $load_more );
	
							 	wp_reset_query(); 
							?>
							
						</div>
					</div>
					
				</div>
			</div>

			
		</div>
		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar(); ?>
			
	</div>
</div>
<?php
if(get_the_ID() == '23') { ?>
    <div style = "text-align: center; margin: 20px; padding: 20px" >
        <a style = "background-color:#dd9933; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom" href = "/category/under-2900-2/" title = "Under $2,900" > Under $2,900 </a >
    </div >
<?php } ?>

<?php get_footer(); ?>
