@extends('layouts.layout')
@section('title', 'Eamon Express | Shipping Rates')
@section('css_content', 'css/style.css')

@section('content')

<div class="container-md">
    
<div class="card text-center">
  <div class="card-header">
    <h3>Billing Document</h3>
  </div>
  <div class="card-body">
    <h5 class="card-title">Tracking #: {{ session('trackingId') }}</h5>
    <a href="{{ session('trackingUrl') }}" class="btn btn-primary" target="_blank">Reciept</a>
  </div>
  <div class="card-footer text-body-secondary">
    {{ session('serviceTyped') }}
  </div>
</div>

</div>

@endsection

@section('js_content', 'js/register_animate.js')
