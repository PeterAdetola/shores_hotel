<!-- Start Modal -->
<div id="guest_details-modal{{ $booking->id }}" class="modal" style="padding:1em;">
    <div class="modal-content">
        <div class="row">
            <ul class="collection with-header">
                <h6 class="card-title ml-2" style="display:inline-block;">
                    {{ $booking->customer_name }}
                </h6>

                <li class="collection-item">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="flex: 1;">
                            <small style="color: #666;">Email</small><br>
                            <input type="text"
                                   readonly
                                   value="{{ $booking->customer_email }}"
                                   id="email-{{ $booking->id }}"
                                   style="border: 1px solid #e0e0e0; padding: 5px 10px; border-radius: 4px; width: 100%; font-size: 14px; background: #f5f5f5;"
                                   onclick="this.select(); document.execCommand('copy'); showCopyToast('Email copied!');">
                        </div>
                        <a href="javascript:void(0)"
                           class="secondary-content grey-text"
                           onclick="event.preventDefault(); selectAndCopy('email-{{ $booking->id }}', 'Email')"
                           title="Click to select and copy"
                           style="margin-left: 10px;">
                            <span class="material-symbols-outlined" style="margin: 0.8em 0 0 -1em;">content_copy</span>
                        </a>
                    </div>
                </li>

                <li class="collection-item">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="flex: 1;">
                            <small style="color: #666;">Phone</small><br>
                            <input type="text"
                                   readonly
                                   value="{{ $booking->customer_phone }}"
                                   id="phone-{{ $booking->id }}"
                                   style="border: 1px solid #e0e0e0; padding: 5px 10px; border-radius: 4px; width: 100%; font-size: 14px; background: #f5f5f5;"
                                   onclick="this.select(); document.execCommand('copy'); showCopyToast('Phone copied!');">
                        </div>
                        <a href="javascript:void(0)"
                           class="secondary-content grey-text"
                           onclick="event.preventDefault(); selectAndCopy('phone-{{ $booking->id }}', 'Phone')"
                           title="Click to select and copy"
                           style="margin-left: 10px;">
                            <span class="material-symbols-outlined" style="margin: 0.8em 0 0 -1em;">content_copy</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
    <!-- Alternative: If you prefer to add CSS classes instead of inline styles -->
    <style>
     .copy-input-container {
         position: relative;
         display: flex;
         align-items: center;
     }

    .copy-input {
        border: 1px solid #e0e0e0;
        padding: 5px 40px 5px 10px;
        border-radius: 4px;
        width: 100%;
        font-size: 14px;
        background: #f5f5f5;
    }

    .copy-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 0;
        padding: 5px;
        border-radius: 3px;
        transition: background-color 0.2s;
        color: #666;
    }

    .copy-icon:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .copy-icon .material-symbols-outlined {
        font-size: 18px;
    }
</style>
<script>
    // Toast helper
    if (typeof window.showCopyToast === 'undefined') {
        window.showCopyToast = function(message) {
            if (typeof M !== 'undefined' && M.toast) {
                M.toast({ html: message, classes: 'rounded green' });
            }
        };
    }

    // Select and copy function
    if (typeof window.selectAndCopy === 'undefined') {
        window.selectAndCopy = function(inputId, label) {
            const input = document.getElementById(inputId);
            if (!input) return;

            // Select the text
            input.focus();
            input.select();
            input.setSelectionRange(0, input.value.length);

            // Try to copy
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    window.showCopyToast(label + ' copied!');
                } else {
                    window.showCopyToast('Text selected. Press Ctrl+C to copy');
                }
            } catch (err) {
                window.showCopyToast('Text selected. Press Ctrl+C to copy');
            }

            // Keep selection visible for user to manually copy if needed
            setTimeout(() => {
                input.select();
            }, 100);
        };
    }
</script>
<!-- /End Modal -->
