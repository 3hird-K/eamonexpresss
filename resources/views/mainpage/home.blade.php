@extends('layouts.layout')
@section('title', 'Eamon Express | Home')
@section('css_content', 'css/style.css')


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




<Section class="calculator-area">
    <div class="container">
        <div class="contain-form">
            <h3>Get a quote without signing up</h3>
            <div class="contain-fields">
                <form id="calc-form" action="{{ route('retrieveShipments') }}" method="get">
                    @csrf
                    <div class="fields">
                        <div class="contain-icons">
                            <img src="img/from.svg" alt="From Icon" class="src">
                            <span>From:</span>
                        </div>
                        <select name="fromCountry" class="form-select" id="from_country">
                            @foreach ($countries as $country)
                                <option value="{{ $country->code }}" {{ $country->code === 'US' ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fields zip">
                        <input type="text" name="zipcodeFrom" class="form-control" placeholder="ZIP Code" id="zipcodeFrom" required>
                    </div>

                    <div class="fields">
                        <div class="contain-icons">
                            <img src="img/to.svg" alt="To Icon" class="src">
                            <span>To:</span>
                        </div>
                        <select name="toCountry" class="form-select" id="to_country">
                            @foreach ($countries as $country)
                                <option value="{{ $country->code }}" {{ $country->code === 'CA' ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fields zip">
                        <input type="text" name="zipcodeTo" class="form-control" placeholder="ZIP Code" id="zipcodeTo" required>
                    </div>

                    <div class="fields">
                        <div class="contain-icons">
                            <img src="img/parcel-weight.svg" alt="Parcel Weight Icon" class="src">
                            <span>Parcel Weight:</span>
                        </div>
                        <input type="number" class="form-control" name="weight" step="0.01" required>
                        <div class="contain-perm contain-icons">
                            <span>LBS</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" id="submit-btn">Get Quote</button>
                        </div>
                        <div class="col-md-6">
                            <div id="error-message">
                                <p class="alert alert-danger text-center" style="display: none;"></p>
                            </div>
                        </div>


                    </div>
                </form>

            </div>
        </div>
    </div>
</Section>




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



<script>
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
</script>


@endsection

@section('js_content', 'js/register_animate.js')
