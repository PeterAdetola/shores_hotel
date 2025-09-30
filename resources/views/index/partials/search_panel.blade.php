@php $rooms = getAllRooms(); @endphp
<div class="mil-search-panel mil-mb-20">
    <form method="POST" action="{{ route('make.booking') }}">
        @csrf

        <div class="mil-form-grid">
            <!-- Check-in -->
            <div class="mil-col-3 mil-field-frame">
                <label>Check-in</label>
                <input name="check_in" type="text"
                       class="datepicker-here"
                       data-position="bottom left"
                       placeholder="Select date"
                       autocomplete="off" readonly>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-calendar">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>

            <!-- Check-out -->
            <div class="mil-col-3 mil-field-frame">
                <label>Check-out</label>
                <input name="check_out" type="text"
                       class="datepicker-here"
                       data-position="bottom left"
                       placeholder="Select date"
                       autocomplete="off" readonly>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-calendar">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>

            <!-- Lodging Type -->
            <div class="mil-col-5 mil-field-frame">
                <label>Lodging type</label>
                <select name="room_id" required>
                    <option value="">Select Lodging</option>

                    {{-- Rooms group --}}
                    @if($rooms->where('room_type', 0)->count() > 0)
                        <optgroup label="Rooms">
                            @foreach($rooms->where('room_type', 0) as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->category->name }} - ₦{{ number_format($room->price_per_night) }}/night
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    {{-- Apartments group --}}
                    @if($rooms->where('room_type', 1)->count() > 0)
                        <optgroup label="Apartments">
                            @foreach($rooms->where('room_type', 1) as $room)
                                <option value="{{ $room->id }}">
                                    {{ $room->category->name }} - ₦{{ number_format($room->price_per_night) }}/night
                                </option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>

            <!-- Adults -->
            <div class="mil-col-2 mil-field-frame">
                <label>Adults</label>
                <input name="adults" type="number" value="1" min="1" required>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-users">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <!-- Children -->
            <div class="mil-col-2 mil-field-frame">
                <label>Children</label>
                <input name="children" type="number" value="0" min="0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-users">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>

        <!-- Submit button -->
{{--        <button type="submit">--}}
{{--            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"--}}
{{--                 viewBox="0 0 24 24" fill="none" stroke="currentColor"--}}
{{--                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"--}}
{{--                 class="feather feather-arrow-right">--}}
{{--                <line x1="5" y1="12" x2="19" y2="12"/>--}}
{{--                <polyline points="12 5 19 12 12 19"/>--}}
{{--            </svg>--}}
{{--            <span>Go</span>--}}
{{--        </button>--}}

        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
            <span>Go</span>
        </button>
    </form>

</div>
