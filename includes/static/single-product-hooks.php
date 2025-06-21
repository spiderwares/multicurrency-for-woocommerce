<?php 

if ( ! defined( 'ABSPATH' ) ) :
    exit; // Exit if accessed directly
endif;

return apply_filters( 'mcwc_get_single_hooks_option',
    array(
        'default' => array(
            'disable-0'                                  	=> esc_html__( 'Disable', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_before_single_product_summary-0'	=> esc_html__( 'Top of Product Page', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_product_thumbnails-0'           	=> esc_html__( 'Below Product Slider (May Not Work with Some Themes)', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_single_product_summary-0'       	=> esc_html__( 'Before Product Title', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_single_product_summary-6'       	=> esc_html__( 'After Product Title', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_before_add_to_cart_form-10'     	=> esc_html__( 'After Short Description', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_before_add_to_cart_quantity-10' 	=> esc_html__( 'Before Quantity Input Field', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_after_add_to_cart_quantity-10'  	=> esc_html__( 'After Quantity Input Field', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_before_add_to_cart_button-10'   	=> esc_html__( 'Before Add to Cart Button', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_after_add_to_cart_button-10'    	=> esc_html__( 'After Add to Cart Button', 'jewellery-price-breakup-for-woocommerce' ),
            'woocommerce_product_meta_end-10'            	=> esc_html__( 'After Product Meta Information', 'jewellery-price-breakup-for-woocommerce' ),
        ),
        'astra' 	=> array(
            'disable-0'                              		=> esc_html__( 'Disable', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_title_before-0'        		=> esc_html__( 'Before Title', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_title_after-0'         		=> esc_html__( 'After Title', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_price_before-0'        		=> esc_html__( 'Before Price', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_price_after-0'         		=> esc_html__( 'After Price', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_rating_before-0'       		=> esc_html__( 'Before Rating', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_rating_after-0'        		=> esc_html__( 'After Rating', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_add_to_cart_before-10'  		=> esc_html__( 'Before Add to Cart', 'jewellery-price-breakup-for-woocommerce' ),
            'astra_woo_single_add_to_cart_after-0'   		=> esc_html__( 'After Add to Cart', 'jewellery-price-breakup-for-woocommerce' ),
        ), 
    )
);