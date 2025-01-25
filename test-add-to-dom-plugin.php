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

//Add 20px b4 the buttons

// Add buttons
function add_buttons()
{
    global $product;

    if (is_product()) {

        ?>
        <div>
            <h3>Choose a color:</h3>
            <div>
                <button class="circle-button green-button" id="green-border-button"></button>
                <button class="circle-button red-button" id="red-border-button"></button>
                <button class="circle-button blue-button" id="blue-border-button"></button>
            </div>
            <h3>Choose a texture:</h3>
            <div class="circle-buttons-container">
                <button class="circle-button wood-button" id="wood-border-button"></button>
                <button class="circle-button metal-button" id="metal-border-button"></button>
                <button class="circle-button plastic-button" id="plastic-border-button"></button>
            </div>
            <br />
        </div>
        <?php

    }
}

// Enqueue buttons CSS
function enqueue_buttons_css()
{
    wp_enqueue_style('circle-button-css', plugins_url('buttons.css', __FILE__));
}

function enqueue_model_viewer_script()
{
    // Enqueue the model-viewer script with correct type=module
    echo '<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>';
}

function add_3d_model_viewer()
{
    ?>
    <div id="model-viewer-container">
        <h1>3D Model Viewer</h1>
        <model-viewer id="model-viewer" src="https://modelviewer.dev/shared-assets/models/Astronaut.glb"
            alt="A 3D model of an astronaut" auto-rotate camera-controls ar></model-viewer>
    </div>
    <?php
}

function test_add_to_dom_plugin()
{
    $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';

    if (
        in_array($plugin_path, wp_get_active_and_valid_plugins())
        || in_array($plugin_path, wp_get_active_network_plugins())
    ) {
        // Add actions
        add_action('woocommerce_before_add_to_cart_form', 'add_buttons');
        add_action('wp_enqueue_scripts', 'enqueue_buttons_css');
        add_action('wp_enqueue_scripts', 'enqueue_model_viewer_script');
        add_action('woocommerce_single_product_summary', 'add_3d_model_viewer');

        //added for testing - cor --------------------------------
        <?php
        if (!defined('ABSPATH')) {
            exit;
        }
        
        function enqueue_jquery_for_woocommerce() {
            if (is_product()) { 
                wp_enqueue_script('jquery');
            }
        }
        add_action('wp_enqueue_scripts', 'enqueue_jquery_for_woocommerce');
        
        add_action('wp_footer', 'custom_jquery_script', 20);
        
        function custom_jquery_script() {
            if (is_product()) { 
                ?>
                <script>
                    jQuery(document).ready(function($) {
                        $('.flex-control-thumbs').append('<li><div style="background: red; width: 100px; height: 100px;"></div></li>');
                    });
                </script>
                <?php
            }
        }
        
        // -------------------------------------------------------
        
    }
}

test_add_to_dom_plugin();





