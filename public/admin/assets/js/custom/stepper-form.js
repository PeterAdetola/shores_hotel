document.addEventListener('DOMContentLoaded', function() {
    // Initialize stepper
    var horizStepper = document.querySelector('#horizStepper');
    var horizStepperInstace = new MStepper(horizStepper, {
        firstActive: 0,
        autoFormCreation: true,
        stepTitleNavigation: true,
        validationFunction: defaultValidationFunction
    });

    // Store default values for ALL inputs when page loads
    const defaultStates = {};
    document.querySelectorAll('input, select, textarea').forEach(el => {
        if (el.type === 'checkbox' || el.type === 'radio') {
            defaultStates[el.name] = el.checked;
        } else if (el.type === 'range') {
            defaultStates[el.id] = el.value;
            // Store displayed value if exists
            const valueDisplay = document.getElementById(el.id + 'Value');
            if (valueDisplay) defaultStates[el.id + '_display'] = el.value;
        } else if (el.type === 'number') {
            defaultStates[el.id] = el.value; // Store by ID for number inputs
        } else {
            defaultStates[el.name] = el.value;
        }
    });

    // Special handling for availability switch (force default to checked)
    const availabilitySwitch = document.querySelector('.availability-box input[type="checkbox"]');
    if (availabilitySwitch) {
        defaultStates[availabilitySwitch.name] = true; // Force default to checked
    }

    // Initialize slider values and bind events
    const initSliders = () => {
        const adultSlider = document.querySelector('input[type="range"][min="1"][max="5"]');
        const childrenSlider = document.querySelector('input[type="range"][min="0"][max="5"]');
        const adultValueSpan = document.getElementById('adultValue');
        const childrenValueSpan = document.getElementById('childrenValue');

        if (adultSlider && adultValueSpan) {
            adultValueSpan.textContent = adultSlider.value;
            adultSlider.addEventListener('input', function() {
                adultValueSpan.textContent = this.value;
            });
        }

        if (childrenSlider && childrenValueSpan) {
            childrenValueSpan.textContent = childrenSlider.value;
            childrenSlider.addEventListener('input', function() {
                childrenValueSpan.textContent = this.value;
            });
        }
    };
    initSliders();

    // Reset functionality
    document.querySelectorAll('[data-stepper-reset]').forEach(button => {
        button.addEventListener('click', function() {
            const activeStep = this.closest('.step-content');

            // Reset standard inputs
            activeStep.querySelectorAll('input').forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = defaultStates[input.name] || false;
                    // Special case for availability switch
                    if (input.closest('.availability-box')) {
                        input.checked = true; // Always reset to checked
                    }
                } else if (input.type === 'range') {
                    input.value = defaultStates[input.id] || input.min;
                    // Update displayed value
                    const valueDisplay = document.getElementById(input.id + 'Value');
                    if (valueDisplay) {
                        valueDisplay.textContent = defaultStates[input.id + '_display'] || input.min;
                    }
                } else if (input.type === 'number') {
                    input.value = defaultStates[input.id] || input.min;
                } else if (input.type === 'file') {
                    input.value = '';
                    // Clear file previews
                    const previewContainer = input.closest('.file-field')?.querySelector('.image-preview');
                    if (previewContainer) {
                        previewContainer.innerHTML = '';
                        previewContainer.style.display = 'none';
                    }
                } else {
                    input.value = defaultStates[input.name] || '';
                }

                // Trigger Materialize update for switches
                if (input.type === 'checkbox' && input.closest('.switch')) {
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });

            // Reset selects
            activeStep.querySelectorAll('select').forEach(select => {
                select.value = defaultStates[select.name] || '';
                M.FormSelect.init(select);
            });

            // Reset textareas
            activeStep.querySelectorAll('textarea').forEach(textarea => {
                textarea.value = defaultStates[textarea.name] || '';
            });

            // Force availability switch to checked state
            const availabilitySwitch = activeStep.querySelector('.availability-box input[type="checkbox"]');
            if (availabilitySwitch) {
                availabilitySwitch.checked = true;
                const event = new Event('change', { bubbles: true });
                availabilitySwitch.dispatchEvent(event);
            }

            // Reset multiple image uploads
            activeStep.querySelectorAll('.image-upload-container').forEach(container => {
                container.innerHTML = '<div class="image-preview" style="display:none;"></div>';
            });

            // Re-initialize sliders to ensure proper reset
            initSliders();
        });
    });

    // File upload preview functionality
    const fileInput = document.getElementById('file-upload');
    const previewContainer = document.querySelector('.preview-container');

    if (fileInput && previewContainer) {
        fileInput.addEventListener('change', function(e) {
            previewContainer.innerHTML = ''; // Clear previous previews

            const files = this.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Check if the file is an image
                if (!file.type.startsWith('image/')) {
                    alert("Please select only image files.");
                    continue;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-thumbnail');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    }
});

// Default validation function for stepper
function defaultValidationFunction(stepperForm, activeStepContent) {
    var inputs = activeStepContent.querySelectorAll('input, textarea, select');
    for (var i = 0; i < inputs.length; i++) {
        if (!inputs[i].checkValidity()) return false;
    }
    return true;
}
