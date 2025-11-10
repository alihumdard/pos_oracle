<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .sidebars {
  height: 100%;
  min-height: 100vh;
  overflow-y: auto;
}
    </style>
</head>

<body class="bg-gray-100">

    <button id="mobile-toggle-btn"
        class="fixed top-4 left-4 z-50 py-2 px-3 rounded bg-[#343a40] text-white border border-[#4b545c] shadow-sm lg:hidden hover:bg-gray-700 transition-colors">
        <i class="fas fa-bars"></i>
    </button>

    <div id="sidebar-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden transition-opacity duration-300 ease-in-out opacity-0">
    </div>

    <aside id="main-sidebar"
        class="fixed top-0 left-0 sidebars w-[250px] bg-[#343a40] text-[#c2c7d0] z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 shadow-xl lg:shadow-none flex flex-col">

        <div class="flex items-center justify-between p-4 border-b border-[#4b545c]">
            <a href="{{route('dashboard')}}" class="flex items-center hover:text-white transition-colors">
                <img src="{{ asset('assets/logo/logo.jpg') }}" alt="Logo"
                    class="h-8 w-8 rounded-full opacity-80 mr-3 shadow-md">
                <span class="text-lg font-light">Rana Electronics</span>
            </a>
            <button id="sidebar-close-btn" class="lg:hidden mb-10 text-[#c2c7d0] hover:text-white focus:outline-none">
                <i class="fas fa-times text-md"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-transparent p-2">
            <nav class="mt-2">
                <ul class="space-y-1" role="menu">

                    <li>
                        <a href="{{route('reports.dashboard')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.dashboard') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt w-6 text-center mr-2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('show.transaction')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('show.transaction') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-book w-6 text-center mr-2"></i>
                            <p>Sale</p>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('show.customers')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('show.customers') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-users w-6 text-center mr-2"></i>
                            <p>Customer</p>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('show.suppliers')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('show.suppliers') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-truck w-6 text-center mr-2"></i>
                            <p>Supplier</p>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('show.categories')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('show.categories') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-list-alt w-6 text-center mr-2"></i>
                            <p>Category</p>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('show.products')}}"
                            class="flex items-center px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('show.products') ? 'bg-[#007bff] text-white' : '' }}">
                            <i class="nav-icon fas fa-box w-6 text-center mr-2"></i>
                            <p>Product</p>
                        </a>
                    </li>

                    @php $reportRoutes = ['reports.sales', 'reports.products', 'reports.customers', 'reports.purchases_suppliers', 'reports.expenses']; @endphp
                    <li class="nav-item-dropdown" data-is-active="{{ Route::is($reportRoutes) ? 'true' : 'false' }}">
                        <a href="#"
                            class="nav-link-toggle flex items-center justify-between px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is($reportRoutes) ? 'bg-[#007bff] text-white' : '' }}">
                            <div class="flex items-center">
                                <i class="nav-icon fas fa-chart-line w-6 text-center mr-2"></i>
                                <p>Reports</p>
                            </div>
                            <i class="fas fa-angle-left transition-transform duration-300"></i>
                        </a>
                        <ul class="nav-treeview ml-4 mt-1 space-y-1 hidden overflow-hidden transition-all duration-300 ease-in-out">
                            <li>
                                <a href="{{ route('reports.sales') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.sales') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Sales Report</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.products') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.products') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Product Report</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.customers') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.customers') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Customer Report</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.purchases_suppliers') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.purchases_suppliers') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Purchases & Suppliers</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.expenses') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('reports.expenses') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Expenses Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php $purchaseRoutes = ['purchase.index', 'purchase.create']; @endphp
                    <li class="nav-item-dropdown" data-is-active="{{ Route::is($purchaseRoutes) ? 'true' : 'false' }}">
                        <a href="#"
                            class="nav-link-toggle flex items-center justify-between px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is($purchaseRoutes) ? 'bg-[#007bff] text-white' : '' }}">
                            <div class="flex items-center">
                                <i class="nav-icon fas fa-shopping-cart w-6 text-center mr-2"></i>
                                <p>Purchases</p>
                            </div>
                            <i class="fas fa-angle-left transition-transform duration-300"></i>
                        </a>
                        <ul class="nav-treeview ml-4 mt-1 space-y-1 hidden">
                            <li>
                                <a href="{{ route('purchase.create') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('purchase.create') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Add Purchase</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('purchase.index') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('purchase.index') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Purchase List</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @php $expenseRoutes = ['expenses.index', 'expenses.create', 'expense_categories.index']; @endphp
                    <li class="nav-item-dropdown" data-is-active="{{ Route::is($expenseRoutes) ? 'true' : 'false' }}">
                        <a href="#"
                            class="nav-link-toggle flex items-center justify-between px-4 py-2 rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is($expenseRoutes) ? 'bg-[#007bff] text-white' : '' }}">
                            <div class="flex items-center">
                                <i class="nav-icon fas fa-file-invoice-dollar w-6 text-center mr-2"></i>
                                <p>Expenses</p>
                            </div>
                            <i class="fas fa-angle-left transition-transform duration-300"></i>
                        </a>
                        <ul class="nav-treeview ml-4 mt-1 space-y-1 hidden">
                            <li>
                                <a href="{{ route('expenses.index') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('expenses.index') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Manage Expenses</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('expenses.create') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('expenses.create') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Add New Expense</p>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('expense_categories.index') }}"
                                    class="flex items-center px-4 py-2 text-sm rounded-md hover:bg-[rgba(255,255,255,0.1)] hover:text-white transition-colors {{ Route::is('expense_categories.index') ? 'bg-[rgba(255,255,255,0.1)] text-white' : '' }}">
                                    <i class="far fa-circle nav-icon w-4 text-center mr-2 text-xs"></i>
                                    <p>Expense Categories</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>

        <div class="p-4 border-t border-[#4b545c] flex items-center">
            <img src="{{asset('assets/dist/img/user2-160x160.jpg')}}" alt="User Image"
                class="h-9 w-9 rounded-full mr-3">
            <div>
                <a href="{{route('logout')}}" class="block hover:text-white transition-colors">Logout</a>
            </div>
        </div>
    </aside>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('mobile-toggle-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');
            const dropdowns = document.querySelectorAll('.nav-item-dropdown');

            // --- 1. Mobile Toggle Logic ---
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10); // Fade in overlay
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300); // Wait for fade out
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', (e) => { e.stopPropagation(); openSidebar(); });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => { e.stopPropagation(); closeSidebar(); });
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // --- 2. Dropdown Menu Logic ---
            dropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.nav-link-toggle');
                const menu = dropdown.querySelector('.nav-treeview');
                const arrow = toggle.querySelector('.fa-angle-left');
                const isActive = dropdown.getAttribute('data-is-active') === 'true';

                if (isActive) {
                    menu.classList.remove('hidden');
                    arrow.classList.add('-rotate-90');
                }

                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    menu.classList.toggle('hidden');
                    arrow.classList.toggle('-rotate-90');
                });
            });

            // --- 3. Auto-Scroll to Active Link ---
            const activeLink = document.querySelector(".bg-\\[\\#007bff\\]");
            if (activeLink) {
                setTimeout(() => {
                    activeLink.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        });
    </script>
</body>

</html>