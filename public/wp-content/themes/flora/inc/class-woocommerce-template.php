<?php
    
if( ! defined( 'ABSPATH' ) ) {
    die;
}

if( ! class_exists( 'Wyde_WooCommerce_Template' ) ) {

    class Wyde_WooCommerce_Template
    {

    	function __construct() {

            add_filter( 'woocommerce_show_page_title', array( $this, 'shop_title'), 10 );

            add_filter( 'woocommerce_breadcrumb_home_url', array( $this, 'shop_page_url') );
            
            remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
            add_action( 'woocommerce_before_main_content', array( $this, 'shop_breadcrumb'), 20 );

            add_action( 'woocommerce_before_cart', array( $this, 'shop_breadcrumb'), 1 );
            add_action( 'woocommerce_before_checkout_form', array( $this, 'shop_breadcrumb'), 1 );
            
            remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    		add_action( 'woocommerce_before_main_content', array( $this, 'before_container' ), 10 );
    		add_action( 'woocommerce_after_main_content', array( $this, 'after_container' ), 10 );

            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
            add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'before_shop_loop_item_title' ), 1 );
            add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

            remove_action( 'woocommerce_sidebar' , 'woocommerce_get_sidebar', 10 );
            add_action( 'woocommerce_sidebar', array($this, 'add_sidebar'), 10);

            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
            add_action( 'woocommerce_after_single_product_summary', array($this, 'upsell_display'), 15 );

            remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
            //add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
            

            add_filter( 'woocommerce_thankyou_order_received_text', array($this, 'order_received_text'));

        }

        function shop_title() {
			return false;
		}

        function shop_page_url(){
            $shop_id = woocommerce_get_page_id( 'shop' );
            if( $shop_id == -1 ){
                if( get_option('show_on_front')  == 'page' ){
                    $shop_id = get_option('page_on_front');
                }
            } 
            return get_permalink( $shop_id );
        }

        function shop_breadcrumb( $args = array() ){
            if(is_shop() && !is_product_category()) return;

            $args = wp_parse_args( $args, apply_filters( 'woocommerce_breadcrumb_defaults', array(
			    'delimiter'   => '<i class="flora-icon-right"></i>',
			    'wrap_before' => '<nav class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			    'wrap_after'  => '</nav>',
			    'before'      => '',
			    'after'       => '',
			    'home'        => __( 'Shop', 'flora' )
		    ) ) );

		    $breadcrumbs = new WC_Breadcrumb();

		    if ( $args['home'] && (is_product_category() || is_single() || is_cart() || is_checkout() || is_account_page() )) {
			    $breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
		    }

		    $args['breadcrumb'] = $breadcrumbs->generate();

		    wc_get_template( 'global/breadcrumb.php', $args );
        }
	
        function before_container() {
            global $wyde_page_id, $wyde_options, $wyde_page_header, $wyde_title_area, $wyde_sidebar_position;
            ?>
            <div id="content">
                <?php 
                wyde_page_title(); 

                $wyde_page_layout = 'boxed';

                if( is_single() ){
                    $wyde_sidebar_position = $wyde_options['shop_single_sidebar']; 
                }else{
                    $wyde_sidebar_position = get_post_meta( $wyde_page_id, '_w_sidebar_position', true );
                }
                
                if( empty($wyde_sidebar_position) ){
                    $wyde_sidebar_position = '3';
                }

                if( isset($_GET['sidebar']) && $_GET['sidebar'] == 'false') $wyde_sidebar_position = '1';//For Demo Only

                ?>
                <div class="<?php echo wyde_get_layout_class(); ?>">    
                    <?php wyde_page_background(); ?>
                    <div class="post-content container">
                        <?php 
                        if($wyde_sidebar_position == '2' ){
                             wyde_sidebar('shop');
                        }
                        ?>
                        <div class="<?php echo wyde_get_main_class(); ?>">       
        <?php
		}

		function after_container() {
        ?>
                                </div>
        <?php
		}

        function add_sidebar(){
            global $wyde_sidebar_position;
            if($wyde_sidebar_position == '3'){
                wyde_sidebar('shop'); 
            }
            ?>
                        </div>
                    </div>
                </div>
        <?php
        }

        function woocommerce_get_product_thumbnail( $size = 'shop_catalog' ) {
            global $product, $woocommerce_loop;
            if( isset($woocommerce_loop['columns']) && intval($woocommerce_loop['columns']) < 3 ){
                $size = 'large';
            }
            ?>
            <div class="cover-image">   
                <a href="<?php the_permalink(); ?>">
                <?php 
                    if ( has_post_thumbnail() ) {
                        echo get_the_post_thumbnail( get_the_ID(), $size );
                    } elseif ( wc_placeholder_img_src() ) {
                        echo wc_placeholder_img( $size );
                    }
                ?>
                <?php
                $attachment_ids = $product->get_gallery_attachment_ids();
                foreach ( $attachment_ids as $attachment_id ) {
                    echo wp_get_attachment_image( $attachment_id, $size );
                }
                ?>
                </a>
            </div>
            <?php
	    }

        function before_shop_loop_item_title(){
            $this->woocommerce_get_product_thumbnail();  
        }

        function upsell_display(){
           global $wyde_options;
           woocommerce_upsell_display(intval( $wyde_options['related_product_items'] ), intval( $wyde_options['related_product_columns'] ));
        }

        function order_received_text($message){
            return '<span class="order-received-text">'.$message.'</span>';
        }
    }

}

new Wyde_WooCommerce_Template();

function wyde_woocommerce_dropdown_menu(){
    global $woocommerce;
    $menu_content= sprintf ('<li class="menu-item-shop align-right"><a href="%1$s"><i class="flora-icon-cart"></i><span class="cart-items%5$s">%2$s</span></a><ul class="menu-cart"><li class="clear"><span class="view-cart"><a href="%1$s" class="w-ghost-button"><i class="flora-icon-cart"></i>%3$s</a></span><span class="total">Total:%4$s</span></li></ul></li>', esc_url( $woocommerce->cart->get_cart_url() ), $woocommerce->cart->cart_contents_count, __('Cart', 'flora'), $woocommerce->cart->get_cart_total(), ($woocommerce->cart->cart_contents_count > 0? '':' empty'));
    return $menu_content;
}

function wyde_dropdown_add_to_cart_fragment( $fragments ) {
	$fragments['.menu-item-shop'] = wyde_woocommerce_dropdown_menu();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'wyde_dropdown_add_to_cart_fragment');

function wyde_woocommerce_menu(){
    global $woocommerce;
    $menu_content= sprintf ('<li class="menu-item-cart"><a href="%1$s"><i class="flora-icon-cart"></i>%2$s<span class="cart-items%3$s">%4$s</span></a></li>', esc_url( $woocommerce->cart->get_cart_url() ), __('My Cart', 'flora'), ( $woocommerce->cart->cart_contents_count > 0 ? '':' empty'), $woocommerce->cart->cart_contents_count );
    return $menu_content;
}

function wyde_add_to_cart_fragment( $fragments ) {
	$fragments['.menu-item-cart'] = wyde_woocommerce_menu();
	return $fragments;
}
add_filter('add_to_cart_fragments', 'wyde_add_to_cart_fragment');

// Change number or products per page
function wyde_products_per_page(){
    global $wyde_options;
    return intval( $wyde_options['shop_product_items'] );
}
add_filter( 'loop_shop_per_page', 'wyde_products_per_page', 20 );

// Change number or products per row
function wyde_shop_columns() {
    global $wyde_options;
    return intval( $wyde_options['shop_product_columns'] );
}
add_filter('loop_shop_columns', 'wyde_shop_columns');

// Related Products
function wyde_related_products_args( $args ) {
    global $wyde_options;
	$args['posts_per_page'] = intval( $wyde_options['related_product_items'] ); 
	$args['columns'] =  intval( $wyde_options['related_product_columns'] ); 
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'wyde_related_products_args' );