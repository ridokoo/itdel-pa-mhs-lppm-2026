<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
<link rel="shortcut icon" type="image/png" href="{{ asset('img/del.png') }}" />
    <!-- Core Css -->
    <link rel="stylesheet" href="/assets/css/styles.css" />
    <link rel="stylesheet" href="/assets/vendor/icons-webfont-3.34.0/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="/styles/custom.css" />

    @livewireStyles

    <title>Delcom - @yield('title')</title>
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css" />

    @yield('others-css')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="/img/logo-dark-text.png" alt="loader" class="lds-ripple img-fluid"
            style="height: 72px; width: 214px;" />
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="text-center mt-5">
                <a href="#"><img src="/img/logo-dark-text.png" alt="logo SDI" style="height:64px" /></a>
            </div>

            <div class="sticky-top px-5 py-3 bg-white">
                <ul class="nav nav-tabs" style="background-color:#F7F8FA">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('legal.terms') ? 'active' : '' }}"
                            href="{{ route('legal.terms') }}">
                            Terms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('legal.privacy') ? 'active' : '' }}"
                            href="{{ route('legal.privacy') }}">
                            hallo Privacy
                        </a>
                    </li>
                </ul>
            </div>

            <div class="px-5">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        function hidePreloader() {
            var preloader = document.querySelector(".preloader");
            if (preloader) {
                preloader.style.display = "none";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            hidePreloader();
        });
    </script>
</body>

</html>



