<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ExpenseCategory::orderBy('name', 'asc');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $categories = $query->paginate(10);
        return view('pages.expenses.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.expenses.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('expense_categories.create')
                ->withErrors($validator)
                ->withInput();
        }

        ExpenseCategory::create($request->all());
        return redirect()->route('expense_categories.index')->with('success', 'Expense category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('pages.expenses.categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $expenseCategory->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('expense_categories.edit', $expenseCategory->id)
                ->withErrors($validator)
                ->withInput();
        }

        $expenseCategory->update($request->all());
        return redirect()->route('expense_categories.index')->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->expenses()->count() > 0) {
            return redirect()->route('expense_categories.index')->with('error', 'Category cannot be deleted because it is associated with expenses.');
        }
        $expenseCategory->delete();
        return redirect()->route('expense_categories.index')->with('success', 'Expense category deleted successfully.');
    }
}
