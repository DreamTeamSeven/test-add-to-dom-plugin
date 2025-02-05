jQuery(document).ready(function ($) {

    function adjustModelViewerHeight() {
        $('.polymuse-model-viewer').height(500);
    }

    adjustModelViewerHeight();
    $(window).resize(adjustModelViewerHeight);

    // Get the model viewer element
    const modelViewer = $('.polymuse-model-viewer')[0];

    // Check if the model viewer element exists
    if (modelViewer) {
        // Wait for the model to load
        modelViewer.addEventListener('load', function () {
            // Get materials and variants data (adjust based on your model's format)
            const model = modelViewer.model;
            console.log(model);

            const materials = modelViewer.model.materials;
            console.log(materials);

            // Example check for available variants (you may need to adjust this logic depending on your model)
            const variants = modelViewer.availableVariants || [];  // Adjust this logic if needed
            console.log('Available variants:', variants);

            // Create buttons for each variant if available
            const variantButtonsContainer = $('#variant-options-container');
            variantButtonsContainer.empty();

            const button = $('<button></button>');
            button.text('hello');
            variantButtonsContainer.append(button);
            // variantButtonsContainer.text('No variants available');
        });
    }

});
