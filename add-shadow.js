document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.circle-button');
    const productImage = document.querySelector('.woocommerce div.product div.images img');

    if (buttons && productImage) {
        buttons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                let boxShadowColor;
                switch (button.classList[1]) {
                    case 'green-button':
                        boxShadowColor = 'green';
                        break;
                    case 'red-button':
                        boxShadowColor = 'red';
                        break;
                    case 'blue-button':
                        boxShadowColor = 'blue';
                        break;
                    default:
                        boxShadowColor = 'green'; // default color
                }
                productImage.style.boxShadow = `0 0 10px 5px ${boxShadowColor}`;
            });
        });
    }
});

//from client
jQuery(document).ready(function($) {

    function adjustModelViewerHeight() {
        var galleryHeight = $('.woocommerce-product-gallery__wrapper').height();
        $('.polymuse-model-viewer').height(galleryHeight);
    }

    adjustModelViewerHeight();
    $(window).resize(adjustModelViewerHeight);
});
