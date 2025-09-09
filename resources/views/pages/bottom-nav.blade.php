<nav class="bottom-nav d-lg-none">
    <div class="bottom-nav-inner">
        <!-- Sale -->
        <a href="{{ route('show.transaction') }}" class="bottom-nav-item {{ request()->routeIs('sale.*') ? 'active' : '' }}">
            <i class="bi bi-cart-fill"></i>
            <span>Sale</span>
        </a>

        <!-- Customer -->
        <a href="{{ route('show.customers') }}" class="bottom-nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Customer</span>
        </a>

        <!-- Category -->
        <a href="{{ route('show.categories') }}" class="bottom-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i>
            <span>Category</span>
        </a>

        <!-- Product -->
        <a href="{{ route('show.products') }}" class="bottom-nav-item {{ request()->routeIs('product.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Product</span>
        </a>
    </div>
</nav>
