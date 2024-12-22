<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\PDF;

// use App\Models\Supplier;

class ProductController extends Controller
{
  public function product_show()
  {

    $data['products'] = Product::orderBy('id', 'DESC')->get();
    // $data['suppliers'] = Supplier::all();
    $data['categories'] = Category::all();
    $data['suppliers'] = Supplier::all();
    return view('pages.product.show',  $data);
  }
  public function product_all()
  {
    $data['products'] = Product::all();
    return view('pages.product.all_product', $data);
  }
  public function products_pdf()
  {
    $data['products'] = Product::all();

    $pdf = PDF::loadView('pages.product.product_pdf', $data);
    return $pdf->download('product.pdf');
  }
  public function product_add(Request $request)
  {
    // Validate input fields
    $request->validate([
      'item_code' => 'required|string|max:255|unique:products,item_code',
      'item_name' => 'required|string|max:255|unique:products,item_name',
      'selling_price' => 'required|numeric',
      'original_price' => 'required|numeric',
      'qty' => 'required|numeric',
    ]);

    // Create and save product
    $product = new Product();
    $product->item_code = $request->item_code;
    $product->supplier_id = $request->supplier_id;
    $product->category_id = $request->category_id;
    $product->item_name = $request->item_name;
    $product->selling_price = $request->selling_price;
    $product->original_price = $request->original_price;
    $product->qty = $request->qty;

    $product->save();

    // Return success response
    return response()->json(['message' => 'Product added successfully!'], 201);
  }
  public function product_delete($id)
  {
        $product = Product::findOrFail($id);
        $product->delete();

    return response()->json(['message' => 'Product deleted successfully!']);
  }
  public function product_edit($id)
  {

    $product = Product::findOrFail($id);
    return response()->json($product);
  }
  public function products_update(Request $request, $id)
  {

    $product = Product::findOrFail($id);

    $product->item_code = $request->item_code;
    $product->supplier_id = $request->supplier_id;
    $product->category_id = $request->category_id;
    $product->item_name = $request->item_name;
    $product->profit = $request->profit;
    $product->selling_price = $request->selling_price;
    $product->original_price = $request->original_price;
    $product->packeges = $request->packeges;
    $product->packing_price = $request->packing_price;
    $product->packing_qty = $request->packing_qty;
    $product->discount = $request->discount;
    $product->qty_sold = $request->qty_sold;
    $product->qty = $request->qty;
    $product->expiry_date = $request->expiry_date;
    $product->date_arrival = $request->date_arrival;
    $product->save();

    return response()->json(['message' => 'Product updated successfully!'], 200);
  }
}
