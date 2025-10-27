<?php
/**
 * Betheme Child Theme
 *
 * @package Betheme Child Theme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/**
 * Child Theme constants
 * You can change below constants
 */

/**
 * Load Textdomain
 * @deprecated please use BeCustom plugin instead
 */

// define('WHITE_LABEL', false);

/**
 * Load Textdomain
 */

load_child_theme_textdomain('betheme', get_stylesheet_directory() . '/languages');
load_child_theme_textdomain('mfn-opts', get_stylesheet_directory() . '/languages');

/**
 * Compatibility shims for Betheme core functions that may be missing
 * This prevents fatal errors if the parent theme is not fully loaded.
 */
if ( ! function_exists( 'mfn_opts_show' ) ) {
    function mfn_opts_show( $key, $default = null ) {
        // Retrieve option via mfn_opts_get when available; otherwise use default
        if ( function_exists( 'mfn_opts_get' ) ) {
            $value = mfn_opts_get( $key, $default );
        } else {
            $value = $default;
        }
        // Normalize arrays to scalar
        if ( is_array( $value ) ) {
            $value = reset( $value );
        }
        if ( $value !== null ) {
            echo $value;
        }
    }
}

/**
 * Enqueue Styles
 */

function mfnch_enqueue_styles()
{
	// enqueue the parent stylesheet
	// however we do not need this if it is empty
	// wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');

	// enqueue the parent RTL stylesheet

	if ( is_rtl() ) {
		wp_enqueue_style('mfn-rtl', get_template_directory_uri() . '/rtl.css');
	}

	// enqueue the child stylesheet

	wp_dequeue_style('style');
	wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css');
}
add_action('wp_enqueue_scripts', 'mfnch_enqueue_styles', 101);

/** Slider revolution **/

/** Checks that slider exists by alias **/
function revolution_slider_exists( $alias ) {

    if( class_exists( 'RevSlider' ) ) {
        $slider = new RevSlider();
        return $slider->alias_exists($alias);
    }
    return false;
}

/** Builds slider shortcode from slider alias **/
function get_rev_slider_shortcode_from_alias( $slider_alias ) {

    return '[rev_slider alias="'.$slider_alias.'"][/rev_slider]';
}

/** Get page alias without paged **/
function get_simple_page_alias( $paged, $current_page_uri ) {

    if ($paged) {
        $page_part_pos = strpos($current_page_uri , '/page');
        $current_page_alias = substr($current_page_uri, 0, $page_part_pos);
    }
    else {
        $current_page_alias = $current_page_uri;
    }

    return str_replace("/", "", $current_page_alias);
}

function get_simple_category_page_alias( $paged, $current_page_uri ) {

    if ($paged) {
        $page_part_pos = strpos($current_page_uri , '/page');
        $current_page_alias = substr($current_page_uri, 0, $page_part_pos);
    }
    else {
        $current_page_alias = $current_page_uri;
    }
    $category_part = '/category';
    $current_page_alias = substr($current_page_alias, strlen($category_part));

    return str_replace("/", "", $current_page_alias);
}

/** Custom **/

function wpb_image_editor_default_to_gd( $editors ) {
    $gd_editor = 'WP_Image_Editor_GD';
    $editors = array_diff( $editors, array( $gd_editor ) );
    array_unshift( $editors, $gd_editor );
    return $editors;
}
add_filter( 'wp_image_editors', 'wpb_image_editor_default_to_gd' );
add_action( 'parse_query', 'changept' );
function changept() {
	if( is_category() || is_tag() && !is_admin() )
		set_query_var( 'post_type', array( 'post', 'antiques' ) );
	return;
}
/*
function wpse74325_pre_get_posts( $query ) {
    if ( $query->is_main_query() && is_category() ) {
        $query->set( 'posts_per_page', '5' );
    }
}
add_action( 'pre_get_posts', 'wpse74325_pre_get_posts' ); */
//
// Recommended way to include parent theme styles.
//  (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
//  

function get_image_gallery_by_attachments( $post_id ){
	$image_gallary = array();
	$attachments = get_children( array(
		'post_parent'    => $post_id,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image'
	) );
	if( !empty( $attachments ) ){
		foreach( $attachments as $i ){
			$arr['type'] = 'image_gallery';
			$arr['gallery_image'] = wp_get_attachment_url( $i->ID );
			array_push( $image_gallary, $arr );
		}
	}
	return $image_gallary;
}

add_action( 'after_setup_theme', 'related_article_size' );
function related_article_size() {
    //add_image_size( 'category-thumb', 300 ); // 300 pixels wide (and unlimited height)
    add_image_size( 'related_article_size', 270, 270, true ); // (cropped)
    add_image_size( 'related_article_size_2x', 540, 540, true ); // (cropped)
    add_image_size( 'related_article_size_small', 350, 350, true ); // (cropped)
}

function load_scripts() {
   // wp_enqueue_style( 'style-lightbox', get_stylesheet_uri() );
   wp_enqueue_style( 'lightbox-css', get_stylesheet_directory_uri() . '/js/lightbox.css' );
   wp_enqueue_script( 'lightbox-js', get_stylesheet_directory_uri() . '/js/lightbox.js', array(), '1.0.0', true );
   wp_enqueue_script( 'masonry' );
   wp_enqueue_script( 'cusom-js', get_stylesheet_directory_uri() . '/js/custom.js', array(), '1.0.0', true );
   wp_enqueue_style( 'slick-css', get_stylesheet_directory_uri() . '/css/slick.css' );
   wp_enqueue_script( 'slick-min-js', get_stylesheet_directory_uri() . '/js/slick.min.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'load_scripts' );
//
// Your code goes below
//
//Enquee script //
//end//*/
add_action('init', 'romanov_antiques_post_type');
function romanov_antiques_post_type() {
    // Register Antiques
    $products_labels = array(
        'name'               => 'Antiques',
        'singular_name'      => 'Antique',
        'menu_name'          => 'Antiques'
        );
    $products_args = array(
        'labels'             => $products_labels,
        'public'             => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'taxonomies'         => array('post_tag','category'),
        'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
        'rewrite' => array(
            'slug' => 'antique'
            )
        );
    register_post_type('Antiques', $products_args);
}
if( ! function_exists('romanov_antiques_archive') ){
    function romanov_antiques_archive( $query = false, $style = false ){
        global $wp_query;
        global $post;
        $antique_status = $antique_price = '';
        $output = '';
        
        $translate['readmore']      = mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
        $translate['client']        = mfn_opts_get('translate') ? mfn_opts_get('translate-client','Client') : __('Client','betheme');
        $translate['date']          = mfn_opts_get('translate') ? mfn_opts_get('translate-date','Date') : __('Date','betheme');
        $translate['website']       = mfn_opts_get('translate') ? mfn_opts_get('translate-website','Website') : __('Website','betheme');
        $translate['view']          = mfn_opts_get('translate') ? mfn_opts_get('translate-view','View website') : __('View website','betheme');
        
        if( ! $query ) $query = $wp_query;
        if( ! $style ){
            if( $_GET && key_exists('mfn-p', $_GET) ){
                $style = $_GET['mfn-p']; // demo
            } else {
                $style = mfn_opts_get( 'portfolio-layout', 'grid' );
            }
        }
        $image_arr = array();

        $loop = 0;
        if ( $query->have_posts() ){
            while ( $query->have_posts() ){
                $query->the_post();
                
                $item_class = array();
                $categories = '';
                $special_class = '';
                
                $custom_fields = get_post_meta(get_the_id());


                $loop ++;
                // echo "<pre>";
                // set_time_limit(0);
                // $post_2 = get_post( get_the_id() );
                // print_r( $post_2->post_date );
                // $cenvertedTime = date('Y-m-d H:i:s',strtotime('-' . (string)$loop . ' minute',strtotime($post_2->post_date)));
                // echo "  -- ". $loop." --   ";
                // print_r( $cenvertedTime );
                //  $my_post = array(  
                //     'ID'            => get_the_id(),  
                //     'post_date'     => $cenvertedTime,  
                //     'post_date_gmt'     => $cenvertedTime  
                // );  
              
                // // Update the post into the database  
                // // wp_update_post( $my_post );  
                // echo "</pre>";

                if (array_key_exists('status', $custom_fields)) {
                    $antique_status = $custom_fields['status'][0];
                }   
                if (array_key_exists('price', $custom_fields)) {
                    $antique_price = $custom_fields['price'][0];
                }
                $terms = get_the_terms(get_the_ID(),'portfolio-types');
                if( is_array( $terms ) ){
                    foreach( $terms as $term ){
                        $item_class[] = 'category-'. $term->slug;
                        $categories .= '<a href="'. site_url() .'/portfolio-types/'. $term->slug .'">'. $term->name .'</a>, ';
                    }
                    $categories = substr( $categories , 0, -2 );
                }
                $item_class[] = get_post_meta( get_the_ID(), 'mfn-post-size', true );
                $item_class[] = has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail';
                $item_class = implode(' ', $item_class);
                
                // full width sections for list style
                if( $item_bg = get_post_meta( get_the_ID(), 'mfn-post-bg', true ) ){
                    $item_bg = 'style="background-image:url('. $item_bg .');"';
                }
                
                $external           = mfn_opts_get( 'portfolio-external' );
                $ext_link           = get_post_meta( get_the_ID(), 'mfn-post-link', true );
                $large_image_url    = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
                 array_push($image_arr, get_post_thumbnail_id( get_the_ID() ) );
                // Image Link ---------------------------------------------------------------------
                
                if( in_array( $external, array('disable','popup') ) ){
                    // disable details & link popup 
                    $link_before    = '<a class="link" href="'. $large_image_url[0] .'" rel="prettyphoto">';
                } elseif( $external && $ext_link ){
                    // link to project website
                    $link_before    = '<a class="link" href="'. $ext_link .'" target="'. $external .'">';
                } else {
                    // link to project details
                    $link_before    = '<a class="link" href="'. get_permalink() .'">';
                }
                
                
                // Echo ---------------------------------------------------------------------------
                
                $output .= '<li class="portfolio-item isotope-item '. $item_class .'">';
                
                if( $style == 'masonry-hover' ){
                        // style: Masonry Hover ---------------------------------------------------
                    $output .= '<div class="masonry-hover-wrapper">';   
                            // desc -------------------
                    $bg_color = get_post_meta( get_the_ID(), 'mfn-post-bg-hover', true );
                    if( $bg_color ){
                        $bg_color = 'style="background-color:'. $bg_color .';"';
                    }
                    $output .= '<div class="hover-desc" '. $bg_color .'>';
                    $output .= '<div class="desc-inner">';
                    $output .= '<h3 class="entry-title" itemprop="headline">'. $link_before . get_the_title() .'</a></h3>';
                    $output .= '<p class="antique_archive_title column one-second" style="margin-bottom: 30px;">' . $antique_status . '</p>';
                    $output .= '<p class="antique_archive_title column one-second" style="margin-bottom: 30px;">';
                    if ( $antique_price !== 'P.O.R' && $antique_price !== 'P.O.R.' ) {
                        $output .= '&#36;'; 
                    }
                    $output .= $antique_price . '</p>';
                    $output .= '<div class="desc-wrappper">';
                    $output .= get_the_excerpt();
                    $output .= '</div>';
                    $output .= '</div>';
                    if( $external != 'disable' ){
                        $output .= '<div class="links-wrappper clearfix">';
                        if( ! in_array( $external, array('_self','_blank') ) )  $output .= '<a class="zoom" href="'. $large_image_url[0] .'" rel="prettyphoto"><i class="icon-search"></i></a>';
                        if( $ext_link ) $output .= '<a class="external" target="_blank" href="'. $ext_link .'" ><i class="icon-forward"></i></a>';
                        if( ! $external )  $output .= $link_before. '<i class="icon-link"></i></a>';
                        $output .= '</div>';
                    }
                    $output .= '</div>';
                            // photo ------------------
                    $output .= '<div class="image-wrapper scale-with-grid">';
                    $output .= $link_before;
                    $output .= get_the_post_thumbnail( get_the_ID(), 'related_article_size_small', array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
                    $output .= '</a>';
                    $output .= '</div>';
                    $output .= '</div>';
                } else {
                        // style: All -------------------------------------------------------------
                    $output .= '<div class="portfolio-item-fw-bg" '. $item_bg .'>';
                    $output .= '<div class="portfolio-item-fill"></div>';
                    $output .= '<div class="portfolio-item-fw-wrapper">';
                                // style: List | Desc ---------------------------------------------
                    $output .= '<div class="list_style_header">';
                    $output .= '<h3 class="entry-title" itemprop="headline">'. $link_before . get_the_title() .'</a></h3>';
                    $output .= '<div class="links_wrapper">';
                    $output .= '<a href="#" class="button button_js portfolio_prev_js"><span class="button_icon"><i class="icon-up-open"></i></span></a>';
                    $output .= '<a href="#" class="button button_js portfolio_next_js"><span class="button_icon"><i class="icon-down-open"></i></span></a>';
                    $output .= '<a href="'. get_permalink() .'" class="button button_left button_theme button_js"><span class="button_icon"><i class="icon-link"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
                    $output .= '</div>';
                    $output .= '</div>';
                                // style: All | Photo ---------------------------------------------
                    $output .= '<div class="image_frame scale-with-grid">';
                    $output .= '<div class="image_wrapper">'; 
                    // $output .= 'nikhil'; 
                    $output .= $link_before;
                    $output .= get_the_post_thumbnail( $post->ID, 'related_article_size_small' , array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
                    // $output .= mfn_post_thumbnail( get_the_ID(), 'related_article_size_2x', $style );
                    $output .= '</a>';
                    $output .= '</div>';
                    $output .= '</div>';
                                // style: All | Desc ----------------------------------------------
                    $output .= '<div class="desc">';
                    $output .= '<div class="title_wrapper">';
                    $output .= '<h5 class="entry-title" itemprop="headline">'. $link_before . get_the_title() .'</a></h5>';  
                   /* $output .= '<div class="view-item-section">';
                    $output .= $link_before;
                    $output .= '<div class="view-item-text">View Item</div>';
                    $output .= '</a>';
                    $output .= '</div>';*/
                    if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
                        $special_class = 'sale-status';
                    }
                    $output .= '<div class="status-main-parent">';
                    $output .= '<p class="antique_archive_title column one-second status-title-section ' .$special_class. '" style="margin-bottom: 30px;">' . $antique_status . '</p>';
                    if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
                        // $output .= '<p class="antique_archive_title column one-second sale-status" style="margin-bottom: 30px;"><i class="icon-doc-text"></i>' . $antique_status . '</p>';
                        $output .= '<p class="antique_archive_title column one-second status-price-section" style="margin-bottom: 30px;">';
                        if ( $antique_price !== 'P.O.R' && $antique_price !== 'P.O.R.') {
                            $output .= '&#36;'; 
                        }
                        $output .= $antique_price . '</p>';
                        
                    }
                    
                    $output .= '<div class="button-love">'. mfn_love() .'</div>';
                    $output .= '</div>';
                    $output .= '</div>';

                    $output .= '<div class="view-item-section">';
                    $output .= $link_before;
                    $output .= '<div class="view-item-text">View Item</div>';
                    $output .= '</a>';
                    $output .= '</div>';

                    $output .= '<div class="details-wrapper">';
                    $output .= '<dl>';
                    if( $client = get_post_meta( get_the_ID(), 'mfn-post-client', true ) ){
                        $output .= '<dt>'. $translate['client'] .'</dt>';
                        $output .= '<dd>'. $client .'</dd>';
                    }
                    $output .= '<dt>'. $translate['date'] .'</dt>';
                    $output .= '<dd>'. get_the_date() .'</dd>';
                    if( $link = get_post_meta( get_the_ID(), 'mfn-post-link', true ) ){
                        $output .= '<dt>'. $translate['website'] .'</dt>';
                        $output .= '<dd><a target="_blank" href="'. $link .'"><i class="icon-forward"></i>'. $translate['view'] .'</a></dd>';
                    }
                    $output .= '</dl>';
                    $output .= '</div>';
                    $output .= '<div class="desc-wrapper">';
                    $output .= get_the_excerpt();
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
                $output .= '</li>';
                
            }
        }
        
        // echo "<pre>";
        // print_r( $loop );
        // echo "</pre>";
        return $output;
    }
}
function searchfilter($query) {
    if ($query->is_search && !is_admin() ) {
        $query->set('Antiques',array('post','page'));
    }
    return $query;
}
add_filter('pre_get_posts','searchfilter');
add_shortcode( 'antiques-search', 'search_filter_result' );   
function search_filter_result( ) { ?>
<div class="ubermenu-search">
    <form role="search" method="get" class="ubermenu-searchform" action="http://romanov.staging.wpengine.com/">
        <input type="text" placeholder="Search..." value="" name="s" class="ubermenu-search-input">
        <input type="hidden" name="post_type" value="antiques" />
        <input type="submit" class="ubermenu-search-submit" value="">
    </form>
</div>
<?php } ?>
<?php

add_shortcode( 'get_related_posts', 'imedica_related_posts' );
// Realted Posts
if ( ! function_exists( 'imedica_related_posts' ) ) :
    function imedica_related_posts( $post ) {
		$rel = get_field( 'related_posts', $post->ID );
    	$portfolio_classes  = 'grid col-4';

        //Check that we have non-sold related posts 		    
        $have_non_sold_posts = false;
		if ( isset( $rel ) && !empty( $rel ) ) {
            foreach ( $rel as $r ) {
				$custom_fields = get_post_meta($r->ID);
				if (array_key_exists('status', $custom_fields)) {
					$antique_status = $custom_fields['status'][0];
					if ( $antique_status !== 'Sold' ) {
					    $have_non_sold_posts = true;
					}
				}   
            }
        }
        
		if ( isset( $rel ) && !empty( $rel ) && $have_non_sold_posts ) {
		?>
        <div class="related-posts">
            <h2 class="related-posts-title"><?php _e( "Related Items", "imedica" ); ?></h2>
            <?php
            //for use in the loop, list 4 post titles related to first tag on current post
			$rel = get_field( 'related_posts', $post->ID );
			//echo '<xmp>'; print_r($rel); echo '</xmp>';
			$output = '';
				//$output .= '<div class="rotate-cls">';
                $output .= '<div class="lm_wrapper rotate-cls '. $portfolio_classes .'">';
                foreach ( $rel as $r ) {
					$item_class = array();
					$categories = '';
					$special_class = '';
					
					$custom_fields = get_post_meta($r->ID);
					if (array_key_exists('status', $custom_fields)) {
						$antique_status = $custom_fields['status'][0];
						if ( $antique_status === 'Sold' ) 
						    continue;
					}   
					if (array_key_exists('price', $custom_fields)) {
						$antique_price = $custom_fields['price'][0];
					}
					$terms = get_the_terms($r->ID,'portfolio-types');
					if( is_array( $terms ) ){
						foreach( $terms as $term ){
							$item_class[] = 'category-'. $term->slug;
							$categories .= '<a href="'. site_url() .'/portfolio-types/'. $term->slug .'">'. $term->name .'</a>, ';
						}
						$categories = substr( $categories , 0, -2 );
					}
					$item_class[] = get_post_meta( $r->ID, 'mfn-post-size', true );
					$item_class[] = has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail';
					$item_class = implode(' ', $item_class);
					
					// full width sections for list style
					if( $item_bg = get_post_meta( $r->ID, 'mfn-post-bg', true ) ){
						$item_bg = 'style="background-image:url('. $item_bg .');"';
					}
					
					$external           = mfn_opts_get( 'portfolio-external' );
					$ext_link           = get_post_meta( $r->ID, 'mfn-post-link', true );
					$large_image_url    = wp_get_attachment_image_src( get_post_thumbnail_id( $r->ID ), 'large' );
					
					// Image Link ---------------------------------------------------------------------
					
					if( in_array( $external, array('disable','popup') ) ){
						// disable details & link popup 
						$link_before    = '<a class="link" href="'. $large_image_url[0] .'" rel="prettyphoto">';
					} elseif( $external && $ext_link ){
						// link to project website
						$link_before    = '<a class="link" href="'. $ext_link .'" target="'. $external .'">';
					} else {
						// link to project details
						$link_before    = '<a class="link" href="'. get_permalink($r->ID) .'">';
					}
                    //$the_query->the_post();
					$output .= '<div class="portfolio-item isotope-item '. $item_class .'">';
                
							// style: All -------------------------------------------------------------
					$output .= '<div class="portfolio-item-fw-bg" '. $item_bg .'>';
					$output .= '<div class="portfolio-item-fill"></div>';
					$output .= '<div class="portfolio-item-fw-wrapper">';
								// style: List | Desc ---------------------------------------------
					$output .= '<div class="list_style_header">';
					$output .= '<h3 class="entry-title" itemprop="headline">'. $link_before . get_the_title( $r->ID ) .'</a></h3>';
					$output .= '<div class="links_wrapper">';
					$output .= '<a href="#" class="button button_js portfolio_prev_js"><span class="button_icon"><i class="icon-up-open"></i></span></a>';
					$output .= '<a href="#" class="button button_js portfolio_next_js"><span class="button_icon"><i class="icon-down-open"></i></span></a>';
					$output .= '<a href="'. get_permalink($r->ID) .'" class="button button_left button_theme button_js"><span class="button_icon"><i class="icon-link"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
					$output .= '</div>';
					$output .= '</div>';
								// style: All | Photo ---------------------------------------------
					$output .= '<div class="image_frame scale-with-grid">';
					$output .= '<div class="image_wrapper">';       
					$output .= $link_before;
                    $output .= get_the_post_thumbnail( $r->ID, 'related_article_size_small' , array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
                    // $output .= mfn_post_thumbnail( get_the_ID(), 'related_article_size_2x', $style );
                    $output .= '</a>';
					$output .= '</div>';
					$output .= '</div>';
								// style: All | Desc ----------------------------------------------
					$output .= '<div class="desc">';
					$output .= '<div class="title_wrapper">';
					$output .= '<h5 class="entry-title" itemprop="headline">'. $link_before . get_the_title( $r->ID ) .'</a></h5>';  
			/*		$output .= '<div class="view-item-section">';
					$output .= $link_before;
					$output .= '<div class="view-item-text">View Item</div>';
					$output .= '</a>';
					$output .= '</div>';*/
					if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
						$special_class = 'sale-status';
					}
					$output .= '<div class="status-main-parent">';
					$output .= '<p class="antique_archive_title column one-second status-title-section ' .$special_class. '" style="margin-bottom: 30px;">' . $antique_status . '</p>';
					if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
						// $output .= '<p class="antique_archive_title column one-second sale-status" style="margin-bottom: 30px;"><i class="icon-doc-text"></i>' . $antique_status . '</p>';
						$output .= '<p class="antique_archive_title column one-second status-price-section" style="margin-bottom: 30px;">';
						if ( $antique_price !== 'P.O.R' && $antique_price !== 'P.O.R.' ) {
							$output .= '&#36;'; 
						}
						$output .= $antique_price . '</p>';
						
					}
					
					$output .= '<div class="button-love">'. mfn_love() .'</div>';
					$output .= '</div>';
					$output .= '</div>';

                    $output .= '<div class="view-item-section">';
                    $output .= $link_before;
                    $output .= '<div class="view-item-text">View Item</div>';
                    $output .= '</a>';
                    $output .= '</div>';
                    
					$output .= '<div class="details-wrapper">';
					$output .= '<dl>';
					if( $client = get_post_meta( $r->ID, 'mfn-post-client', true ) ){
						$output .= '<dt>'. $translate['client'] .'</dt>';
						$output .= '<dd>'. $client .'</dd>';
					}
					$output .= '<dt>'. $translate['date'] .'</dt>';
					$output .= '<dd>'. $r->post_date .'</dd>';
					if( $link = get_post_meta( $r->ID, 'mfn-post-link', true ) ){
						$output .= '<dt>'. $translate['website'] .'</dt>';
						$output .= '<dd><a target="_blank" href="'. $link .'"><i class="icon-forward"></i>'. $translate['view'] .'</a></dd>';
					}
					$output .= '</dl>';
					$output .= '</div>';
					$output .= '<div class="desc-wrapper">';
					$output .= $r->post_excerpt;
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					
					
					$output .= '</div>';
                }
				//$output .= $output;
                //$output .= '</ul>';
				$output .= '</div>';
				$output .= '<script>
						jQuery(document).ready(function(){
  							jQuery(".rotate-cls").slick({
    							slidesToShow : 4,
								slidesToScroll : 1,
								prevArrow : \'<button type="button" class="slick-prev">&lt;</button>\',
								nextArrow : \'<button type="button" class="slick-next">&gt;</button>\',
								responsive: [
								    {
								        breakpoint: 1024,
								        settings: {
								        slidesToShow: 3,
								        slidesToScroll: 1
								      }
								    },
                                    {
                                      breakpoint:769,
                                      settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                      }
                                    },
                                    {
                                      breakpoint:767,
                                      settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                      }
                                    },
								    {
								      breakpoint: 600,
								      settings: {
								        slidesToShow: 1,
								        slidesToScroll: 1
								      }
								    },
								    {
								      breakpoint: 480,
								      settings: {
								        slidesToShow: 1,
								        slidesToScroll: 1
								      }
								    }
								  ]
  							});
						});
					</script>';
			echo $output;
                       
            ?>
        </div> <!-- .related-posts -->
        <?php
		} 
    }
endif; // Realted Posts

if ( ! function_exists( 'responsive_related_posts' ) ) :
    function responsive_related_posts( $post, $class_to_apply, $class_to_hide, $slides_to_show ) {
		$rel = get_field( 'related_posts', $post->ID );
    	$portfolio_classes  = 'grid col-4';
        $translate['readmore']      = mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
        $translate['client']        = mfn_opts_get('translate') ? mfn_opts_get('translate-client','Client') : __('Client','betheme');
        $translate['date']          = mfn_opts_get('translate') ? mfn_opts_get('translate-date','Date') : __('Date','betheme');
        $translate['website']       = mfn_opts_get('translate') ? mfn_opts_get('translate-website','Website') : __('Website','betheme');
        $translate['view']          = mfn_opts_get('translate') ? mfn_opts_get('translate-view','View website') : __('View website','betheme');

        //Check that we have non-sold related posts 		    
        $have_non_sold_posts = false;
		if ( isset( $rel ) && !empty( $rel ) ) {
            foreach ( $rel as $r ) {
				$custom_fields = get_post_meta($r->ID);
				if (array_key_exists('status', $custom_fields)) {
					$antique_status = $custom_fields['status'][0];
					if ( $antique_status !== 'Sold' ) {
					    $have_non_sold_posts = true;
					}
				}   
            }
        }
        
		if ( isset( $rel ) && !empty( $rel ) && $have_non_sold_posts ) {
		?>
        <div class="related-posts <?php echo $class_to_hide; ?>">
            <h2 class="related-posts-title"><?php _e( "Related Items", "imedica" ); ?></h2>
            <?php
            //for use in the loop, list 4 post titles related to first tag on current post
			$rel = get_field( 'related_posts', $post->ID );
			//echo '<xmp>'; print_r($rel); echo '</xmp>';
			$output = '';
				//$output .= '<div class="rotate-cls">';
                $output .= '<div class="lm_wrapper rotate-cls ' .  $class_to_apply . ' ' . $portfolio_classes .'">';
                foreach ( $rel as $r ) {
					$item_class = array();
					$categories = '';
					$special_class = '';
					$antique_status = '';
					$antique_price = '';
					$item_bg = '';
					
					$custom_fields = get_post_meta($r->ID);
					if (array_key_exists('status', $custom_fields)) {
						$antique_status = $custom_fields['status'][0];
						if ( $antique_status === 'Sold' ) 
						    continue;
					}	  
					if (array_key_exists('price', $custom_fields)) {
						$antique_price = $custom_fields['price'][0];
					}
					$terms = get_the_terms($r->ID,'portfolio-types');
					if( is_array( $terms ) ){
						foreach( $terms as $term ){
							$item_class[] = 'category-'. $term->slug;
							$categories .= '<a href="'. site_url() .'/portfolio-types/'. $term->slug .'">'. $term->name .'</a>, ';
						}
						$categories = substr( $categories , 0, -2 );
					}
					$item_class[] = get_post_meta( $r->ID, 'mfn-post-size', true );
					$item_class[] = has_post_thumbnail($r->ID) ? 'has-thumbnail' : 'no-thumbnail';
					$item_class = implode(' ', $item_class);
					
					// full width sections for list style
					if( $item_bg = get_post_meta( $r->ID, 'mfn-post-bg', true ) ){
						$item_bg = 'style="background-image:url('. $item_bg .');"';
					}
					
					$external           = mfn_opts_get( 'portfolio-external' );
					$ext_link           = get_post_meta( $r->ID, 'mfn-post-link', true );
					$large_image_url    = wp_get_attachment_image_src( get_post_thumbnail_id( $r->ID ), 'large' );
					
					// Image Link ---------------------------------------------------------------------
					
					if( in_array( $external, array('disable','popup') ) ){
						// disable details & link popup 
						$link_before    = '<a class="link" href="'. $large_image_url[0] .'" rel="prettyphoto">';
					} elseif( $external && $ext_link ){
						// link to project website
						$link_before    = '<a class="link" href="'. $ext_link .'" target="'. $external .'">';
					} else {
						// link to project details
						$link_before    = '<a class="link" href="'. get_permalink($r->ID) .'">';
					}
                    //$the_query->the_post();
					$output .= '<div class="portfolio-item isotope-item '. $item_class .'">';
                
							// style: All -------------------------------------------------------------
					$output .= '<div class="portfolio-item-fw-bg" '. $item_bg .'>';
					$output .= '<div class="portfolio-item-fill"></div>';
					$output .= '<div class="portfolio-item-fw-wrapper">';
								// style: List | Desc ---------------------------------------------
					$output .= '<div class="list_style_header">';
					$output .= '<h3 class="entry-title" itemprop="headline">'. $link_before . get_the_title( $r->ID ) .'</a></h3>';
					$output .= '<div class="links_wrapper">';
					$output .= '<a href="#" class="button button_js portfolio_prev_js"><span class="button_icon"><i class="icon-up-open"></i></span></a>';
					$output .= '<a href="#" class="button button_js portfolio_next_js"><span class="button_icon"><i class="icon-down-open"></i></span></a>';
					$output .= '<a href="'. get_permalink($r->ID) .'" class="button button_left button_theme button_js"><span class="button_icon"><i class="icon-link"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
					$output .= '</div>';
					$output .= '</div>';
								// style: All | Photo ---------------------------------------------
					$output .= '<div class="image_frame scale-with-grid">';
					$output .= '<div class="image_wrapper">';       
					$output .= $link_before;
                    $output .= get_the_post_thumbnail( $r->ID, 'related_article_size_small' , array( 'class'=>'scale-with-grid', 'itemprop'=>'image' ) );
                    // $output .= mfn_post_thumbnail( get_the_ID(), 'related_article_size_2x', $style );
                    $output .= '</a>';
					$output .= '</div>';
					$output .= '</div>';
								// style: All | Desc ----------------------------------------------
					$output .= '<div class="desc">';
					$output .= '<div class="title_wrapper">';
					$output .= '<h5 class="entry-title" itemprop="headline">'. $link_before . get_the_title( $r->ID ) .'</a></h5>';  
			/*		$output .= '<div class="view-item-section">';
					$output .= $link_before;
					$output .= '<div class="view-item-text">View Item</div>';
					$output .= '</a>';
					$output .= '</div>';*/
					if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
						$special_class = 'sale-status';
					}
					$output .= '<div class="status-main-parent">';
					$output .= '<p class="antique_archive_title column one-second status-title-section ' .$special_class. '" style="margin-bottom: 30px;">' . $antique_status . '</p>';
					if ( $antique_status !== 'Sold' && $antique_price !== '' ) { 
						// $output .= '<p class="antique_archive_title column one-second sale-status" style="margin-bottom: 30px;"><i class="icon-doc-text"></i>' . $antique_status . '</p>';
						$output .= '<p class="antique_archive_title column one-second status-price-section" style="margin-bottom: 30px;">';
						if ( $antique_price !== 'P.O.R' && $antique_price !== 'P.O.R.' ) {
							$output .= '&#36;'; 
						}
						$output .= $antique_price . '</p>';
						
					}
					
					$output .= '<div class="button-love">'. mfn_love() .'</div>';
					$output .= '</div>';
					$output .= '</div>';

                    $output .= '<div class="view-item-section">';
                    $output .= $link_before;
                    $output .= '<div class="view-item-text">View Item</div>';
                    $output .= '</a>';
                    $output .= '</div>';
                    
					$output .= '<div class="details-wrapper">';
					$output .= '<dl>';
					if( $client = get_post_meta( $r->ID, 'mfn-post-client', true ) ){
						$output .= '<dt>'. $translate['client'] .'</dt>';
						$output .= '<dd>'. $client .'</dd>';
					}
					$output .= '<dt>'. $translate['date'] .'</dt>';
					$output .= '<dd>'. $r->post_date .'</dd>';
					if( $link = get_post_meta( $r->ID, 'mfn-post-link', true ) ){
						$output .= '<dt>'. $translate['website'] .'</dt>';
						$output .= '<dd><a target="_blank" href="'. $link .'"><i class="icon-forward"></i>'. $translate['view'] .'</a></dd>';
					}
					$output .= '</dl>';
					$output .= '</div>';
					$output .= '<div class="desc-wrapper">';
					$output .= $r->post_excerpt;
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					
					
					$output .= '</div>';
                }
				//$output .= $output;
                //$output .= '</ul>';
				$output .= '</div>';
            $output .= '<script>
						jQuery(document).ready(function(){
  							jQuery(".' . $class_to_apply . '").slick({
    							slidesToShow : ' . $slides_to_show . ',
								slidesToScroll : 1,
								infinite: false,       // отключаем бесконечный скролл
                                adaptiveHeight: true,   
								prevArrow : \'<button type="button" class="slick-prev">&lt;</button>\',
								nextArrow : \'<button type="button" class="slick-next">&gt;</button>\',
								responsive: [
								    {
								        breakpoint: 1024,
								        settings: {
								        slidesToShow: 3,
								        slidesToScroll: 1
								      }
								    },
                                    {
                                      breakpoint:769,
                                      settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 1
                                      }
                                    },
                                    {
                                      breakpoint:767,
                                      settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                      }
                                    },
								    {
								      breakpoint: 600,
								      settings: {
								        slidesToShow: 1,
								        slidesToScroll: 1
								      }
								    },
								    {
								      breakpoint: 480,
								      settings: {
								        slidesToShow: 1,
								        slidesToScroll: 1
								      }
								    }
								  ]
  							});
						});
					</script>';
            echo $output;

            ?>
        </div> <!-- .related-posts -->
        <?php
		} 
    }
endif; // Realted Posts

?>
<?php

// The function that outputs the metabox html
function product_gallery_metabox() {
    global $post;
	
    // Here we get the current images ids of the gallery
    $values = get_post_custom($post->ID);
    if(isset($values['product_gallery'])) {
        // The json decode and base64 decode return an array of image ids
        //$ids = json_decode(base64_decode($values['product_gallery'][0]));
		$ids = unserialize($values['product_gallery'][0]);
    }
    else {
        $image_gallery_from_posts = get_image_gallery_by_attachments($post->ID);
		if( empty( $image_gallery_from_posts ) ) {
			
		}
        $ids = array();
    }
    wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce'); // Security
    // Implode the array to a comma separated list
    $cs_ids = ( !empty( $ids ) ) ? implode(",", $ids) : '';   
    // We display the gallery
	$id_code = (  $cs_ids == '' ) ? '' : 'ids="'.$cs_ids.'"';
	//echo '[gallery ' . $id_code . ']';
    $html  = do_shortcode( '[gallery ' . $id_code . ']');
    // Here we store the image ids which are used when saving the product
    $html .= '<input id="product_gallery_ids" type="hidden" name="product_gallery_ids" value="'.$cs_ids. '" />';
    // A button which we will bind to later on in JavaScript
    $html .= '<input id="manage_gallery" title="Manage gallery" type="button" value="Manage gallery" />';
    echo $html;
}
 
// A function that will add the metabox to the edit page
function add_product_gallery_metabox() { 
    // More info about arguments in the WP Codex
    add_meta_box(
        'product_gallery',          // Name of the box
        __('Gallery'),              // Title of the box
        'product_gallery_metabox',  // The metabox html function 
        'antiques',                  // SET TO THE POST TYPE WHERE THE METABOX IS SHOWN
        'normal',                   // Specifies where the box is shown
        'high'                      // Specifies where the box is shown
    ); 
}
 
// This function takes care of saving the metabox's value
function save_product_metaboxes($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['meta_box_nonce'])
        || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce'))
        return;
    // Check if user has right access level
    if (!current_user_can('edit_post', $post_id))
        return;
 
    // Check if data is in post
    if (isset($_POST['product_gallery_ids'])) {
        // Encode so it can be stored an retrieved properly
        //$encode = base64_encode(json_encode(explode(',',$_POST['product_gallery_ids'])));
        //update_post_meta($post_id, 'product_gallery', $encode);
		$arr = explode( ',', $_POST['product_gallery_ids'] );
		update_post_meta($post_id, 'product_gallery', $arr);
    }
}
 
// Hook these actions into Wordpress
add_action('add_meta_boxes', 'add_product_gallery_metabox');
add_action('save_post', 'save_product_metaboxes');


function register_admin_scripts() {
    wp_register_script('gallery-meta-box', get_stylesheet_directory_uri() . '/js/metabox-gallery.js');
    wp_enqueue_script(array('jquery', 'gallery-meta-box'));
}
add_action('admin_enqueue_scripts', 'register_admin_scripts');

// Remove query strings from urls
function imedica_remove_query_strings( $src ) {
    $src = remove_query_arg( array( 'v', 'ver', 'rev', 'bg_color', 'sensor' ), $src );
    return $src;
}
add_filter( 'style_loader_src', 'imedica_remove_query_strings', 10, 2 );
add_filter( 'script_loader_src', 'imedica_remove_query_strings', 10, 2 );


/* ---------------------------------------------------------------------------
 * Sticky post navigation
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_post_navigation' ) )
{
	function mfn_post_navigation( $post, $next_prev, $icon ){
		$output = '';
	
		if( is_object( $post ) ){
			// move this DOM element with JS
			$output .= '<a class="fixed-nav fixed-nav-'. $next_prev .' format-'. get_post_format( $post ) .'" href="'. get_permalink( $post ) .'">';
				
				$output .= '<span class="arrow"><i class="'. $icon .'"></i></span>';
				
				$output .= '<div class="photo">';
					$output .= get_the_post_thumbnail( $post->ID, 'blog-navi' );
				$output .= '</div>';
				
				$output .= '<div class="desc">';
					$output .= '<h6>'. get_the_title( $post ) .'</h6>';
					$output .= '<span class="date"><i class="icon-clock"></i>'. get_the_date(get_option('date_format'), $post->ID) .'</span>';
				$output .= '</div>';
				
			$output .= '</a>';
		}
	
		return $output;
	}
}
?>
