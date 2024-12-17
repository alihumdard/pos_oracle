<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Add Category
    public function customer_add(Request $request)
    {
       //  $request->validate([
       //      'name' => 'required|unique:categories,name',
       //  ]);
   
        $customer = new Customer();
        $customer->cnic          =   $request->cnic;
        $customer->address       =   $request->address;
        $customer->name          =   $request->name;
        $customer->mobile_number =   $request->mobile_number;
        $customer->debit         =   $request->debit;
        $customer->credit        =   $request->credit;

        $customer->save();

        return response()->json(['message' => 'Customer added successfully!', 'Customer' => $Customer]);
    }

    // Show Categories
    public function customer_show()
    {
      $data['products']=Product::all();  
      $data['customers'] = Customer::orderBy('id', 'DESC')->get();
        return view('pages.customer.show', $data);
    }

    // Edit Customer (fetch data for editing)
    public function Customer_edit($id)
    {
        $Customer = Customer::findOrFail($id);
        return response()->json($Customer);
    }

    // Update Customer
    public function Customer_update(Request $request, $id)
    {
        $Customer = Customer::findOrFail($id);
        $Customer->Customer = $request->Customer;
        $Customer->contact_person = $request->contact_person;
        $Customer->address = $request->address;
        $Customer->contact_no = $request->contact_no;
        $Customer->note = $request->note;
        $Customer->save();

        return response()->json(['message' => 'Customer updated successfully!']);
    }

    // Delete Customer
    public function Customer_delete($id)
    {
        $Customer = Customer::findOrFail($id);
        $Customer->delete();

        return response()->json(['message' => 'Customer deleted successfully!']);
    }
}
