<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\{Request, Response};

class CategoryController extends Controller
{
    protected $rules = [
        'category_name' => 'required|max:255',
        // 'user_id' => 'required|integer',
    ];

    protected $messages = [
        'category_name.required' => 'Category name is required',
        'category_name.max' => 'Category name may not be greater than 255 characters',
        // 'user_id.required' => 'User id is required',
        // 'user_id.integer' => 'User id must be an integer'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$categories = Category::whereUserId(\Auth::id())
                            ->orderBy('category_name')
                            ->withCount('albums')
                            ->paginate(10);*/

        // Dato che abbiamo messo la relazione dentro al modello User...
        // $categories = \Auth::user()->categories()->paginate(10);

        // Usiamo le "query scope"
        $categories = Category::getCategoriesByUserId(\Auth::user())->paginate(10);

        $category = new Category();

        return view('categories.index', compact('categories', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create', ['category' => new Category() ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $res = Category::create([
            'category_name' => $request->category_name,
            'user_id' => \Auth::id()
        ]);

        $message = $res ? "Category created" : "Problem creating category";

        session()->flash('message', $message);

        // Controllo se la richiesta è stata fatta con ajax o meno.
        if($request->ajax()) {
            return [
                'message' => $message,
                'success' => $res
            ];
        }
        else {
            return redirect()->route('categories.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.create')->withCategory($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // $this->validate($request, $this->rules);
        $category->category_name = $request->category_name;

        $res = $category->save();

        $message = $res ? "Category updated" : "Problem updating category";

        session()->flash('message', $message);

        // Controllo se la richiesta è stata fatta con ajax o meno.
        if($request->ajax()) {
            return [
                'message' => $message,
                'success' => $res
            ];
        }
        else {
            return redirect()->route('categories.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Request $request)
    {
        $res = $category->delete();

        $message = $res ? "Category deleted" : "Problem deleting category";

        session()->flash('message', $message);

        // Controllo se la richiesta è stata fatta con ajax o meno.
        if($request->ajax()) {
            return [
                'message' => $message,
                'success' => $res
            ];
        }
        else {
            return redirect()->route('categories.index');
        }
    }
}
