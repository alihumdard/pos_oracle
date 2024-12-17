<div class="sidebar">
   <!-- Sidebar user panel (optional) -->
   

   <!-- Sidebar Menu -->
   <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
         <li class="nav-item menu-open">
            <a href="{{route('dashboard')}}" class="nav-link active">
               <i class="nav-icon fas fa-tachometer-alt"></i>
               <p>
                  Dashboard
                  <i class="right fas fa-angle-left"></i>
               </p>
            </a>
         </li>


         <li class="nav-item">
            <a href="{{route('show.categories')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Category
               </p>
            </a>

         </li>
         <li class="nav-item">
            <a href="{{route('show.products')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Product
               </p>
            </a>

         </li>
         <li class="nav-item">
            <a href="{{route('show.suppliers')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Supplier
               </p>
            </a>

         </li>
         <!-- <li class="nav-item">
            <a href="{{route('show.customers')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Customer
               </p>
            </a>

         </li> -->
         <li class="nav-item">
            <a href="{{route('show.transaction')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Sales
               </p>
            </a>

         </li>



      </ul>
   </nav>
   <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
         <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
         <a href="{{route('logout')}}" class="d-block">Logout</a>
      </div>
   </div>
   <!-- /.sidebar-menu -->
</div>