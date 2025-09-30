<style>

    /* 1. Global button disabled styles */
    button:disabled,
    .btn:disabled {
        /* These might be making all buttons appear disabled */
        pointer-events: none;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* 2. Form validation styles that affect all buttons */
    .was-validated button,
    .needs-validation button {
        /* Bootstrap validation might be affecting buttons */
    }

    /* 3. CSS frameworks reset styles */
    button {
        background: none;
        border: 0;
        color: inherit;
        /* These resets might be removing button styling */
    }

    /* 4. Materialize conflicts */
    .btn, button {
        /* Check if there are conflicting Materialize overrides */
    }

    /* 5. Generic disabled state */
    [disabled] {
        pointer-events: none !important;
        /* This would disable ALL disabled elements */
    }

    /* 6. Form element resets */
    input, button, select, textarea {
        /* Broad resets that might affect button behavior */
    }

    /* SOLUTION: Add these specific overrides to your CSS */

    /* Override any global disabled styles for stepper buttons specifically */
    .stepper .step-actions button:not([data-originally-disabled]),
    .stepper .step-actions .btn:not([data-originally-disabled]) {
        pointer-events: auto !important;
        cursor: pointer !important;
        opacity: 1 !important;
    }

    /* Ensure only truly disabled buttons are disabled */
    .stepper .step-actions button[disabled="true"],
    .stepper .step-actions button[disabled="disabled"],
    .stepper .step-actions .btn[disabled="true"],
    .stepper .step-actions .btn[disabled="disabled"] {
        pointer-events: none !important;
        cursor: not-allowed !important;
        opacity: 0.6 !important;
    }

    /* Reset button appearance for stepper buttons */
    .stepper button {
        -webkit-appearance: button;
        -moz-appearance: button;
        appearance: button;
    }

    /* Ensure buttons maintain their click events */
    .stepper .step-actions * {
        pointer-events: auto;
    }

    /* Fix z-index issues */
    .stepper {
        position: relative;
        z-index: 1;
    }
/*.stepper select {*/
/*    text-align: left !important;*/
/*}*/
    .stepper .step-actions {
        position: relative;
        z-index: 2;
    }
    .preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .preview-container img {
        width: 150px;
        height: 100px;
        margin: 5px;
        object-fit: cover;
    }.amenities-container {
         display: flex;
         flex-wrap: wrap;
         gap: 12px; /* Modern way to space items (replaces margin) */
         margin-top: 1em;
     }

    .amenity-item {
        padding: 0.5em 1em;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: #f9f9f9;
        transition: all 0.2s ease;
    }

    .amenity-item:hover {
        background: #f0f0f0;
        border-color: #bbb;
    }

    /* Hide default checkbox (optional) */
    .amenity-item input[type="checkbox"] {
        display: none;
    }

    /* Custom checkbox style */
    .amenity-item label {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .amenity-item label:before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 1px solid #ccc;
        border-radius: 3px;
        background: white;
    }

    .amenity-item input:checked + label:before {
        background: #4CAF50;
        border-color: #4CAF50;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
    }

    .availability-box {
        padding: 1.2em 1.5em; /* Increased padding */
        margin: 0.8em 0;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background: #f8f9fa;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .availability-box:hover {
        border-color: #bdbdbd;
        background: #f1f3f5;
    }

    /* Switch styling */
    .availability-box .switch {
        margin: 0;
    }

    .availability-box .lever {
        margin: 0 12px;
    }

    .availability-text {
        font-weight: 500;
        color: #424242;
        margin-left: 8px;
    }

    /* Hide the actual checkbox but keep functionality */
    .hidden-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    #select-options-5de765dc-44e0-4ba5-8411-5d9c3333b193 li > span {
        text-align: left !important;
    }
    ul.dropdown-content li > span {
        text-align: left !important;
    }

    /* Materialize switch adjustments */
    .availability-box .switch label input[type="checkbox"]:checked + .lever {
        background-color: #84c7c1;
    }

    .availability-box .switch label input[type="checkbox"]:checked + .lever:after {
        background-color: #26a69a;
    }
</style>

