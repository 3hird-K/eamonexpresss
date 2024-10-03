<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Eamon Express')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/scrollbar.css">
    <link rel="stylesheet" href="@yield('css_content')">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://js.radar.com/v4.4.3/radar.css" rel="stylesheet">
    <script src="https://js.radar.com/v4.4.3/radar.min.js"></script>
  </head>
  <body data-bs-spy="scroll" data-bs-target=".navbar" tabindex="0" style="background-color: #b0e1f9;">

    <header id="headerSection" class="sticky-top">
        <nav class="navbar navbar-expand-md navbar-light" style="background-color: #b0e1f9;">
            <div class="container-md">
                <a class="navbar-brand" href="#">
                    <img src="img/logo.png" alt="Logo" class="img-fluid" style="max-height: 45px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="/">Home</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link fw-bold" href="#track">Track a Package</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/contact') }}">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/blog') }}">Blog</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link fw-bold" href="#login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="#signup">Sign Up</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    @yield('content')
    <footer>
<div class="container">
    <div class="column">
    <img src="img/logo.png" alt="" class="src">
    </div>
    <div class="column">
        <ul>
            <h3>Company</h3>
            <li><a href="{{ url('/about') }}" class="href">About Us</a></li>
            <li><a href="{{ url(path: '/review') }}" class="href">Reviews</a></li>
            <li><a href="{{ url(path: '/blog') }}" class="href">Blog</a></li>
            <li><a href="#" class="href">Privacy Policy</a></li>
            <li><a href="#" class="href">Cookie Policy</a></li>
            <li><a href="#" class="href">Terms & Conditions</a></li>
            <li><a href="#" class="href">Acceptable Use Policy</a></li>
            <li><a href="#" class="href">Sitemap</a></li>
        </ul>
    </div>

    <div class="column">
        <ul>
            <h3>Shipping Services</h3>
            <li><a href="#" class="href">Ship a Package</a></li>
            <!-- <li><a href="#" class="href">Track a Package</a></li> -->
            <li><a href="{{ url(path: '/domestic-shipping') }}" class="href">Domestic Shipping</a></li>
            <li><a href="{{ url(path: '/international-shipping') }}" class="href">International Shipping</a></li>
            <li><a href="#" class="href">Bulk Shipping</a></li>
            <li><a href="{{ url(path: '/couriers') }}" class="href">Couriers</a></li>
            <li><a href="{{ url(path: '/delivery') }}" class="href">Delivery Services</a></li>
        <ul>
    </div>

    <div class="column">
        <ul>
            <h3>Customer</h3>
            <!-- <li><a href="#" class="href">Login</a></li>
            <li><a href="#" class="href">Sign Up</a></li> -->
            <li><a href="{{ url('/contact') }}" class="href">Contact Us</a></li>
            <li><a href="#" class="href">How To Guides</a></li>
        </ul>
    </div>

    
    <div class="column">
        <h3>Connect with us</h3>
        <div class="contain-media">
            <img src="img/footer/facebook.svg" alt="" class="src">
            <img src="img/footer/x.png" alt="" class="src">
            <img src="img/footer/insta.svg" alt="" class="src">
        </div>
    </div>
</div>

</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="@yield('js_content')"></script>
    <script src="@yield('js_content2')"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  </body>
</html>
