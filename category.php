<?php
get_header(); 
global $pagename, $wp_query ;

$category_name = $wp_query->query['category_name'];
// Class
$portfolio_classes 	= '';
$section_class 		= array();
$is_last_page = false;

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
					
					$page_num = (( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1 );
					$page_alias = get_simple_category_page_alias( get_query_var( 'paged' ), $_SERVER['REQUEST_URI'] );
					$slider_alias =  $page_alias."-page-".$page_num; 
					$slider_shortcode =  '[rev_slider alias="'.$slider_alias.'"]'; 

					if ( revolution_slider_exists( $slider_alias ) ) {
						echo do_shortcode( $slider_shortcode );
					}
					else {

						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
						if ($paged == 1) {
							$cat_obj = $wp_query->get_queried_object();
							echo do_shortcode(get_field('top_slider_shortcode',$cat_obj->taxonomy.'_'.$cat_obj->term_id));
						}
						if ($paged == 2) {
							$cat_obj = $wp_query->get_queried_object();
							echo do_shortcode(get_field('top_slider_shortcode_page_2',$cat_obj->taxonomy.'_'.$cat_obj->term_id));
						}
					}

					//$mfn_builder = new Mfn_Builder_Front(mfn_ID(), true);
					//$mfn_builder->show();
				
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
			
			<div class="section the_content category-description">
				<div class="section_wrapper clearfix">
        			<div class="the_content_wrapper">
                        <div class="vc_row wpb_row vc_row-fluid">
                    		<div class="wpb_column vc_column_container vc_col-sm-12">
                    			<div class="vc_column-inner ">
                    			    <div class="wpb_wrapper">
                        				<div class="wpb_raw_code wpb_content_element wpb_raw_html">
                        					<div class="wpb_wrapper">
                                                <?php 
                                                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
                                                if ($paged == 1) echo term_description( $current_cat ); ?>
                        					</div>
                        				</div>
                    				</div>
                    			</div>
                    		</div>
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
								// echo "<pre>";
								// print_r( $paged );
								// echo "</pre>";

								$portfolio_args = array( 
									'category__not_in' => '-1',
									'post_type' 			=> 'antiques',
									'posts_per_page' 		=>  mfn_opts_get( 'portfolio-posts', 6 ),
									'paged' 				=> $paged,
									'order' 				=> mfn_opts_get( 'portfolio-order', 'DESC' ),
								    'orderby' 				=> mfn_opts_get( 'portfolio-orderby', 'date' ),
									'ignore_sticky_posts' 	=> 1,
									'tax_query' => array(
										array(
											'taxonomy' => 'category',
											'field' => 'slug',
											'terms' => $category_name,
										)
									)

								);

								// echo "<pre>";
								// print_r( $category_name );
								// echo "</pre>";
				
								// demo
								if( $_GET && key_exists('mfn-iso', $_GET) ) 						$portfolio_args['posts_per_page'] = -1;
								if( $_GET && key_exists('mfn-p', $_GET) && $_GET['mfn-p']=='list' ) $portfolio_args['posts_per_page'] = 5;
								if( $_GET && key_exists('mfn-pp', $_GET) ) 							$portfolio_args['posts_per_page'] = $_GET['mfn-pp'];
								
								$portfolio_query = new WP_Query( $portfolio_args );
								$max_page = $portfolio_query->max_num_pages;
								$current_page = $portfolio_query->get( 'paged' );
	                            if($max_page == $current_page) {
	                                $is_last_page = true;
	                            }
							 	echo '<div class="portfolio_group lm_wrapper isotope '. $portfolio_classes .'">';
							 		echo romanov_antiques_archive( $portfolio_query );
								echo '</div>';
				
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

<?php if ( $is_last_page ) {
    $cat_obj = $wp_query->get_queried_object();
    echo do_shortcode(get_field('slider_shortcode',$cat_obj->taxonomy.'_'.$cat_obj->term_id));
} ?>
<?php if ( $category_name == 'under-2900-2' ) { ?>
    <div style="text-align: center; margin: 20px; padding: 20px">
        <a style="background-color:#dd9933; color:#ffffff;" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom" href="/new-arrivals/" title="New Arrivals">New Arrivals</a>
    </div>
<?php } ?>
<?php get_footer(); ?>
