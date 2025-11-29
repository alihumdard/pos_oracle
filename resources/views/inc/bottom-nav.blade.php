@include('inc.script')

<nav class="fixed bottom-0 left-0 z-50 w-full bg-white border-t border-gray-100 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] lg:hidden">
    
    <div class="grid grid-cols-4 gap-1 px-2 py-2">

        <a href="{{ route('show.transaction') }}" class="flex flex-col items-center justify-center gap-1 rounded-lg py-2 text-xs transition-all duration-200 {{ 
            request()->routeIs('sale.*') 
            ? 'bg-indigo-100 text-indigo-600' 
            : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600' 
        }}">
            <i class="bi bi-cart-fill text-2xl"></i>
            <span class="font-medium">Sale</span>
        </a>

        <a href="{{ route('show.customers') }}" class="flex flex-col items-center justify-center gap-1 rounded-lg py-2 text-xs transition-all duration-200 {{ 
            request()->routeIs('customers.*') 
            ? 'bg-indigo-100 text-indigo-600' 
            : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600' 
        }}">
            <i class="bi bi-people-fill text-2xl"></i>
            <span class="font-medium">Customer</span>
        </a>

        <a href="{{ route('show.categories') }}" class="flex flex-col items-center justify-center gap-1 rounded-lg py-2 text-xs transition-all duration-200 {{ 
            request()->routeIs('categories.*') 
            ? 'bg-indigo-100 text-indigo-600' 
            : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600' 
        }}">
            <i class="bi bi-tags-fill text-2xl"></i>
            <span class="font-medium">Category</span>
        </a>

        <a href="{{ route('show.products') }}" class="flex flex-col items-center justify-center gap-1 rounded-lg py-2 text-xs transition-all duration-200 {{ 
            request()->routeIs('product.*') 
            ? 'bg-indigo-100 text-indigo-600' 
            : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600' 
        }}">
            <i class="bi bi-box-seam text-2xl"></i>
            <span class="font-medium">Product</span>
        </a>

    </div>
</nav>