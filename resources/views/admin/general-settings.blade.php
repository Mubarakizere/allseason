@extends('layouts.admin')

@section('title', 'General Settings — All The Season Garden')

@push('styles')
<style>
    .gs-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .gs-header {
        margin-bottom: 24px;
    }
    .gs-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .gs-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* Cards */
    .gs-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 24px;
        height: calc(100% - 24px);
        display: flex;
        flex-direction: column;
    }
    .gs-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }
    .gs-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .gs-card-body {
        padding: 0;
        flex: 1;
    }
    .gs-card-footer {
        padding: 12px 20px;
        background: #ffffff;
        border-top: 1px solid #f3f4f6;
    }

    .btn-gs-add {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-gs-add:hover {
        background: #b91c1c;
    }

    /* Table Styling */
    .table-gs {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .table-gs th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px;
    }
    .table-gs td {
        padding: 12px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        color: #111827;
        font-size: 13px;
    }
    .table-gs tr:last-child td {
        border-bottom: none;
    }
    .table-gs tr:hover td {
        background-color: #f9fafb;
    }

    .pac-container {
        z-index: 20000 !important;
        background-color: #fff;
        border: 1px solid #ccc;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Phone Number Modal
        function resetPhoneNumberModal() {
            $('#phoneNumberForm')[0].reset();
            $('#phoneNumberForm').attr('action', "{{ route('admin.phone-number.store') }}");
            $('#phoneNumberFormMethod').val('');
        }

        window.createPhoneNumber = function () {
            resetPhoneNumberModal();
            $('#phoneNumberModalLabel').text('Add Phone Number');
        };

        window.editPhoneNumber = function (id, phoneNumber, useWhatsapp) {
            resetPhoneNumberModal();
            $('#phone_number').val(phoneNumber);
            $('#use_whatsapp').prop('checked', useWhatsapp === 1);
            let actionUrl = "{{ route('admin.phone-number.update', ':id') }}".replace(':id', id);
            $('#phoneNumberForm').attr('action', actionUrl);
            $('#phoneNumberFormMethod').val('PUT');
            $('#phoneNumberModalLabel').text('Edit Phone Number');
        };

        // Address Modal
        function resetAddressModal() {
            $('#addressForm')[0].reset();
            $('#addressForm').attr('action', "{{ route('admin.address.store') }}");
            $('#addressFormMethod').val('');
            $('#addressModalLabel').text('Add Address');
        }

        window.createAddress = function () {
            resetAddressModal();
            $('#addressModalLabel').text('Add Address');
        };

        window.editAddress = function (button) {
            resetAddressModal();

            var $btn = $(button);
            var id          = $btn.data('id');
            var street      = $btn.data('street') || '';
            var city        = $btn.data('city') || '';
            var state       = $btn.data('state') || '';
            var postalCode  = $btn.data('postal_code') || '';
            var country     = $btn.data('country') || '';
            var latitude    = $btn.data('latitude') || '';
            var longitude   = $btn.data('longitude') || '';
            var fullAddress = $btn.data('full_address') || '';

            $('#address').val(fullAddress);
            $('#line1').val(street);
            $('#city').val(city);
            $('#state').val(state);
            $('#postal_code').val(postalCode);
            $('#country').val(country);
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);

            let actionUrl = "{{ route('admin.address.update', ':id') }}".replace(':id', id);
            $('#addressForm').attr('action', actionUrl);
            $('#addressFormMethod').val('PUT');
            $('#addressModalLabel').text('Edit Address');
        };

        // Working Hour Modal
        function resetWorkingHourModal() {
            $('#workingHourForm')[0].reset();
            $('#workingHourForm').attr('action', "{{ route('admin.working-hour.store') }}");
            $('#workingHourFormMethod').val('');
            $('#is_closed').prop('checked', false);
            toggleWorkingHourTimeInputs(false);
        }

        window.createWorkingHour = function () {
            resetWorkingHourModal();
            $('#workingHourModalLabel').text('Add Working Hour');
        };

        window.editWorkingHour = function (button) {
            resetWorkingHourModal();

            var $btn      = $(button);
            var id        = $btn.data('id');
            var day       = $btn.data('day');
            var opens     = $btn.data('opens');
            var closes    = $btn.data('closes');
            var isClosed  = $btn.data('is_closed') == 1;

            $('#day_of_week').val(day);
            $('#opens_at').val(opens || '');
            $('#closes_at').val(closes || '');
            $('#is_closed').prop('checked', isClosed);

            toggleWorkingHourTimeInputs(isClosed);

            let actionUrl = "{{ route('admin.working-hour.update', ':id') }}".replace(':id', id);
            $('#workingHourForm').attr('action', actionUrl);
            $('#workingHourFormMethod').val('PUT');
            $('#workingHourModalLabel').text('Edit Working Hour');
        };

        function toggleWorkingHourTimeInputs(isClosed) {
            const disabled = !!isClosed;
            $('#opens_at').prop('disabled', disabled);
            $('#closes_at').prop('disabled', disabled);
        }

        $('#is_closed').on('change', function () {
            toggleWorkingHourTimeInputs(this.checked);
        });

        // Social Media Handle Modal
        function resetSocialMediaModal() {
            $('#socialMediaForm')[0].reset();
            $('#socialMediaForm').attr('action', "{{ route('admin.social-media-handles.store') }}");
            $('#handle').val('');
            $('#socialMediaFormMethod').val('');
        }

        window.createSocialMediaHandle = function () {
            resetSocialMediaModal();
            $('#socialMediaModalLabel').text('Add Social Media Handle');
        };

        window.editSocialMediaHandle = function (id, handle, socialMedia) {
            resetSocialMediaModal();
            $('#handle').val(handle);
            $('#social_media').val(socialMedia);
            let actionUrl = "{{ route('admin.social-media-handles.update', ':id') }}".replace(':id', id);
            $('#socialMediaForm').attr('action', actionUrl);
            $('#socialMediaFormMethod').val('PUT');
            $('#socialMediaModalLabel').text('Edit Social Media Handle');
        };      

        // Delete Handlers
        window.deletePhoneNumber = function (id) {
            let actionUrl = "{{ route('admin.phone-number.delete', ':id') }}".replace(':id', id);
            $('#deletePhoneNumberForm').attr('action', actionUrl);
            $('#deletePhoneNumberModal').modal('show');
        };

        window.deleteAddress = function (id) {
            let actionUrl = "{{ route('admin.address.delete', ':id') }}".replace(':id', id);
            $('#deleteAddressForm').attr('action', actionUrl);
            $('#deleteAddressModal').modal('show');
        };

        window.deleteWorkingHour = function (id) {
            let actionUrl = "{{ route('admin.working-hour.delete', ':id') }}".replace(':id', id);
            $('#deleteWorkingHourForm').attr('action', actionUrl);
            $('#deleteWorkingHourModal').modal('show');
        };

        window.deleteSocialMediaHandle = function (id) {
            let actionUrl = "{{ route('admin.social-media-handles.delete', ':id') }}".replace(':id', id);
            $('#deleteSocialMediaHandleForm').attr('action', actionUrl);
            $('#deleteSocialMediaHandleModal').modal('show');
        };
    });

    function initAddressModalPlaces() {
        var input = document.getElementById('address');
        if (!input || !window.google || !google.maps || !google.maps.places) return;

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            fields: ['address_components', 'geometry', 'formatted_address']
        });

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place || !place.address_components) return;

            var components = place.address_components;

            function findComponent(type) {
                var comp = components.find(function (c) {
                    return c.types.indexOf(type) !== -1;
                });
                return comp ? comp.long_name : '';
            }

            var streetNumber = findComponent('street_number');
            var route        = findComponent('route');
            var line1        = [streetNumber, route].filter(Boolean).join(' ');

            document.getElementById('line1').value        = line1;
            document.getElementById('city').value         = findComponent('locality') || findComponent('postal_town') || findComponent('sublocality') || '';
            document.getElementById('state').value        = findComponent('administrative_area_level_1');
            document.getElementById('postal_code').value  = findComponent('postal_code');
            document.getElementById('country').value      = findComponent('country');

            if (place.geometry && place.geometry.location) {
                document.getElementById('latitude').value  = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('addressModal');
        if (!modalEl) return;

        modalEl.addEventListener('shown.bs.modal', function () {
            if (window.google && google.maps && google.maps.places) {
                initAddressModalPlaces();
            }
        });
    });

    (function() {
        function updateCurrencyFields() {
            const select = document.getElementById('country_id');
            if (!select) return;
            const option = select.options[select.selectedIndex];
            if (!option) return;

            const symbol = option.getAttribute('data-currency-symbol') || '';
            const code   = option.getAttribute('data-currency-code') || '';

            const decoded = document.getElementById('decoded_symbol');
            const cCode = document.getElementById('currency_code');
            if (decoded) decoded.value = symbol;
            if (cCode) cCode.value  = code;

            const hiddenSymbol = document.getElementById('currency_symbol');
            if (hiddenSymbol) hiddenSymbol.value = symbol;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('country_id');
            if (!select) return;

            select.addEventListener('change', updateCurrencyFields);
            updateCurrencyFields();
        });
    })();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places" async defer></script>
@endpush

@section('content')
<div class="content-wrapper gs-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="gs-header">
        <h1>General Settings & Restaurant Profile</h1>
        <p>Manage contact phone numbers, location addresses, social channels, business hours, and store settings.</p>
    </div>

    {{-- Section Grid 1: Phone Numbers & Addresses --}}
    <div class="row">
        {{-- Phone Numbers --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <div class="gs-card-header">
                    <h3 class="gs-card-title"><i class="fas fa-phone-alt text-danger me-1"></i> Phone Numbers</h3>
                    <button type="button" class="btn-gs-add" data-bs-toggle="modal" data-bs-target="#phoneNumberModal" onclick="createPhoneNumber()">
                        <i class="fas fa-plus"></i> Add Number
                    </button>
                </div>
                <div class="gs-card-body">
                    <table class="table-gs">
                        <thead>
                            <tr>
                                <th>Phone Number</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($phoneNumbers as $phoneNumber)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-phone text-muted"></i>
                                            <span class="fw-bold text-dark">{{ $phoneNumber->phone_number }}</span>
                                            @if($phoneNumber->use_whatsapp == 1)
                                                <span class="badge bg-success text-white" title="WhatsApp Enabled" style="font-size: 10px;">
                                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#phoneNumberModal" onclick="editPhoneNumber({{ $phoneNumber->id }}, '{{ $phoneNumber->phone_number }}', {{ $phoneNumber->use_whatsapp }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePhoneNumber({{ $phoneNumber->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No phone numbers configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Addresses --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <div class="gs-card-header">
                    <h3 class="gs-card-title"><i class="fas fa-map-marker-alt text-danger me-1"></i> Restaurant Addresses</h3>
                    <button type="button" class="btn-gs-add" data-bs-toggle="modal" data-bs-target="#addressModal" onclick="createAddress()">
                        <i class="fas fa-plus"></i> Add Address
                    </button>
                </div>
                <div class="gs-card-body">
                    <table class="table-gs">
                        <thead>
                            <tr>
                                <th>Address</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($addresses as $address)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-map-pin text-muted"></i>
                                            <span class="text-dark">{{ $address->full_address }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addressModal"
                                                data-id="{{ $address->id }}"
                                                data-street="{{ e($address->street) }}"
                                                data-city="{{ e($address->city) }}"
                                                data-state="{{ e($address->state) }}"
                                                data-postal_code="{{ e($address->postal_code) }}"
                                                data-country="{{ e($address->country) }}"
                                                data-latitude="{{ $address->latitude }}"
                                                data-longitude="{{ $address->longitude }}"
                                                data-full_address="{{ e($address->full_address) }}"
                                                onclick="editAddress(this)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAddress({{ $address->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No addresses configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Grid 2: Social Media & Working Hours --}}
    <div class="row">
        {{-- Social Media --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <div class="gs-card-header">
                    <h3 class="gs-card-title"><i class="fas fa-share-alt text-danger me-1"></i> Social Media Handles</h3>
                    <button type="button" class="btn-gs-add" data-bs-toggle="modal" data-bs-target="#socialMediaModal" onclick="createSocialMediaHandle()">
                        <i class="fas fa-plus"></i> Add Handle
                    </button>
                </div>
                <div class="gs-card-body">
                    <table class="table-gs">
                        <thead>
                            <tr>
                                <th>Handle</th>
                                <th>Platform</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($socialMediaHandles as $handle)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($handle->social_media === 'facebook')
                                                <i class="fab fa-facebook text-primary"></i>
                                            @elseif($handle->social_media === 'instagram')
                                                <i class="fab fa-instagram text-danger"></i>
                                            @elseif($handle->social_media === 'youtube')
                                                <i class="fab fa-youtube text-danger"></i>         
                                            @elseif($handle->social_media === 'tiktok')
                                                <i class="fab fa-tiktok text-dark"></i>                                        
                                            @else
                                                <i class="fas fa-globe text-muted"></i> 
                                            @endif
                                            <span class="fw-bold text-dark">{{ $handle->handle }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">{{ ucfirst($handle->social_media) }}</span></td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#socialMediaModal" onclick="editSocialMediaHandle({{ $handle->id }}, '{{ $handle->handle }}', '{{ $handle->social_media }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSocialMediaHandle({{ $handle->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No social media handles configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Working Hours --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <div class="gs-card-header">
                    <h3 class="gs-card-title"><i class="fas fa-clock text-danger me-1"></i> Opening Working Hours</h3>
                    <button type="button" class="btn-gs-add" data-bs-toggle="modal" data-bs-target="#workingHourModal" onclick="createWorkingHour()">
                        <i class="fas fa-plus"></i> Add Hours
                    </button>
                </div>
                <div class="gs-card-body">
                    <table class="table-gs">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Opens</th>
                                <th>Closes</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workingHours as $workingHour)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $workingHour->day_of_week }}</td>
                                    <td>
                                        @if(!$workingHour->is_closed && $workingHour->opens_at)
                                            {{ \Carbon\Carbon::parse($workingHour->opens_at)->format('H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$workingHour->is_closed && $workingHour->closes_at)
                                            {{ \Carbon\Carbon::parse($workingHour->closes_at)->format('H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($workingHour->is_closed)
                                            <span class="badge bg-danger text-white">Closed</span>
                                        @else
                                            <span class="badge bg-success text-white">Open</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#workingHourModal"
                                                data-id="{{ $workingHour->id }}"
                                                data-day="{{ $workingHour->day_of_week }}"
                                                data-opens="{{ $workingHour->opens_at ? \Carbon\Carbon::parse($workingHour->opens_at)->format('H:i') : '' }}"
                                                data-closes="{{ $workingHour->closes_at ? \Carbon\Carbon::parse($workingHour->closes_at)->format('H:i') : '' }}"
                                                data-is_closed="{{ $workingHour->is_closed ? 1 : 0 }}"
                                                onclick="editWorkingHour(this)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteWorkingHour({{ $workingHour->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No working hours configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Grid 3: Live Chat & Currency Settings --}}
    <div class="row">
        {{-- Live Chat Script --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <form method="POST" action="{{ $script ? route('admin.livechat.update', $script->id) : route('admin.livechat.store') }}" style="display: flex; flex-direction: column; flex: 1;">
                    @csrf
                    @if($script)
                        @method('PUT')
                    @endif
                    <div class="gs-card-header">
                        <h3 class="gs-card-title"><i class="fas fa-comments text-danger me-1"></i> Live Chat Integration</h3>
                    </div>
                    <div class="gs-card-body p-3">
                        <div class="alert alert-light border small text-muted mb-3" role="alert">
                            <i class="fas fa-info-circle text-primary me-1"></i> Paste valid widget code from Tawk.to, LiveChat, or Crisp.
                        </div>
                        <div class="mb-3">
                            <label for="name" class="fw-semibold mb-1" style="font-size: 12px;">Provider Name *</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Tawk.to" value="{{ $script->name ?? '' }}" required style="font-size: 13px;">
                        </div>
                        <div class="mb-3">
                            <label for="script_code" class="fw-semibold mb-1" style="font-size: 12px;">Script Code *</label>
                            <textarea class="form-control font-monospace" id="script_code" name="script_code" rows="3" placeholder="<script>...</script>" required style="font-size: 12px;"></textarea>
                        </div>
                    </div>
                    <div class="gs-card-footer d-flex justify-content-between">
                        @if($script)
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Script</button>
                            <button type="button" class="btn btn-outline-danger" onclick="document.getElementById('form-delete-livechat').requestSubmit();">Remove Widget</button>
                        @else
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold">Add Live Chat</button>
                        @endif
                    </div>
                </form>
                @if($script)
                    <form method="POST" id="form-delete-livechat" action="{{ route('admin.livechat.destroy', $script->id) }}" data-confirm-message="Are you sure you want to remove the live chat widget script?">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        {{-- Country & Currency --}}
        <div class="col-lg-6 mb-4">
            <div class="gs-card">
                <form action="{{ route('site-settings.save') }}" method="POST" style="display: flex; flex-direction: column; flex: 1;">
                    @csrf
                    <input type="hidden" id="currency_symbol" name="currency_symbol">

                    <div class="gs-card-header">
                        <h3 class="gs-card-title"><i class="fas fa-globe text-danger me-1"></i> Regional & Currency Settings</h3>
                    </div>
                    <div class="gs-card-body p-3">
                        <div class="mb-3">
                            <label for="country_id" class="fw-semibold mb-1" style="font-size: 12px;">Operating Country *</label>
                            <select required class="form-select" id="country_id" name="country_id" style="font-size: 13px;">
                                <option value="" disabled {{ empty($site_settings?->country) ? 'selected' : '' }}>Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                            data-currency-symbol="{{ $country->currency_symbol }}"
                                            data-currency-code="{{ $country->currency_code }}"
                                            {{ $site_settings?->country === $country->name ? 'selected' : '' }}>
                                        {{ $country->name }} ({{ $country->currency_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label for="decoded_symbol" class="fw-semibold mb-1" style="font-size: 12px;">Currency Symbol</label>
                                <input value="{!! $site_settings->currency_symbol ?? '' !!}" type="text" id="decoded_symbol" class="form-control bg-light" readonly style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="currency_code" class="fw-semibold mb-1" style="font-size: 12px;">Currency Code</label>
                                <input value="{{ $site_settings->currency_code ?? '' }}" type="text" id="currency_code" class="form-control bg-light" readonly style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="gs-card-footer">
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Currency Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Section Grid 4: Customer Order Settings --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="gs-card">
                <form action="{{ route('admin.order-settings.update') }}" method="POST">
                    @csrf
                    <div class="gs-card-header">
                        <h3 class="gs-card-title"><i class="fas fa-truck text-danger me-1"></i> Customer Order & Delivery Settings</h3>
                    </div>
                    <div class="gs-card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="price_per_mile" class="fw-semibold mb-1" style="font-size: 12px;">Delivery Rate / Mile ({!! $site_settings->currency_symbol !!}) *</label>
                                <input type="number" name="price_per_mile" id="price_per_mile" class="form-control" value="{{ $order_settings->price_per_mile ?? '' }}" step="0.01" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-4">
                                <label for="distance_limit_in_miles" class="fw-semibold mb-1" style="font-size: 12px;">Max Distance Radius (Miles) *</label>
                                <input type="number" name="distance_limit_in_miles" id="distance_limit_in_miles" class="form-control" value="{{ $order_settings->distance_limit_in_miles ?? '' }}" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-4">
                                <label for="notification_emails" class="fw-semibold mb-1" style="font-size: 12px;">Order Alert Emails</label>
                                <input type="text" name="notification_emails" id="notification_emails" class="form-control" value="{{ $order_settings->notification_emails ?? '' }}" placeholder="admin@example.com, manager@example.com" style="font-size: 13px;">
                                <small class="text-muted" style="font-size: 11px;">Separate multiple email addresses with commas.</small>
                            </div>
                        </div>
                    </div>
                    <div class="gs-card-footer">
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Order Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modals --}}

    {{-- Phone Number Modal --}}
    <div class="modal fade" id="phoneNumberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="phoneNumberForm" method="POST" style="width: 100%;">
                    @csrf
                    <input type="hidden" id="phoneNumberFormMethod" name="_method" value="">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" id="phoneNumberModalLabel">Phone Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label for="phone_number" class="fw-semibold mb-1" style="font-size: 12px;">Phone Number *</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="e.g. +250 788 123 456" required style="font-size: 13px;">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="use_whatsapp" name="use_whatsapp" value="1">
                            <label class="form-check-label fw-semibold ms-1" for="use_whatsapp" style="font-size: 12.5px;">Enable for Customer WhatsApp Direct Contact</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Number</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Address Modal --}}
    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="addressForm" method="POST" style="width: 100%;">
                    @csrf
                    <input type="hidden" id="addressFormMethod" name="_method" value="">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" id="addressModalLabel">Restaurant Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label for="address" class="fw-semibold mb-1" style="font-size: 12px;">Search Address (Google Places) *</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Start typing address..." autocomplete="off" required style="font-size: 13px;">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label for="line1" class="fw-semibold mb-1" style="font-size: 12px;">Street</label>
                                <input type="text" class="form-control bg-light" id="line1" name="line1" readonly style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="city" class="fw-semibold mb-1" style="font-size: 12px;">City</label>
                                <input type="text" class="form-control bg-light" id="city" name="city" readonly style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="state" class="fw-semibold mb-1" style="font-size: 12px;">State / Province</label>
                                <input type="text" class="form-control bg-light" id="state" name="state" readonly style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="country" class="fw-semibold mb-1" style="font-size: 12px;">Country</label>
                                <input type="text" class="form-control bg-light" id="country" name="country" readonly style="font-size: 13px;">
                            </div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Working Hour Modal --}}
    <div class="modal fade" id="workingHourModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="workingHourForm" method="POST" style="width: 100%;">
                    @csrf
                    <input type="hidden" id="workingHourFormMethod" name="_method" value="">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" id="workingHourModalLabel">Working Hour</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label for="day_of_week" class="fw-semibold mb-1" style="font-size: 12px;">Day of Week *</label>
                            <select class="form-select" id="day_of_week" name="day_of_week" required style="font-size: 13px;">
                                <option value="" disabled selected>Select day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label for="opens_at" class="fw-semibold mb-1" style="font-size: 12px;">Opens At</label>
                                <input type="time" class="form-control" id="opens_at" name="opens_at" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="closes_at" class="fw-semibold mb-1" style="font-size: 12px;">Closes At</label>
                                <input type="time" class="form-control" id="closes_at" name="closes_at" style="font-size: 13px;">
                            </div>
                        </div>
                        <div class="form-check form-switch mt-1">
                            <input type="checkbox" class="form-check-input" id="is_closed" name="is_closed" value="1">
                            <label class="form-check-label fw-semibold ms-1" for="is_closed" style="font-size: 12.5px;">Mark Closed All Day</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Hours</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Social Media Modal --}}
    <div class="modal fade" id="socialMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="socialMediaForm" method="POST" style="width: 100%;">
                    @csrf
                    <input type="hidden" id="socialMediaFormMethod" name="_method" value="">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" id="socialMediaModalLabel">Social Media Handle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label for="handle" class="fw-semibold mb-1" style="font-size: 12px;">Handle / Username *</label>
                            <input type="text" class="form-control" id="handle" name="handle" required placeholder="e.g. @alltheseasongarden" style="font-size: 13px;">
                        </div>
                        <div class="mb-2">
                            <label for="social_media" class="fw-semibold mb-1" style="font-size: 12px;">Platform *</label>
                            <select class="form-select" id="social_media" name="social_media" required style="font-size: 13px;">
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="youtube">YouTube</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Handle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modals --}}
    <div class="modal fade" id="deletePhoneNumberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="deletePhoneNumberForm" method="POST" style="width: 100%;">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Delete Phone Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px;">Are you sure you want to delete this phone number?</div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="deleteAddressForm" method="POST" style="width: 100%;">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Delete Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px;">Are you sure you want to delete this address?</div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteWorkingHourModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="deleteWorkingHourForm" method="POST" style="width: 100%;">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Delete Working Hour</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px;">Are you sure you want to delete this working hour?</div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteSocialMediaHandleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <form id="deleteSocialMediaHandleForm" method="POST" style="width: 100%;">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Delete Handle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px;">Are you sure you want to delete this social media handle?</div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection