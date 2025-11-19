<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;

Route::get('/run-commands', [DashboardController::class, 'runMigrations']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/invoice',function(){
return view('pages.customer.invoice');
});
Route::get('/invoice/print',function(){
return view('pages.customer.invoice_print');
})->name('invoice.print');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/dashboard', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes
Route::post('/category/add', [CategoryController::class, 'category_add'])->name('add.categories');
Route::get('/category/show', [CategoryController::class, 'category_show'])->name('show.categories');
Route::get('categories/{id}', [CategoryController::class, 'category_edit']);
Route::put('/categories/{id}', [CategoryController::class, 'category_update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'category_delete'])->name('categories.delete');

Route::get('/product/show', [ProductController::class, 'product_show'])->name('show.products');
Route::post('/product/add', [ProductController::class, 'product_add'])->name('add.products');
Route::delete('/products/{id}',[ProductController::class,'product_delete'])->name('product.delete');
Route::get('/all/products',[ProductController::class,'product_all'])->name('product_all');
Route::get('products/{id}', [ProductController::class, 'product_edit']);
Route::put('/products/{id}', [ProductController::class, 'products_update'])->name('products.update');
Route::get('/generate/product', [ProductController::class, 'products_pdf'])->name('generate.product');
Route::get('/supplier/category/filter', [ProductController::class,'supplier_category_filter'])->name('supplier_category.filter');


Route::post('/supplier/add', [SupplierController::class, 'supplier_add'])->name('add.suppliers');
Route::get('/supplier/show', [SupplierController::class,'supplier_show'])->name('show.suppliers');
Route::get('suppliers/{id}', [SupplierController::class, 'supplier_edit']);
Route::put('/suppliers/{id}', [SupplierController::class, 'supplier_update'])->name('suppliers.update');
Route::delete('/supplier/{id}', [SupplierController::class, 'supplier_delete'])->name('supplier.delete');

Route::get('/customer/show', [CustomerController::class,'customer_show'])->name('show.customers');
Route::post('/customer/add', [CustomerController::class,'customer_add'])->name('add.customers');
Route::get('/customer/filter', [CustomerController::class,'customer_filter'])->name('customer.filter');

// Route::post('/supplier/add', [SupplierController::class, 'supplier_add'])->name('add.suppliers');
// Route::get('suppliers/{id}', [SupplierController::class, 'supplier_edit']);
// Route::put('/suppliers/{id}', [SupplierController::class, 'supplier_update'])->name('suppliers.update');
Route::delete('/supplier/{id}', [SupplierController::class, 'supplier_delete'])->name('supplier.delete');
Route::get('/customer/view/{id}', [CustomerController::class, 'view'])->name('customer.view');
Route::get('/sales/{id}/detail', [CustomerController::class, 'detail'])->name('sales.detail');
Route::get('/transaction/show/{id?}', [TransactionController::class,'transaction_show'])->name('show.transaction');
Route::post('/store-transaction',[TransactionController::class, 'store_transaction'])->name('transaction.sales');
Route::delete('/transaction/{id}',[TransactionController::class, 'transaction_delete'])->name('sales.delete');
Route::post('/sale/store',[TransactionController::class, 'sale_store'])->name('sale.store');
Route::get('/search-customer', [TransactionController::class, 'search'])->name('search.customer');

Route::get('/customer/invoice/{id?}/{cash?}/{credit?}/{debit?}', [TransactionController::class, 'invoice'])->name('pages.customer.invoice');
Route::get('generate-pdf/{id}', [TransactionController::class, 'generatePDF'])->name('generate-pdf');
// Route::get('test',function()
Route::get('/customer/{id}/sales-summary', [CustomerController::class, 'salesSummary'])->name('customer.sales.summary');
Route::post('/sale/update/{id}', [TransactionController::class, 'update_sale'])->name('sale.update');
Route::put('/transaction/{id}', [TransactionController::class, 'update_transaction'])->name('transaction.update');
Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
// return view('pages.customer.pdf_generate');
// });
Route::get('/sales', [SaleController::class, 'index'])->name('sale.index');
// New Routes for Reports Module
Route::prefix('reports')->group(function () {
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('/customers', [ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/purchases-suppliers', [ReportController::class, 'purchasesAndSuppliers'])->name('reports.purchases_suppliers');
    Route::get('/expenses', [ReportController::class, 'expensesReport'])->name('reports.expenses');
});
// ... other routes

// <-- NEW ROUTE FOR MODAL -->
Route::get('/supplier/{supplier}/purchase-summary', [PurchaseController::class, 'getSupplierPurchaseSummary'])->name('supplier.purchase.summary');

// Purchase Routes
Route::prefix('purchases')->name('purchase.')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('index'); // Route to list purchases
    Route::get('/create', [PurchaseController::class, 'create'])->name('create');
    Route::post('/store', [PurchaseController::class, 'store'])->name('store');
    Route::get('/product-details/{id}', [PurchaseController::class, 'getProductDetails'])->name('product.details');
    Route::get('/get-products-by-supplier/{id}', [PurchaseController::class, 'getProductsBySupplier'])->name('products-by-supplier');    
    
    // <-- ADD THESE TWO LINES FOR EDIT FUNCTIONALITY -->
    Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('edit');
    Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('update');
});

Route::middleware('auth')->group(function () { // Assuming expenses should be behind auth
    Route::resource('expense_categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);
});

// In routes/web.php
Route::get('/payments/create', [App\Http\Controllers\SupplierPaymentController::class, 'create'])->name('payment.create');
Route::post('/payments/store', [App\Http\Controllers\SupplierPaymentController::class, 'store'])->name('payment.store');


Route::post('/customer/recovery/add', [CustomerController::class, 'addRecoveryDate']);
Route::post('/customer/recovery/delete', [CustomerController::class, 'deleteRecoveryDate']);
Route::post('/customer/recovery/reminder', [CustomerController::class, 'sendRecoveryReminder']);