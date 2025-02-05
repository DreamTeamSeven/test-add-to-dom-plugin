jQuery(document).ready(function ($) {
    function adjustModelViewerHeight() {
        $('.polymuse-model-viewer').height(500);
    }

    adjustModelViewerHeight();
    $(window).resize(adjustModelViewerHeight);

    const modelViewer = $('model-viewer')[0];

    if (modelViewer) {
        console.log('Model viewer found:', modelViewer);

        $(modelViewer).on('load', () => {
            console.log('Model viewer loaded (event fired)');
            const model = modelViewer.model;
            console.log('Model:', model);

            const materials = modelViewer.model.materials;
            console.log(materials);

            // Check for available variants
            const variants = modelViewer.availableVariants;
            console.log('Available variants:', variants);

            // Get material info for each variant
            const variantInfo = {};
            if (variants) {
                variants.forEach(variant => {
                    modelViewer.variantName = variant;
                    const material = modelViewer.model.materials[0]; // Assuming first material
                    if (material && material.pbrMetallicRoughness && material.pbrMetallicRoughness.baseColorFactor) {
                        variantInfo[variant] = material.pbrMetallicRoughness.baseColorFactor;
                    }
                });
                // Reset to first variant
                modelViewer.variantName = variants[0];
            }

            // Create buttons for each variant
            const variantButtonsContainer = $('#variant-options-container')[0];
            if (variantButtonsContainer) {
                if (variants && variants.length > 0) {
                    variants.forEach(variant => {
                        const button = document.createElement('button');
                        button.textContent = variant;
                        button.addEventListener('click', () => {
                            modelViewer.variantName = variant;
                        });
                        variantButtonsContainer.appendChild(button);
                    });
                } else {
                    variantButtonsContainer.textContent = 'No variants available';
                }
            }
        });
    } else {
        console.log('Model Viewer element not found.');
    }
});