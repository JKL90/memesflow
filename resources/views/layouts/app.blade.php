<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MemesFLow" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MemesFlow')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../plugins/ekko-lightbox/ekko-lightbox.css">

    <style>
        /* Conteneur général du preloader */
        #preloader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.8);
            /* semi-transparent */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Spinner */
        .spinner {
            width: 50px;
            height: 40px;
            text-align: center;
        }

        .spinner>div {
            background-color: #3498db;
            height: 100%;
            width: 6px;
            display: inline-block;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
        }

        .spinner .rect2 {
            animation-delay: -1.1s;
        }

        .spinner .rect3 {
            animation-delay: -1.0s;
        }

        .spinner .rect4 {
            animation-delay: -0.9s;
        }

        .spinner .rect5 {
            animation-delay: -0.8s;
        }

        @keyframes sk-stretchdelay {

            0%,
            40%,
            100% {
                transform: scaleY(0.4);
            }

            20% {
                transform: scaleY(1.0);
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <div id="preloader">
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href=" {{ route('memes') }} " class="navbar-brand">
                    <img src="../../dist/img/LogoMemesFlow.png" alt="Memes Flow Logo"
                        class="brand-image img-circle elevation-4" style="opacity: .8">
                    <span class="brand-text font-weight-light">MemesFlow</span>
                </a>
                <div class="float-right d-none d-sm-inline">
                    <a href=" {{ route('pages.about') }} " class="navbar-brand">
                        <span class="brand-text font-weight-light btn">A propos</span>
                    </a>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            {{-- <h1 class="m-0"> Top Navigation <small>Example 3.0</small></h1> --}}
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            @yield('content')
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Version 1.0
            </div>
            <strong>Copyright &copy; 2025.</strong> Tous
            droits réservés | Réalisé par Jordan LOKO pour admission à SUPINFO
        </footer>

    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- Toastr -->
    <script src="../../plugins/toastr/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- preloader -->
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            preloader.style.opacity = '0';
            preloader.style.transition = 'opacity 0.5s ease';

            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        });
    </script>
    <!-- gallerie -->
    <script>
        $(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            })
        })

        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('.toastrDefaultSuccess').click(function() {
                toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultInfo').click(function() {
                toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultError').click(function() {
                toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultWarning').click(function() {
                toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
        })
    </script>

    <script>
        $(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>
</body>

</html>
