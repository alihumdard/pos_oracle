<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;

class CategoryController extends Controller
{
      // Add Category
      public function category_add(Request $request)
      {

          $request->validate([
              'name' => 'required|unique:categories,name',
          ]);
  
          $category = new Category();
          $category->name = $request->name;
          $category->save();
  
          return response()->json(['message' => 'Category added successfully!', 'category' => $category]);
      }
  
      // Show Categories
      public function category_show()
      {
        $categories = Category::orderBy('id', 'DESC')->get();
          return view('pages.category.show', compact('categories'));
      }
  
      // Edit Category (fetch data for editing)
      public function category_edit($id)
      {
          $category = Category::findOrFail($id);
          return response()->json($category);
      }
  
      // Update Category
      public function category_update(Request $request, $id)
      {
          $category = Category::findOrFail($id);
          $category->name = $request->name;
  
          $category->save();
  
          return response()->json(['message' => 'Category updated successfully!', 'category' => $category]);
      }
  
      // Delete Category
      public function category_delete($id)
      {
          $category = Category::findOrFail($id);
          $category->delete();
  
          return response()->json(['message' => 'Category deleted successfully!']);
      }
     
}
