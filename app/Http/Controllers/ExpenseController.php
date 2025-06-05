<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('category', 'user')->orderBy('expense_date', 'desc');

        if ($request->filled('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('paid_to', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $request->search . '%');
            });
        }

        $expenses = $query->paginate(15);
        $categories = ExpenseCategory::orderBy('name')->get(); // For filter dropdown

        return view('pages.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('pages.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'paid_to' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('expenses.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Assign current user

        Expense::create($data);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('pages.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'paid_to' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('expenses.edit', $expense->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->all();
        // user_id typically doesn't change on edit, or can be updated if needed
        // $data['user_id'] = Auth::id();

        $expense->update($data);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete(); // Soft delete if enabled
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}