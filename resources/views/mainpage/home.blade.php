@extends('layouts.layout')
@section('title', 'Eamon Express | Home')
@section('css_content', 'css/style.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">



@section('content')

<Section class="banner-area">
    <div class="container">
        <div class="row">
            <div class="contain-head col-lg-6">
                <div class="contain-headings">
                    <h1>Compare and Book</h1>
                    <h2>Low Cost shipping services</h2>
                    <p>Receive your packages swiftly and securely with Eamon Express. Track your shipments and get real-time updates on their location.</p>
                    <a href="#" class="href">Request Service</a>
                </div>
            </div>
            <div class="contain-img col-lg-6 ">
                <img src="img/Banner-image.png" alt="" class="src">
            </div>
        </div>
    </div>
</Section>









<section class="calculator-area py-5" id='contact'>
    <div class="container-md">
        <div class="container-form bg-white p-4 rounded shadow-lg" style="max-width: 1000px; margin: auto;">
            <h3 class="text-center text-white bg-primary py-2 rounded">Get a Quote Without Signing Up</h3>
            @if(session('error'))
                <div class="alert alert-danger text-center" id="validateError">
                    {{ session('error') }}
                </div>
            @else
                {{-- This space is intentionally left blank --}}
            @endif
            <form class="d-flex justify-content-center mt-4" id="calc-form" action="{{ route('retrieveShipments') }}" method="get">
                @csrf
                <div class="row g-3 align-items-center justify-content-between">
                    <!-- First Column -->
                    <div class="col-md-6">

                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="shipperStreet">Shippers Postal Codes</label>
                            <input type="text" class="form-control" id="shipperStreet" name="shipperStreet" placeholder="72601 - Enter your postal code here" value="{{ old('shipperStreet') }}"  required>


                        </div>

                        <!-- From Country -->
                        <div class="mb-3">
                            <label for="from_country" class="form-label d-flex align-items-center justify-content-start">
                                <i class="bi bi-geo-alt-fill me-2"></i> Shipper:
                            </label>
                            <select name="fromCountry" class="form-select" id="from_country" required>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->code }}" {{ $country->code === 'US' ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- From ZIP Code -->
                        <div class="mb-3">
                            {{-- <label for="zipcodeFrom" class="form-label d-flex align-items-center justify-content-start">
                                <i class="bi bi-envelope-fill me-2"></i> From Zip / Postal Code:
                            </label> --}}


                            <input type="hidden" id="inputFromZip"  name="zipcodeFrom" class="form-control" placeholder="Zip / Postal code" id="zipcodeFrom" required style="background-color: #eeeeee;  ">
                            {{-- <input type="text" id="inputFromZip"  name="zipcodeFrom" class="form-control" placeholder="Zip / Postal code" id="zipcodeFrom" required> --}}

                            <input type="hidden" class="form-control text-uppercase" id="shipperstateOrProvinceCode" name="shipperstateOrProvinceCode" placeholder="AR (Argentina)" required>

                            <input type="hidden" class="form-control" id="shipperCity" name="shipperCity" placeholder="Green Valley" maxlength="35" value="{{ old('shipperCity') }}" required>


                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="col-md-6">
                        {{-- Complete Address --}}
                        <div class="mb-3">
                            <label for="recipientStreet">Recipient Postal Codes</label>
                            <input type="text" class="form-control" id="recipientStreet" name="recipientStreet" placeholder="m1m1m1 - Enter your postal code here" value="{{ old('zipcodeTo') }}"  required>
                        </div>

                        <!-- To Country -->
                        <div class="mb-3">
                            <label for="to_country" class="form-label d-flex align-items-center justify-content-start">
                                <i class="bi bi-geo-alt-fill me-2"></i> Recipient:
                            </label>
                            <select name="toCountry" class="form-select" id="to_country" required>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->code }}" {{ $country->code === 'CA' ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- To ZIP Code -->
                        <div class="mb-3">
                            {{-- <label for="zipcodeTo" class="form-label d-flex align-items-center justify-content-start">
                                <i class="bi bi-envelope-fill me-2"></i> To Zip / Postal Code:
                            </label> --}}
                            <input type="hidden" id="inputToZip" name="zipcodeTo" class="form-control" placeholder="Zip / Postal code" id="zipcodeTo" required style='background-color: #eeeeee;  '>
                            {{-- <input type="text" id="inputToZip" name="zipcodeTo" class="form-control" placeholder="Zip / Postal code" id="zipcodeTo" required> --}}

                            <input type="hidden" class="form-control text-uppercase" id="recipientstateOrProvinceCode" name="recipientstateOrProvinceCode" placeholder="ON (Ontario)" required value="{{ old('recipientstateOrProvinceCode') }}">

                            <input type="hidden" class="form-control" id="recipientCity" name="recipientCity" placeholder="Toronto" maxlength="35" required>


                        </div>
                    </div>

                    <!-- Weight Input -->
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="weight" class="form-label d-flex align-items-center">
                                <i class="bi bi-box-seam me-2"></i> Parcel Weight:
                            </label>
                            <div class="input-group">


                                <input type="number" class="form-control" name="weight" step="0.01" placeholder="Weight" required style='background-color: #eeeeee;'>
                                <span class="input-group-text">LBS</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-3">
                            <button type="submit"  class="btn btn-primary px-4">Get Quote</button>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>
</section>




<Section class="About-eamon-area heading-dy">
    <div class="container">
        <div class="row">
            <div class="contain-head col-lg-6">
                <div class="contain-headings">
                    <h2>What is Eamon Express?</h2>
                    <p>Eamon Express is a user-friendly price comparison site for booking shipping services both within the US and internationally.<br></br>

                        Use our smart shipping calculator to get an instant quote, discover discounted rates with leading couriers, and compare prices to find the best deals.</p>
                    <a href="#" class="href">Learn More</a>
                </div>
            </div>
            <div class="contain-img col-lg-6 ">
                <img src="img/about-aemon.png" alt="" class="src">
            </div>
        </div>
    </div>
</Section>
<section class="howitwork heading-dy">
    <div class="container">
        <h2>How does Eamon Express work?</h2>
        <div class="contain-instructions">
            <div class="contain-inst">
                <img src="img/inst1.png" alt="" class="src">
                <p>Let us know where you're sending your package from and its destination.</p>
            </div>
            <div class="contain-inst">
                <img src="img/inst2.png" alt="" class="src">
                <p>Indicate the weight and dimensions of your package so we can find the best deals for you.</p>
            </div>
            <div class="contain-inst">
                <img src="img/inst3.png" alt="" class="src">
                <p>Select the courier that suits your needs and pay for your shipping online.</p>
            </div>
            <div class="contain-inst">
                <img src="img/inst4.png" alt="" class="src">
                <p>Attach your shipping label and either drop off your package or arrange for it to be picked up.</p>
            </div>
        </div>
    </div>
</section>
<Section class="Update-sec heading-dy">
    <div class="container">
        <div class="row">
            <div class="contain-img col-lg-6 ">
                <img src="img/update-banner.png" alt="" class="src">
            </div>
            <div class="contain-head col-lg-6">
                <div class="contain-headings">
                    <h2>Stay Updated With Our Latest News</h2>
                    <p>Receive our latest updates, news, blog posts, and more directly in your inbox. Subscribe to our mailing list today.</p>
                    <div class="contain-update-frm">
                        <form action="" method="post" id="updatefrm">
                            <input type="email" name="email" placeholder="Email">
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</Section>





{{-- <script>
    document.getElementById('calc-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form submission until validation is done
        let fromCountry = document.getElementById('from_country').value;
        let toCountry = document.getElementById('to_country').value;
        let zipcodeFrom = document.querySelector('input[name="zipcodeFrom"]');
        let zipcodeTo = document.querySelector('input[name="zipcodeTo"]');
        let errorMessage = document.querySelector('#error-message p');

        // Postal code validation patterns
        const postalCodePatterns = {
            'US': /^[0-9]{5}(?:-[0-9]{4})?$/,  // US ZIP code format (5 digits or ZIP+4)
            'CA': /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/,  // Canadian postal code format
            // Add more country patterns here if necessary
        };

        // Clear error message
        errorMessage.style.display = 'none';

        // Validate "From" postal code
        if (postalCodePatterns[fromCountry] && !postalCodePatterns[fromCountry].test(zipcodeFrom.value)) {
            errorMessage.textContent = `Invalid ZIP/Postal Code format country (${fromCountry}).`;
            errorMessage.style.display = 'block';
            zipcodeFrom.value = ''; // Clear input
            zipcodeFrom.focus(); // Set focus on the input field
            return;
        }

        // Validate "To" postal code
        if (postalCodePatterns[toCountry] && !postalCodePatterns[toCountry].test(zipcodeTo.value)) {
            errorMessage.textContent = `Invalid ZIP/Postal Code format country (${toCountry}).`;
            errorMessage.style.display = 'block';
            zipcodeTo.value = ''; // Clear input
            zipcodeTo.focus(); // Set focus on the input field
            return;
        }



        // If validation passes, hide the error message and submit the form
        errorMessage.style.display = 'none';
        this.submit();
    });
</script> --}}


@endsection

@section('js_content', 'js/locateAddressWithPostal.js')
{{-- @section('js_content2', 'js/validatePostal.js') --}}
