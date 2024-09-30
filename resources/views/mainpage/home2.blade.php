@extends('layouts.layout')
@section('title', 'Eamon Express | Home')
@section('css_content', 'css/style.css')
<link href="https://js.radar.com/v4.4.2/radar.css" rel="stylesheet">

@section('content')

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


                <h1>Country Search Dropdown</h1>
                <input type="text" id="countryInput" placeholder="Start typing a country name..." />
                <div id="dropdown" class="dropdown"></div>

            </div>
        </div>
    </div>
</Section>



@endsection
<script src="https://js.radar.com/v4.4.2/radar.min.js"></script>

<script>

    const testPublishableKey = 'prj_live_pk_282bce66618b63742b0ad59cd6a9c2deda92aadd';

    Radar.initialize(testPublishableKey);

    // search

    Radar.autocomplete({
        query: '841 bro',
        near: {
            latitude: 40.783826,
            longitude: -73.975363
        },
        limit: 10
        })
        .then((result) => {
        const { addresses } = result;
        // do something with addresses
        })
        .catch((err) => {
        // handle error
        });






    </script>


@section('js_content', 'js/register_animate.js')
