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
        background-color: #F1E2B3;
        color: #5d4c0e;
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
        <svg class="w-6 h-6 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path fill="currentColor" fill-rule="evenodd" d="M12.0212 2.37541c.2069.29981.2344.6884.0718 1.01435-.0835.16728-.1286.49646-.0866.81839.0345.26443.1525.51845.3564.72235.3798.37974.9446.46622 1.4086.25366.5022-.23 1.0957-.0094 1.3256.49272.0358.07797.0606.15815.0752.23883.0722.36885-.0676.76315-.391.99974-.067.04901-.1315.10409-.1929.16547-.6377.63769-.6377 1.67158 0 2.30927.6377.63771 1.6716.63771 2.3093 0 .1104-.11046.2008-.23148.2721-.35918.2649-.47416.8598-.65023 1.3401-.3966.4802.25363.6702.84422.428 1.33031-.1068.21428-.1459.45618-.1151.69208.033.2531.146.4962.3412.6915.265.265.622.3806.9709.3452.0473-.0048.095-.0062.1425-.0043.111.0047.5252-.0071.7534-.0158.2865-.0108.5639.1018.7617.3093.1978.2075.2971.4899.2726.7756-.1945 2.2647-1.1592 4.478-2.8921 6.2109-3.9053 3.9053-10.23692 3.9053-14.14216 0-3.90524-3.9052-3.90524-10.23686 0-14.1421C6.727 3.13084 8.88362 2.17056 11.0983 1.94837c.3624-.03636.716.12723.9229.42704ZM8.65695 8.41498c-.55229 0-1 .44771-1 1 0 .55228.44771 1.00002 1 1.00002h.01c.55228 0 1-.44774 1-1.00002 0-.55229-.44772-1-1-1h-.01ZM7.27106 12c-.55229 0-1 .4478-1 1 0 .5523.44771 1 1 1h.01c.55228 0 1-.4477 1-1 0-.5522-.44772-1-1-1h-.01Zm7.68744 1.9157c-.5523 0-1 .4477-1 1s.4477 1 1 1h.01c.5523 0 1-.4477 1-1s-.4477-1-1-1h-.01ZM11 16c-.5523 0-1.00004.4478-1.00004 1 0 .5523.44774 1 1.00004 1h.01c.5523 0 1-.4477 1-1 0-.5522-.4477-1-1-1H11Z" clip-rule="evenodd"/>
        </svg>

        <span class="sr-only">Cookie</span>
    </div>
    <div class="toast-message">
        {!! trans('cookie-consent::texts.message') !!}
    </div>
    <button type="button" class="close-button" data-dismiss-target="#toast-default" aria-label="Close" style="padding: 15px">
        <span class="sr-only">Close</span>
        <img src="{{ asset('img/close.png') }}" style="width:20px; height: 20px">
{{--        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">--}}
{{--            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>--}}
{{--        </svg>--}}
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


