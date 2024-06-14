<?php

namespace App\Http\Controllers;
use App\Models\Category;


use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function indexCategory(Request $req){
        $category= Category::get();
        return view('admin.category')->with("categories",$category);
    }
    
    public function addCategory(Request $req)
{
    $category= Category::all();
    return view('admin.addCategory')->with("categories",$category); // Display the form to add a new category
}

public function addNewCategory(Request $req)
{
    $category = new Category;
    $category->name = $req->name;
    
    // Check if the category name already exists
    $existingCategory = Category::where('name', $req->name)->first();
    if ($existingCategory) {
        return redirect()->back()->with('error', 'Category name already exists. Please choose a different name.');
    }
    
    $category->save();
    
    return redirect()->back()->with('success', 'Category added successfully');
}

    
    public function deleteCategory(Request $req)
    {
        $category = Category::find($req->id);
        
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }
        
        // Check if there are books that reference this category
        $booksCount = $category->books()->count();
        
        if ($booksCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete category. There are books that reference this category.');
        }
        
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
    
    public function editCategory(Request $req){
        $category = Category::find($req->id);
        // Check if there are books that reference this category
        $booksCount = $category->books()->count();
    
        if ($booksCount > 0) {
            return redirect()->back()->with('error', 'Cannot edit category. There are books that reference this category.');
        }
        return view('admin.editCategory')->with("categories",$category);
    }
    public function updateCategory(Request $req)
    {
        $category = Category::find($req->id);
        $newName = $req->name;

        // Check if the new category name already exists and it's not the current category's name
        $existingCategory = Category::where('name', $newName)
                                    ->where('id', '!=', $category->id)
                                    ->first();
        if ($existingCategory) {
            return redirect()->back()->with('error', 'Category name already exists. Please choose a different name.');
        }

        // Update the category name
        $category->name = $newName;
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    public function showBooksByCategory(Category $category)
    {
        $books = $category->books()->paginate(10);
        return view('dashboard', compact('books'));
    }

}
