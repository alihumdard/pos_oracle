<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title> Dashboard</title>
   @include('pages.css')
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <style>

      /* Your global zoom */
      body {
         zoom: 80%;
      }

      .modal {
         transform: scale(1.05);
      }

      .modal-backdrop {
         zoom: 125%;
      }
   </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">
      @include('pages.sidebar')
      <div class="content-wrapper">


         <section class="content">
            <div class="container-fluid">
               @yield('content')

            </div>
         </section>
      </div>
      {{-- @include('pages.footer') --}}
   </div>

   @include('pages.script')
   @stack('scripts')
   @include('pages.bottom-nav')

   <script>
      window.onload = function() {
         document.body.style.zoom = "80%";
      };
   </script>
</body>

</html>