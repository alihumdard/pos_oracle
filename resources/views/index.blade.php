<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Dashboard</title>
    @include('pages.css')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    /* Bottom Navigation Bar */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #ffffff;
        border-top: 1px solid #e3e6f0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        z-index: 1030;
        padding: 0.5rem 0;
    }

    .bottom-nav-inner {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #858796;
        font-size: 0.75rem;
        flex-grow: 1;
        transition: color 0.9s;
    }

    .bottom-nav-item i {
        font-size: 1.5rem;
        margin-bottom: 0.1rem;
    }

    .bottom-nav-item.active,
    .bottom-nav-item:hover {
        color: #4e73df;
    }

    @media (max-width: 990px) {
        .sidebars {
            display: none;
        }
    }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <aside class="sidebars  main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{route('dashboard')}}" class="brand-link">
                <img src="{{ asset('assets/logo/logo.jpg') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Rana Electronics</span>
            </a>
            <!-- Sidebar -->
            @include('pages.sidebar')
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper ">
            <section class="content">
                <div class="container-fluid pb-20 sm:pb-0">
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- @include('pages.footer') -->
    </div>
    @include('pages.bottom-nav')

    @include('pages.script')
    @stack('scripts')


</body>

</html>