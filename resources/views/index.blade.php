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
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
         <!-- Brand Logo -->
         <a href="{{route('dashboard')}}" class="brand-link">
            <img src="{{ asset('assets/logo/logo.jpg') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Rana Electronics</span>
         </a>
         <!-- Sidebar -->
         @include('pages.sidebar')
         <!-- /.sidebar -->
      </aside>
      <div class="content-wrapper">


         <section class="content">
            <div class="container-fluid">
               @yield('content')

            </div>
         </section>
      </div>
      @include('pages.footer')
   </div>

   @include('pages.script')
   @stack('scripts')


</body>

</html>