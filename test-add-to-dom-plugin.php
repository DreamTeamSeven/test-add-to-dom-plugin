<?php
/*
 * Plugin Name:       Test Add to Dom Plugin
 * Plugin URI:        https://github.com/DreamTeamSeven/test-add-to-dom-plugin
 * Description:       A plugin to test adding to the DOM
 * Version:           0.0.0
 * Requires at least: 6.7.1
 * Requires PHP:      8.3
 * Author:            Dream Team Seven
 * Author URI:        https://github.com/DreamTeamSeven
 * License:           No License
 * License URI:       https://choosealicense.com/no-permission
 * Update URI:        https://github.com/DreamTeamSeven/test-add-to-dom-plugin
 * Text Domain:       test-add-to-dom-plugin
 * Requires Plugins:  WooCommerce
 */

function my_custom_header_element()
{
    // Your custom header element code here
    echo '<p style="background-color: #f0f0f0; padding: 10px;">This is my custom header element!</p>';
}

function add_green_Shadow_to_product_image()
{
    global $product;

    // Check if we're on a product page
    if (is_product()) {
        ?>
        <style>
            .woocommerce-product-gallery__image {
                box-shadow: 0 0 10px 5px green;
            }
        </style>
        <?php
    }
}

// Hook to add circle buttons
function add_circle_buttons()
{
    global $product;

    // Check if we're on a product page
    if (is_product()) {
        ?>
        <div class="circle-buttons">
            <button class="circle-button green-button" id="green-border-button">Button 1</button>
        </div>
        <?php
    }
}

// Hook to enqueue circle button CSS
function enqueue_circle_button_css()
{
    wp_enqueue_style('circle-button-css', plugins_url('circle-button.css', __FILE__));
}
// Hook to enqueue circle button JS
function enqueue_circle_button_js()
{
    wp_enqueue_script('circle-button-js', plugins_url('circle-button.js', __FILE__), array('jquery'));
}

function replace_product_image()
{
    global $product;

    // Check if we're on a product page
    if (is_product()) {
        // Remove the existing product image
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

        // Output a new image with a green shadow
        ?>
        <div style="box-shadow: 0 0 10px 5px green; display: inline-block; padding: 10px;">
            <img src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>" />
        </div>
        <?php
    }
}

test_add_to_dom_plugin();

// Add actions
add_action('woocommerce_before_add_to_cart_button', 'add_circle_buttons');
add_action('wp_enqueue_scripts', 'enqueue_circle_button_css');
// add_action('wp_enqueue_scripts', 'enqueue_circle_button_js');

// Add shadow action

// Add action
add_action('woocommerce_before_single_product_summary', 'replace_product_image', 19);