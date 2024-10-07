@extends('layouts.layout')
@section('title', 'FedEx Shipping')
@section('css_content', 'css/style.css')
<link href="https://js.radar.com/v4.4.2/radar.css" rel="stylesheet">
    <script src="https://js.radar.com/v4.4.2/radar.min.js"></script>

@section('content')
<div class="container-xl mt-5 p-4 my-5 rounded">
    <h2 class="text-center">FedEx Shipping Calculator</h2>
    <form action="{{ route('createdShipment')}}" method="POST">
        @csrf




        <!-- Shipper and Recipient Info Section -->
        <div class="card my-4">
            <div class="card-header">
                <h4>Shipper & Recipient Information</h4>
                <small>Fill in the details of the shipper and recipient.</small>
                <p><span class="fw-bold">{{ $serviceType }}</span></p>
                <p>Total: <span class="fw-bold">$ {{ session('totalNetCharge') }}</span></p>
            </div>
            <div class="card-body">
                <div>
                <h5 class="text-primary">Shipper Information</h5>
                <input type="text" name="shipperCountryCode" value="{{ $fromCountry }}" class="form-control fw-bold mb-3" disabled>
                <input type="hidden" name="shipperCountryCode" value="{{ $fromCountry }}" class="form-control fw-bold">
                </div>
                <div class="row">

                    {{-- Shipper Name --}}

                <div class="col-md-6 mb-3">
                    <label for="shipperName">Shipper Name</label>
                    <input type="text" class="form-control" id="shipperName" name="shipperName" placeholder="Enter shipper name" value="{{ old('shipperName') }}" required>
                </div>

                {{-- Shipper Phone --}}
                <div class="col-md-6 mb-3">
                    <label for="shipperPhone">The shipper's phone number.</label>
                    <input type="tel" class="form-control" id="shipperPhone" name="shipperPhone" placeholder="(604) 555-7890" minlength="10" maxlength="15" required>
                    <div class="error-message" id="phone-error" style="display: none; color: red;"></div>
                        {{-- <label for="shipperPhone">Phone Numbers</label>
                        <input type="tel" class="form-control" id="shipperPhone" name="shipperPhone" placeholder="(604) 555-7890" minlength="10" maxlength="15" value="{{ old('shipperPhone') }}" required> --}}
                        {{-- @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                @if ($error == "Phone Number is wrong!")
                                    <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                                        <p>{{ $error }}</p>
                                     </div>

                            @endif
                            @endforeach
                         @endif --}}
                    </div>


                    {{-- Shipper Street --}}

                    <div class="col-md-6 mb-3">
                        <label for="shipperStreet">Shipper Street</label>
                        <input type="text" class="form-control" id="shipperStreet" name="shipperStreet" placeholder="Shipper Street" value="{{ old('shipperStreet', $shipperStreet) }}" required>



                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="shipperCity">Province/State Code</label>
                        <input type="text" class="form-control text-uppercase" id="shipperstateOrProvinceCode" name="shipperstateOrProvinceCode" placeholder="AR (Argentina)" value="{{ old('shipperstateOrProvinceCode', $shipperstateOrProvinceCode ) }}" required>
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    @if ($error == "Province Code is wrong!")
                                        <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                                            <p>{{ $error }}</p>
                                        </div>

                                @endif
                                @endforeach
                            @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="shipperCity">City</label>
                        <input type="text" class="form-control" id="shipperCity" name="shipperCity" placeholder="Green Valley" maxlength="35" value="{{ old('shipperCity', $shipperCity) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="shipperCity">Postal Code</label>
                        <input type="text" class="form-control" id="shipperCity" value="{{ session('zipcodeFrom') }}">
                    </div>

                </div>

                <h5 class="text-primary mt-4">Recipient Information</h5>
                <input type="text" name="recipientCountryCode" value="{{ $toCountry }}" class="form-control fw-bold mb-3" disabled>
                <input type="hidden" name="recipientCountryCode" value="{{ $toCountry }}" class="form-control fw-bold">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="recipientName">Recipient Name</label>
                        <input type="text" class="form-control" id="recipientName" name="recipientName" placeholder="Enter recipient name" value="{{ old('recipientName') }}"  required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="recipientPhone">Phone Number</label>
                        <input type="tel" class="form-control" id="recipientPhone" name="recipientPhone" placeholder="(604) 555-7890" minlength="10" maxlength="15" value="{{ old('recipientPhone') }}" required>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                @if ($error == "Phone Number is wrong!")
                                    <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                                        <p>{{ $error }}</p>
                                    </div>

                            @endif
                            @endforeach
                         @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="recipientStreet">Recipient Street </label>
                        <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="123 Maple St" value="{{ old('recipientStreet', $recipientStreet) }}"  required>
                        {{-- <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="123 Maple St" value="{{ old('recipientStreet') }}"  required> --}}
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="recipientstateOrProvinceCode">Province/State Code</label>
                        <input type="text" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="ON (Ontario)" required value="{{ old('recipientstateOrProvinceCode', $recipientstateOrProvinceCode) }}" max="2">
                        @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    @if ($error == "Recipient Province Code is wrong!")
                                        <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                                            <p>{{ $error }}</p>
                                        </div>

                                @endif
                                @endforeach
                            @endif

                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="recipientCity">City</label>
                        <input type="text" class="form-control" id="recipientCity" name="recipientCity" placeholder="Toronto" maxlength="35" value="{{ old('$recipientCity', $recipientCity ) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="shipperCity">Postal Code</label>
                        <input type="text" class="form-control" id="shipperCity" value="{{ session('zipcodeTo') }}" >
                    </div>
                </div>
            </div>
            <!-- @if ($errors->any())
                <div class="container-md alert alert-danger d-flex justify-content-center align-content-center text-center">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @if ($error == "Phone Number too Long!")

                            @endif
                        @endforeach
                </div>
            @endif -->
        </div>

        <!-- Shipment Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Shipment Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Ship Date Dropdown -->
                    <div class="col-md-6 mb-3">
                        <label for="shipDate">Ship Date</label>
                        <select class="form-control" id="shipDate" name="shipDate" required>
                            @for ($i = 0; $i <= 8; $i++)
                                <option value="{{ now()->addDays($i)->format('Y-m-d') }}">
                                    {{ now()->addDays($i)->format('Y-m-d') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Packaging Type -->

                    <div class="col-md-6 mb-3">
                        <input type="hidden" id="weightInput" value="{{ session('weight') }}">
                        <label for="packagingType">Packaging Type</label>
                        <select class="form-control" id="packagingType" name="packagingType" required>
                            <option value="YOUR_PACKAGING">Customer Packaging - 150 lbs/68 KG (Express)</option>
                            <option value="YOUR_PACKAGING">Customer Packaging - 70 lbs/32 KG (Ground)</option>
                            <option value="YOUR_PACKAGING">Customer Packaging - 70 lbs/32 KG (Economy)</option>
                            <option value="FEDEX_ENVELOPE" data-weight="1">FedEx Envelope - 1 lbs/0.5 KG</option>
                            <option value="FEDEX_BOX" data-weight="20">FedEx Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_SMALL_BOX" data-weight="20">FedEx Small Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_MEDIUM_BOX" data-weight="20">FedEx Medium Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_LARGE_BOX" data-weight="20">FedEx Large Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_EXTRA_LARGE_BOX" data-weight="20">FedEx Extra Large Box - 20 lbs/9 KG</option>
                            <option value="FEDEX_10KG_BOX" data-weight="22">FedEx 10kg Box - 22 lbs/10 KG</option>
                            <option value="FEDEX_25KG_BOX" data-weight="55">FedEx 25kg Box - 55 lbs/25 KG</option>
                            <option value="FEDEX_PAK" data-weight="20">FedEx Pak - 20 lbs/9 KG</option>
                            <option value="FEDEX_TUBE" data-weight="20">FedEx Tube - 20 lbs/9 KG</option>
                        </select>
                    </div>


                    <!-- Pickup Type -->
                    <div class="col-md-6 mb-3">
                        <label for="pickupType">Pickup Type</label>
                        <select class="form-control" id="pickupType" name="pickupType" required>
                            <option value="USE_SCHEDULED_PICKUP">Use Scheduled Pickup</option>
                            <option value="CONTACT_FEDEX_TO_SCHEDULE">Contact FedEx to Schedule</option>
                            <option value="DROPOFF_AT_FEDEX_LOCATION">Dropoff at FedEx Location</option>
                        </select>
                    </div>

                    <!-- Special Service Type -->
                    <!-- <div class="col-md-6 mb-3">
                        <label for="specialServiceType">Special Service Types</label>
                        <select class="form-control" id="specialServiceType" name="specialServiceType">
                            <option value="FEDEX_ONE_RATE">FedEx One Rate</option>
                        </select>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Label Specification -->
        {{-- <div class="card mb-4"> --}}
            {{-- <div class="card-header">
                <h4>Tell me more about your goods:</h4>
            </div>
            <div class="card-body"> --}}

            {{-- <div class="row"> --}}
                {{-- <div class="col-md-6 input-group mb-3"> --}}
                    {{-- <span class="input-group-text" id="basic-addon1">$</span> --}}
                    <input type="hidden" class="form-control" placeholder="Enter your estimated amount" name="customsValueAmount" value="200" required>

                {{-- </div> --}}
                {{-- <div class="col-md-6 input-group mb-3"> --}}
                    {{-- <span class="input-group-text" id="basic-addon1">qty.</span> --}}
                    <input type="hidden" value="1" class="form-control" name="customsValueQuantity" required>

                {{-- </div> --}}
            {{-- </div> --}}



                <!-- <div class="row"> -->
                    <!-- <div class="col-md-6 mb-3">
                        <label for="imageType">Image Type</label>
                        <select class="form-control" id="imageType" name="imageType" required>
                            <option value="PDF">PDF</option>
                            <option value="PNG">PNG</option>
                        </select>
                    </div>                     -->
                    <!-- <div class="col-md-6 mb-3">
                        <label for="labelStockType">Label Stock Type</label>
                        <select class="form-control" id="labelStockType" name="labelStockType" required>
                            <option value="PAPER_4X6">PAPER 4x6</option>
                            <option value="PAPER_4X675">PAPER 4x6.75</option>
                            <option value="PAPER_4X8">PAPER 4x8</option>
                            <option value="PAPER_4X9">PAPER 4x9</option>
                            <option value="PAPER_7X475">PAPER 7x4.75</option>
                            <option value="PAPER_85X11_BOTTOM_HALF_LABEL">PAPER 8.5x11 Bottom Half Label</option>
                            <option value="PAPER_85X11_TOP_HALF_LABEL">PAPER 8.5x11 Top Half Label</option>
                            <option value="PAPER_LETTER">PAPER LETTER</option>
                            <option value="STOCK_4X675_LEADING_DOC_TAB">STOCK 4x6.75 Leading Doc Tab</option>
                            <option value="STOCK_4X8">STOCK 4x8</option>
                            <option value="STOCK_4X9_LEADING_DOC_TAB">STOCK 4x9 Leading Doc Tab</option>
                            <option value="STOCK_4X6">STOCK 4x6</option>
                            <option value="STOCK_4X675_TRAILING_DOC_TAB">STOCK 4x6.75 Trailing Doc Tab</option>
                            <option value="STOCK_4X9_TRAILING_DOC_TAB">STOCK 4x9 Trailing Doc Tab</option>
                            <option value="STOCK_4X9">STOCK 4x9</option>
                            <option value="STOCK_4X85_TRAILING_DOC_TAB">STOCK 4x8.5 Trailing Doc Tab</option>
                            <option value="STOCK_4X105_TRAILING_DOC_TAB">STOCK 4x10.5 Trailing Doc Tab</option>
                        </select> -->


                    <!-- </div> -->
                    <!-- <div class="col-md-6 mb-3"> -->
                        <!-- <label for="labelResponseOptions">Label Response Options</label>
                        <select class="form-control" id="labelResponseOptions" name="labelResponseOptions" required>
                            <option value="LABEL">Label</option>
                            <option value="URL_ONLY">URL Only</option>
                        </select> -->


                    <!-- </div> -->

                    <input type="hidden" name="labelStockType" value="PAPER_LETTER">
                    <input type="hidden" name="imageType" value="PDF">
                    <input type="hidden" name="labelResponseOptions" value="URL_ONLY">
                {{-- </div> --}}
            {{-- </div> --}}
            <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">Submit Shipment Request</button>
        </div>
        </div>


    </form>
</div>

{{-- <input type="text" value={{ session('zipcodeTo') }} id="autocomplete"> --}}
{{-- <input type="text" value={{ session('zipcodeTo') }} id="autocomplete"> --}}

{{-- <div id="autocomplete" /> --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const weightInput = parseInt(document.getElementById('weightInput').value, 10);
        const select = document.getElementById('packagingType');
        const options = select.options;

        // Iterate over the options to show/hide based on weight
        for (let i = options.length - 1; i >= 0; i--) {
            const optionWeight = parseInt(options[i].getAttribute('data-weight'), 10);
            if (optionWeight <= weightInput) {
                select.remove(i);
            }
        }
    });
</script>

<script>
    document.getElementById('calc-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission until validation is done
        let shipperPhone = document.getElementById('shipperPhone').value;
        let phoneError = document.getElementById('phone-error');
        phoneError.style.display = 'none'; // Clear previous error message

        // Remove non-digit characters for validation
        let cleanedPhone = shipperPhone.replace(/\D/g, '');

        // Check length and format
        if (cleanedPhone.length < 10 || cleanedPhone.length > 15) {
            phoneError.textContent = 'Phone number must be between 10 and 15 digits long.';
            phoneError.style.display = 'block';
            return;
        }

        // For US and CA: must have exactly 10 digits, with an optional leading country code '1' or '+1'
        if ((cleanedPhone.length === 10 || (cleanedPhone.length === 11 && cleanedPhone.startsWith('1'))) ||
            (cleanedPhone.length === 12 && cleanedPhone.startsWith('+1'))) {
            // Phone number is valid, continue with form submission
            this.submit();
        } else {
            phoneError.textContent = 'For US and CA, a phone number must have exactly 10 digits, plus an optional leading country code of "1" or "+1".';
            phoneError.style.display = 'block';
        }
    });
</script>





@endsection
{{-- @section('js_content', 'js/locatorRadar.js') --}}
{{-- @section('js_content', 'js/locatorRadar.js') --}}


