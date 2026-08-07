<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ===============================
    // LIST
    // ===============================
    public function index(Request $request)
    {
        $categories = Category::query()

            ->when($request->search, function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%'.$request->search.'%'
                );

            })

            ->orderBy('name')

            ->paginate(15)

            ->withQueryString();

        return view(

            'categories.index',

            compact('categories')

        );
    }


    // ===============================
    // FORM CREATE
    // ===============================
    public function create()
    {
        return view('categories.create');
    }


    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([

            'name'=>

                'required|

                unique:categories,name',

            'description'=>

                'nullable|string',

            'is_active'=>

                'required|boolean'

        ]);

        Category::create([

            'name'=>$request->name,

            'description'=>$request->description,

            'is_active'=>$request->is_active

        ]);

        return redirect()

            ->route('categories.index')

            ->with(

                'success',

                'Kategori berhasil ditambahkan.'

            );
    }


    // ===============================
    // FORM EDIT
    // ===============================
    public function edit(Category $category)
    {
        return view(

            'categories.edit',

            compact('category')

        );
    }


    // ===============================
    // UPDATE
    // ===============================
    public function update(
        Request $request,
        Category $category
    )
    {
        $request->validate([

            'name'=>

                'required|

                unique:categories,name,'

                .$category->id,

            'description'=>

                'nullable|string',

            'is_active'=>

                'required|boolean'

        ]);

        $category->update([

            'name'=>$request->name,

            'description'=>$request->description,

            'is_active'=>$request->is_active

        ]);

        return redirect()

            ->route('categories.index')

            ->with(

                'success',

                'Kategori berhasil diperbarui.'

            );
    }
}