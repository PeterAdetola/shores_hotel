<style>
    /* Responsive container positioning */
    #toast-default {
        position: fixed;
        bottom: 1.25rem; /* 20px, like Tailwind's bottom-5 */
        left: 50%;
        transform: translateX(-50%);
        width: 85%;
        max-width: 90%; /* safety limit */
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: white;
        color: #6b7280; /* text-gray-500 */
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        z-index: 9999;
    }

    /* Responsive widths */
    @media (min-width: 768px) {
        #toast-default {
            width: 60%;
        }
    }

    @media (min-width: 1024px) {
        #toast-default {
            width: 25%;
        }
    }

    .icon-container {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 2rem;
        height: 2rem;
        /*background-color: rgb(236, 185, 52);*/
        /*color: #5d4c0e;*/
        border-radius: 0.5rem;
    }

    .toast-message {
        margin-left: 0.75rem;
        font-size: 0.875rem;
        font-weight: normal;
    }

    .close-button {
        margin-left: auto;
        margin-right: -0.375rem;  /* -mx-1.5 */
        margin-top: -0.375rem;    /* -my-1.5 */
        margin-bottom: -0.375rem;
        background-color: white;
        color: #9ca3af;           /* text-gray-400 */
        padding: 0.375rem;        /* p-1.5 */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 1.5rem;
        width: 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
        border: none;
        cursor: pointer;
    }

    .close-button:hover {
        background-color: #f3f4f6;  /* hover:bg-gray-100 */
        color: #111827;             /* hover:text-gray-900 */
    }

    .close-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px #d1d5db; /* focus:ring-2 focus:ring-gray-300 */
    }
</style>




<div id="toast-default" role="alert">
    <div class="icon-container">
        <span class="material-symbols-outlined grey-text">cookie</span>

{{--        <span class="sr-only">Cookie</span>--}}
    </div>
    <div class="toast-message">
        {!! trans('cookie-consent::texts.message') !!}
    </div>
    <button type="button" class="close-button" data-dismiss-target="#toast-default" aria-label="Close" style="padding: 15px">
{{--        <span class="sr-only">Close</span>--}}
{{--        <img src="{{ asset('img/close.png') }}" style="width:20px; height: 20px">--}}
{{--        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">--}}
{{--            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>--}}
{{--        </svg>--}}
        <span class="material-symbols-outlined grey-text">close</span>
    </button>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-dismiss-target]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.querySelector(this.getAttribute('data-dismiss-target'));
                if (target) {
                    target.style.display = 'none';

                    // Set cookie to remember consent
                    document.cookie = "cookie_consent=true; path=/; max-age=" + 60 * 60 * 24 * 365;
                }
            });
        });
    });
</script>


