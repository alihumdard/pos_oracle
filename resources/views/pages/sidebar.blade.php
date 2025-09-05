@include('pages.script')
<style>
    @media (max-width: 990px) {
        .sidebars {
            display: none;
        }
    }

    .main-sidebar {
        overflow-y: auto;
        max-height: 100vh;
    }
</style>

<div class=" sidebars sidebar ">

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item ">
                <a href="{{route('reports.dashboard')}}" class="nav-link {{ Route::is('reports.dashboard') ? 'active' : '' }}">

                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('show.transaction')}}" class="nav-link {{ Route::is('show.transaction') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Sale
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('show.customers')}}" class="nav-link {{ Route::is('show.customers') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Customer
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('show.suppliers')}}" class="nav-link {{ Route::is('show.suppliers') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Supplier
                    </p>
                </a>

            </li>

            <li class="nav-item">
                <a href="{{route('show.categories')}}" class="nav-link {{ Route::is('show.categories') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Category
                    </p>
                </a>

            </li>
            <li class="nav-item">
                <a href="{{route('show.products')}}" class="nav-link {{ Route::is('show.products') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Product
                    </p>
                </a>
            </li>

            @php
            $reportRoutes = [
            'reports.sales',
            'reports.products',
            'reports.customers',
            'reports.purchases_suppliers',
            'reports.expenses',
            ];
            @endphp

            <li class="nav-item {{ Route::is($reportRoutes) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is($reportRoutes) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Reports
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('reports.sales') }}" class="nav-link {{ Route::is('reports.sales') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sales Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.products') }}" class="nav-link {{ Route::is('reports.products') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Product Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.customers') }}" class="nav-link {{ Route::is('reports.customers') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Customer Report</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.purchases_suppliers') }}" class="nav-link {{ Route::is('reports.purchases_suppliers') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Purchases & Suppliers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.expenses') }}" class="nav-link {{ Route::is('reports.expenses') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Expenses Report</p>
                        </a>
                    </li>
                </ul>
            </li>
            @php
            $purchaseRoutes = ['purchase.index', 'purchase.create'];
            @endphp

            <li class="nav-item {{ Route::is($purchaseRoutes) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is($purchaseRoutes) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Purchases
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('purchase.create') }}" class="nav-link {{ Route::is('purchase.create') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add Purchase</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('purchase.index') }}" class="nav-link {{ Route::is('purchase.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Purchase List</p>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- ... other sidebar items ... --}}
            @php
            $expenseRoutes = ['expenses.index', 'expenses.create', 'expense_categories.index'];
            @endphp

            <li class="nav-item {{ Route::is($expenseRoutes) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is($expenseRoutes) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p>
                        Expenses
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ Route::is('expenses.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Expenses</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('expenses.create') }}" class="nav-link {{ Route::is('expenses.create') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New Expense</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('expense_categories.index') }}" class="nav-link {{ Route::is('expense_categories.index') ? 'active' : '' }}">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const activeLink = document.querySelector(".nav-link.active");

        if (activeLink) {
            // Optional: wrap inside a short timeout if CSS hasn't applied yet
            setTimeout(() => {
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'nearest'
                });
            }, 300); // Adjust delay if needed
        }
    });
</script>