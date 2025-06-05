<style>
@media (max-width: 990px) {
  .sidebars {
    display: none;
  }
}
</style>

<div class="sidebars sidebar">
  
   <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
         <li class="nav-item menu-open">
            <a href="{{route('reports.dashboard')}}" class="nav-link active">
               
               <p>
                  Dashboard
                  <i class="right fas fa-angle-left"></i>
               </p>
            </a>
         </li>

           <li class="nav-item">
            <a href="{{route('show.transaction')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Sales
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
         <li class="nav-item">
            <a href="{{route('show.customers')}}" class="nav-link">
               <i class="nav-icon fas fa-book"></i>
               <p>
                  Customer
               </p>
            </a>

         </li>
          <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-chart-line"></i> {{-- Icon for Reports --}}
                    <p>
                        Reports
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('reports.dashboard') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Dashboard Overview</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.sales') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sales Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.products') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Product Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.customers') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Customer Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.purchases_suppliers') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Purchases & Suppliers</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="{{ route('reports.expenses') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Expenses Report</p>
                        </a>
                     </li>
                </ul>
            </li>

            <li class="nav-item">
               <a href="#" class="nav-link"> {{-- Or make it a direct link if no sub-menu --}}
                  <i class="nav-icon fas fa-shopping-cart"></i> {{-- Example Icon --}}
                  <p>
                        Purchases
                        <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                        <a href="{{ route('purchase.create') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Add Purchase</p>
                        </a>
                  </li>
                  <li class="nav-item">
                        <a href="{{ route('purchase.index') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Purchase List</p>
                        </a>
                  </li>
               </ul>
            </li>
            {{-- ... other sidebar items ... --}}

            <li class="nav-item">
               <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-file-invoice-dollar"></i> {{-- Example Icon --}}
                  <p>
                        Expenses
                        <i class="right fas fa-angle-left"></i>
                  </p>
               </a>
               <ul class="nav nav-treeview">
                  <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Manage Expenses</p>
                        </a>
                  </li>
                  <li class="nav-item">
                        <a href="{{ route('expenses.create') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Add New Expense</p>
                        </a>
                  </li>
                  <li class="nav-item">
                        <a href="{{ route('expense_categories.index') }}" class="nav-link">
                           <i class="far fa-circle nav-icon"></i>
                           <p>Expense Categories</p>
                        </a>
                  </li>
               </ul>
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
 
</div>