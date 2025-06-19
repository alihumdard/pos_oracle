<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
     
     public function supplier_add(Request $request)
     {
    
    
         $supplier = new Supplier();
         $supplier->supplier = $request->supplier;
         $supplier->contact_person = $request->contact_person;
         $supplier->address = $request->address;
         $supplier->contact_no = $request->contact_no;
         $supplier->note = $request->note;
         $supplier->save();
 
         return response()->json(['message' => 'Supplier added successfully!', 'Supplier' => $supplier]);
     }
 
   
     public function supplier_show()
     {
       $suppliers = Supplier::orderBy('id', 'DESC')->get();
         return view('pages.supplier.show', compact('suppliers'));
     }
 
     
     public function supplier_edit($id)
     {
         $supplier = Supplier::findOrFail($id);
         return response()->json($supplier);
     }
 
     public function Supplier_update(Request $request, $id)
     {
         $supplier = Supplier::findOrFail($id);
         $supplier->supplier = $request->supplier;
         $supplier->contact_person = $request->contact_person;
         $supplier->address = $request->address;
         $supplier->contact_no = $request->contact_no;
         $supplier->note = $request->note;
         $supplier->save();
 
         return response()->json(['message' => 'Supplier updated successfully!']);
     }
 
     // Delete Supplier
     public function supplier_delete($id)
     {
         $Supplier = Supplier::findOrFail($id);
         $Supplier->delete();
 
         return response()->json(['message' => 'Supplier deleted successfully!']);
     }
}
