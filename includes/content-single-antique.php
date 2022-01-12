<?php
/**
 * The template for displaying content in the single-portfolio.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// PayPal Handler
// If the user clicks the PayPal checkout button...
if (isset($_POST['paypal'])) {
    
    $testmode = false;
    $post_id = get_the_id();
    $paypalurl = $testmode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
    $business = $testmode ? 'sb-fqbtb1445850@business.example.com' : 'romanovrussia@aol.com';
    $return_url = 'https://www.romanovrussia.com/thank-you-confirmation/';
    $notify_url = 'https://www.romanovrussia.com/paypal-ipn-listener/?ipn_listener=paypal';
    $cancel_return_url = get_post_permalink();
    $amount = $testmode ? '1' : str_replace( ',', '', $price );
    $item_name = get_the_title();

    $available = isItemAvailable($post_id);

    if( $available ) {
        $data = array(
            'cmd' => "_xclick",
            'business' => $business,
            'item_name' => $item_name,
            'item_number' => $post_id,
            'amount' => $amount,
            'rm' => '2',
            'return' => $return_url,
            'notify_url'	=> $notify_url,
            'cancel_return' => $cancel_return_url
        );
        // Send the user to the paypal checkout screen
        header('location:' . $paypalurl . '?' . http_build_query($data));
        // End the script don't need to execute anything else
        exit;
    }
    else {
        //Show message, that item is not avalable anymore
        echo do_shortcode('[popupwfancybox id="1"]');
    }
}

function isItemAvailable($post_id) {
    global $wpdb;
    if ( $post_id != '' ) {
        $status = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id LIKE $post_id AND meta_key LIKE 'status'");
        if ( $status == 'Available' ) {
            return true;
        }
    }
    return false;
}

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



// prev & next post -------------------
$in_same_term = ( mfn_opts_get( 'prev-next-nav' ) == 'same-category' ) ? true : false;
$post_prev = get_adjacent_post( $in_same_term, '', true, 'portfolio-types' );
$post_next = get_adjacent_post( $in_same_term, '', false, 'portfolio-types' );
$portfolio_page_id = mfn_opts_get( 'portfolio-page' );
// categories -------------------------
$categories 	= '';
$aCategories 	= '';
$terms = get_the_terms( get_the_ID(), 'portfolio-types' );
if( is_array( $terms ) ){
	foreach( $terms as $term ){
		$categories		.= '<li><a href="'. get_term_link($term) .'">'. $term->name .'</a></li>';
		$aCategories[]	= $term->term_id;  
	}
}

$permalink =  get_permalink();
$permalink =  str_replace("http://romanov.staging.wpengine.com/antique/", "", $permalink);
$permalink =  str_replace("/", "", $permalink);
$splittedstring = explode("-",$permalink);


$urlrom = '';
$urlrom .= "http://romanovrussia.com/";
$eos = '_';
$lastElement = end($splittedstring);
foreach ($splittedstring as $key => $value) {
	// $splittedstring[$key] = ucfirst( $splittedstring[$key] );
	$urlrom .= ucfirst( $splittedstring[$key] );
	if ($value !== $lastElement) {
		$urlrom .= $eos;
	}
}
$urlrom .= ".html";

// echo $urlrom;
// echo "<a href='" .$urlrom. "' target='_blank'>".$urlrom."</a>";

// post classes -----------------------
$classes = array();
if( ! mfn_opts_get( 'share' ) ) $classes[] = 'no-share';
if( get_post_meta(get_the_ID(), 'mfn-post-slider-header', true) ) $classes[] = 'no-img';
$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
$translate['all'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
$translate['related'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-related','Related posts') : __('Related posts','betheme');
$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
$translate['client'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-client','Client') : __('Client','betheme');
$translate['date'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-date','Date') : __('Date','betheme');
$translate['website'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-website','Website') : __('Website','betheme');
$translate['view'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-view','View website') : __('View website','betheme');
$translate['task'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-task','Task') : __('Task','betheme');
?>
<div id="portfolio-item-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="section section-portfolio-header">
		<div class="section_wrapper clearfix">
			<?php if( false/*mfn_opts_get('prev-next-nav')*/ ): ?>
				<div class="column one post-nav">
					<?php 
					// prev & next post navigation
					echo mfn_post_navigation( $post_prev, 'prev', 'icon-left-open-big' ); 
					echo mfn_post_navigation( $post_next, 'next', 'icon-right-open-big' ); 
					?>
					<ul class="next-prev-nav">
						<?php if( $post_prev ): ?>
							<li class="prev"><a class="button button_js" href="<?php echo get_permalink( $post_prev ); ?>"><span class="button_icon"><i class="icon-left-open"></i></span></a></li>
						<?php endif; ?>
						<?php if( $post_next ): ?>
							<li class="next"><a class="button button_js" href="<?php echo get_permalink( $post_next ); ?>"><span class="button_icon"><i class="icon-right-open"></i></span></a></li>
						<?php endif; ?>
					</ul>
					<?php if( $portfolio_page_id ): ?>
						<a class="list-nav" href="<?php echo get_permalink( mfn_wpml_ID( $portfolio_page_id ) ); ?>"><i class="icon-layout"></i><?php echo $translate['all']; ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="column one post-header">
				<div class="button-love"><?php echo mfn_love() ?></div>
				
				<div class="title_wrapper single-antiques-template-header">
					<?php 
					$h = mfn_opts_get( 'title-heading', 1 );
					echo '<h'. $h .' class="entry-title romanov-custom-template-header" itemprop="headline">'. get_the_title() .'</h'. $h .'>';
					?>
					<div class="antiques-templates-disclaimer">Click on Images to Enlarge</div>	
				</div>
				
			</div>
			<div class="column two-third ">
				<div class="antique_masonry_wrap">
					<?php foreach ( $images_for_gallery as $key => $image_url) { ?>
					<div class="column antique_masonry one-second">
						<div class="image_frame image_item no_link scale-with-grid aligncenter has_border">
						    <div class="image_wrapper">
						        <a rel="swipebox" class="<?php if ( strpos($image_url, 'youtube') !== false) { echo "youtube-a"; } ?>" href="<?php 
						            if ( strpos($image_url, 'youtube') !== false) {
						                $video_code_start = strpos($image_url, '-', strpos($image_url, 'youtube')) + 1;
						                $video_code_end = strpos($image_url, '.', strpos($image_url, 'youtube'));
						                echo "https://www.youtube.com/watch?v="; 
						                echo substr($image_url, $video_code_start, $video_code_end - $video_code_start);
						                echo "&rel=0&autoplay=1";
						            }
					                else {
						                echo $image_url; 
					                } ?>">
						            <img class="scale-with-grid" src="<?php echo $image_url; ?>" alt="" width="479" height="447"></a>
				            </div>
			            </div>
					</div>
					<?php } ?>
				</div>				
			</div>
			<div class="column one-third">
				<div class="column_attr" style="">
					<div class="entry-content portfolio-main-content" itemprop="mainContentOfPage">
						<?php
							// Content Builder & WordPress Editor Content
							// mfn_builder_print( get_the_ID() );
							$mfn_builder = new Mfn_Builder_Front(get_the_ID());
							$mfn_builder->show();
						?>
					</div>

					<div class="meta-value-parent">
						<h5 class="template-sidebar-heading" style="margin-bottom: 5px;">Status:</h5>
						
						<span class="meta-value reserved-status">
    						<?php if($status == "Sold") {
    						    echo '<h1>'. $status .'</h1>';
    						}
    						else if ($status == "Reserved") {
        						echo '<h1>'. $status .'</h1>';
        						echo '<p>This item is reserved and waiting for the payment to be completed.</p>';
    						}
    						else {
        						echo '<p>'. $status .'</p>';
    						} ?>
						</span>
						
					</div>	
					<?php
					
					if ( $status !== 'Sold' && $price !== '' ) { ?>
					<div class="meta-value-parent">
						<h5 class="template-sidebar-heading" style="margin-bottom: 5px;">Price:</h5>
						
						<span class="meta-value jwellry-product-price ">
						<?php 
						echo '<p>';
						if ( $price !== 'P.O.R' && $price !== 'P.O.R.'  ) {
							echo '&#36;';
						}
						echo $price .'</p>';

						?> 
							<?php } ?>
							<?php $paypalbuttoncode = get_value( 'paypal_button'); ?>
							
							<?php if ( $status !== 'Sold' && $price !== '' && $price !== 'P.O.R' && $price !== 'P.O.R.' && $status !== 'Reserved') {
		        					if ( strcmp($paypalbuttoncode, 'old_paypal_button_code') == 0) { ?>
            							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="paypal-button-section">
            								<!-- Identify your business so that you can collect the payments. -->
            								<input type="hidden" name="business" value="romanovrussia@aol.com">
            								<!-- Specify a Buy Now button. -->
            								<input type="hidden" name="cmd" value="_xclick">
            								<!-- Specify details about the item that buyers will purchase. -->
            								<input type="hidden" name="item_name" value="<?php single_post_title(); ?>">
            								<input type="hidden" name="amount" value="<?php echo str_replace( ',', '', $price ); ?>">
            								<!-- Display the payment button. -->
            								<input src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" type="image">
            								<img alt="" border="0" width="1" height="1"
            								src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
            							</form>
    							<?php } /*else if ($paypalbuttoncode == '') { ?>
    							            <script src="https://www.paypal.com/sdk/js?client-id=AWJXJq5vIxHZf0NfNL4TlsT7QLw8MkWGFWUPt_SBBc_KiVFPcH9EBH6G3lUzY2-6POc0asMBHB3xS1Yr"> // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
                                                </script><?php
                                                $price = str_replace( ',', '', $price );
							                    echo do_shortcode('[checkout_for_paypal reference_id="'.get_the_id().'" item_description="'.get_the_title().'" amount="'.$price.'"]'); ?>
							        
    							<?php } */else if ($paypalbuttoncode == '') { ?>
    							            <form method="post" class="paypal-button-section">
                                                <input src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" name="paypal" alt="PayPal - The safer, easier way to pay online!" border="0" type="image">
                                                <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
                                                <input type="hidden" name="paypal">
                                            </form> 
					           <?php  } else { 
    							            $paypalbuttoncode = substr_replace($paypalbuttoncode, "class='paypal-button-section' ", strpos($paypalbuttoncode, "action"), 0); 
    							            echo $paypalbuttoncode; 
					                } ?>
							<?php } ?>
						</span>
					</div>
					<div class="meta-value-parent">
						<span class="meta-value portfolio-category-list">
							<?php
							$cat = get_the_category_list();
							if ( $cat ) {

								echo '<p>';
								echo '<h5 class="template-sidebar-heading" style="margin-bottom: 5px;">Categories:</h5>';
								echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'imedica' ) );
								echo "</p>";
							}
							?>

						</span>
					</div>	
					
					<?php
				// Count tags
					$tags = wp_get_post_tags( get_the_ID() );
					$tag_count = count( $tags );
					if( $tag_count ) {
						echo '	<span class="meta-value portfolio-tag-list">';
						the_tags( '<h5>Tags:</h5>', ', ', '');
						echo '	</span>';
					} ?>
					<div class="meta-value-parent reference-section">
						<?php if ( $reference != ' ' && $reference != NULL ) { ?>
						
						<h5 class="template-sidebar-heading" style="margin-bottom: 5px;">Reference:</h5>
						
						<span class="meta-value reserved-status"><?php echo '<p>'. $reference .'</p>'; ?></span>
						<?php } ?>

					</div>	
					<br>
					<div class="meta-value-parent reference-section">
    					<div class="vc_btn3-container  home-read-more margin-remove vc_btn3-center">
    	                    <a style="border: 1px solid #d49b50; color:#ffffff;" 
    	                        class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom" 
    	                        href="mailto:info@romanovrussia.com?subject=Question about item with reference <?php echo $reference ?>">
    	                            Ask a question
                            </a>
                        </div>
                    </div>
					<div class="meta-value-parent reference-section">
    					<div class="vc_btn3-container  home-read-more margin-remove vc_btn3-center">
    	                    <a style="border: 1px solid #d49b50; color:#ffffff;" 
    	                        class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-square vc_btn3-style-custom" 
                                href="/terms" title="">
    	                            Terms of sale
                            </a>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="section section-post-footer">
		<div class="section_wrapper clearfix">
			<div class="column one post-pager">
				<?php
					// List of pages
				wp_link_pages(array(
					'before'			=> '<div class="pager-single">',
					'after'				=> '</div>',
					'link_before'		=> '<span>',
					'link_after'		=> '</span>',
					'next_or_number'	=> 'number'
					));
					?>
				</div>
			</div>
		</div>
		<div class="section section-post-related">
			<div class="section_wrapper clearfix">
				<?php
				if( mfn_opts_get( 'portfolio-related' ) && $aCategories ){
					$args = array(
						'post_type' 			=> 'portfolio',
						'tax_query' => array(
							array(
								'taxonomy'	=> 'portfolio-types',
								'field'		=> 'term_id',
								'terms'		=> $aCategories
								),
							),
						'post__not_in'			=> array( get_the_ID() ),
						'posts_per_page'		=> 3,
						'post_status'			=> 'publish',
						'no_found_rows'			=> true,
						'ignore_sticky_posts'	=> true,
						);
					$query_related_posts = new WP_Query( $args );
					if ( $query_related_posts->have_posts() ){
						echo '<div class="section-related-adjustment">';
						echo '<h4>'. $translate['related'] .'</h4>';
						while ( $query_related_posts->have_posts() ){
							$query_related_posts->the_post();
							echo '<div class="column one-third post-related '. implode(' ',get_post_class()).'">';	
							echo '<div class="image_frame scale-with-grid">';
							echo '<div class="image_wrapper">';
							echo mfn_post_thumbnail( get_the_ID(), 'portfolio' );
							echo '</div>';
							echo '</div>';
							echo '<div class="date_label">'. get_the_date() .'</div>';
							echo '<div class="desc">';
							echo '<h4><a href="'. get_permalink() .'">'. get_the_title() .'</a></h4>';
							echo '<hr class="hr_color" />';
							echo '<a href="'. get_permalink() .'" class="button button_left button_js"><span class="button_icon"><i class="icon-layout"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
							echo '</div>';
							echo '</div>';
						}
						echo '</div>';
					}
					$rev_slider = get_post_meta( get_the_ID() );
					
					wp_reset_postdata();
				}
				//echo 'vrunda<xmp>'; print_r($rev_slider); echo '</xmp>kansara';
				global $post;
				// echo "<pre>";
				// var_dump( $post );
				// echo "</pre>";
				//do_shortcode( '[ultimate_carousel slides_on_desk="4"][get_related_posts][/ultimate_carousel]' );
				
				imedica_related_posts( $post );

				?>
			</div>
		</div>
	</div>