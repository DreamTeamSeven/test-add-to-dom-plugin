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

// Add buttons
function add_buttons()
{
    global $product;

    if (is_product()) {

        ?>
        <div>
            <h3>Choose a color:</h3>
            <div>
                <button class="circle-button green-button" id="green-border-button" data-color="green"></button>
                <button class="circle-button red-button" id="red-border-button" data-color="red"></button>
                <button class="blue-button circle-button" id="blue-border-button" data-color="blue"></button>

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

// Add custom field to product editor
function polymuse_custom_field()
{
    woocommerce_wp_text_input(
        array(
            'id' => '_3d_model_url',
            'label' => '3D Model URL',
            'description' => 'Enter the URL of the 3D model file (e.g., .glb or .gltf)',
            'desc_tip' => true,
        )
    );
}

// Save custom field data
function polymuse_save_custom_field($post_id)
{
    $model_url = $_POST['_3d_model_url'];
    if (!empty($model_url)) {
        update_post_meta($post_id, '_3d_model_url', esc_url($model_url));
    }
}


// Add 3D model and thumbnail to gallery
function polymuse_add_model_and_thumbnail_to_gallery($html, $attachment_id)
{
    global $product;

    // Debug logging
    error_log('polymuse_add_model_and_thumbnail_to_gallery called');
    error_log('Attachment ID: ' . $attachment_id);
    error_log('HTML received: ' . $html);

    if (!$product) {
        error_log('No product found');
        return $html;
    }

    $model_url = get_post_meta($product->get_id(), '_3d_model_url', true);
    error_log('Model URL: ' . $model_url);

    if (!empty($model_url)) {
        // Create thumbnail URL for the 3D model
        $model_thumbnail_url = plugins_url('3d.webp', __FILE__);
        error_log('Model Thumbnail URL: ' . $model_thumbnail_url);

        // Check if this is the first image in the gallery
        static $first_image = true;

        if ($first_image) {
            $first_image = false;
            // Create the model viewer div
            $model_viewer = '<div data-thumb="' . esc_url($model_thumbnail_url) . '" ';
            $model_viewer .= 'data-thumb-alt="3D Model" ';
            $model_viewer .= 'data-thumb-srcset="' . esc_url($model_thumbnail_url) . ' 100w" ';
            $model_viewer .= 'data-thumb-sizes="(max-width: 100px) 100vw, 100px" ';
            $model_viewer .= 'class="woocommerce-product-gallery__image polymuse-model-viewer">';
            $model_viewer .= '<model-viewer src="' . esc_url($model_url) . '" alt="3D model of ' . esc_attr($product->get_name()) . '" auto-rotate camera-controls ar style="width: 100%; height: 100%;"></model-viewer>';
            $model_viewer .= '</div>';

            error_log('Modified HTML: ' . $html);
            return $model_viewer . $html;
        }
    }

    return $html;
}

function polymuse_enqueue_assets()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style('polymuse-styles', plugins_url('/styles.css', __FILE__));
    wp_enqueue_script('polymuse-script', plugins_url('polymuse.js', __FILE__), array('jquery'), '1.0', true);
}

function polymuse_add_model_viewer_script()
{
    echo '<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>';
}

function add_js_to_dom()
{
    ?>
    <script>
        console.log('DOM is ready');
        jQuery(function ($) {
            console.log('DOM is ready');

            const $colorSelect = $("select[name='attribute_color']");
            const $form = $(".variations_form");

            // Ensure WooCommerce variation update works
            function updateVariation(color) {
                console.log("Updating variation for color:", color);

                // Set dropdown value and trigger change event
                $colorSelect.val(color).trigger("change");

                // Find the variation data
                let variations = $form.data("product_variations");
                let selectedVariation = variations.find(v => v.attributes.attribute_color === color);

                if (selectedVariation) {
                    console.log("Found variation:", selectedVariation);

                    // Update WooCommerce variation ID field
                    $form.find('input[name="variation_id"], input.variation_id').val(selectedVariation.variation_id).trigger("change");

                    // Trigger WooCommerce events to properly update pricing, stock, etc.
                    $form.trigger("woocommerce_variation_has_changed");
                } else {
                    console.log("No matching variation found for color:", color);
                }
            }

            // Handle button clicks to change color
            $(".circle-button").on("click", function () {
                const color = $(this).data("color").charAt(0).toUpperCase() + $(this).data("color").slice(1); // Capitalize
                console.log("Color button clicked:", color);

                // Update variation without changing the image
                updateVariation(color);

                // Highlight the selected button
                $(".circle-button").removeClass("selected");
                $(this).addClass("selected");
            });

            // Handle dropdown change (if user manually selects from dropdown)
            $colorSelect.on("change", function () {
                const selectedColor = $(this).val();
                console.log("Dropdown changed to:", selectedColor);
                updateVariation(selectedColor);
            });
        });


    </script>
    <?php
}

function add_look_at_me_heading()
{
    echo '<h1 style="font-size: 10px;">Look at me</h1>';
}

// Work around to edit cart page
function bbloomer_woocommerce_cart_block_do_actions($block_content, $block)
{
    $blocks = array(
        'woocommerce/cart',
        'woocommerce/filled-cart-block',
        'woocommerce/cart-items-block',
        'woocommerce/cart-line-items-block',
        'woocommerce/cart-cross-sells-block',
        'woocommerce/cart-cross-sells-products-block',
        'woocommerce/cart-totals-block',
        'woocommerce/cart-order-summary-block',
        'woocommerce/cart-order-summary-heading-block',
        'woocommerce/cart-order-summary-coupon-form-block',
        'woocommerce/cart-order-summary-subtotal-block',
        'woocommerce/cart-order-summary-fee-block',
        'woocommerce/cart-order-summary-discount-block',
        'woocommerce/cart-order-summary-shipping-block',
        'woocommerce/cart-order-summary-taxes-block',
        'woocommerce/cart-express-payment-block',
        'woocommerce/proceed-to-checkout-block',
        'woocommerce/cart-accepted-payment-methods-block',
    );
    if (in_array($block['blockName'], $blocks)) {
        ob_start();
        do_action('bbloomer_before_' . $block['blockName']);
        echo $block_content;
        do_action('bbloomer_after_' . $block['blockName']);
        $block_content = ob_get_contents();
        ob_end_clean();
    }
    return $block_content;
}

function test_add_to_dom_plugin()
{
    $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';

    if (
        in_array($plugin_path, wp_get_active_and_valid_plugins())
        || in_array($plugin_path, wp_get_active_network_plugins())
    ) {

        // add_filter('render_block', 'bbloomer_woocommerce_cart_block_do_actions', 9999, 2);// Work around to edit cart page
        // add_action('bbloomer_before_woocommerce/cart-line-items-block', 'add_look_at_me_heading');
        add_action('woocommerce_before_add_to_cart_form', 'add_buttons');


        add_action('woocommerce_product_options_general_product_data', 'polymuse_custom_field');
        add_action('woocommerce_process_product_meta', 'polymuse_save_custom_field');
        add_filter('woocommerce_single_product_image_thumbnail_html', 'polymuse_add_model_and_thumbnail_to_gallery', 10, 2);
        add_action('wp_head', 'polymuse_add_model_viewer_script');
        add_action('wp_enqueue_scripts', 'polymuse_enqueue_assets');

        add_action('wp_footer', 'add_js_to_dom');

    }
}

test_add_to_dom_plugin();





