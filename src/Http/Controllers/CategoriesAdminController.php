<?php

namespace Mixdinternet\Categories\Http\Controllers;

use Mixdinternet\Categories\Category;
use Illuminate\Http\Request;
use Caffeinated\Flash\Facades\Flash;
use Mixdinternet\Categories\Http\Requests\CreateEditCategoriesRequest;
use Mixdinternet\Admix\Http\Controllers\AdmixController;

class CategoriesAdminController extends AdmixController
{
    protected $categoryType;

    public function __construct()
    {
        $this->categoryType = request()->segment(2);
        view()->share('categoryType', $this->categoryType);
    }

    public function index(Request $request)
    {
        session()->put('backUrl', request()->fullUrl());

        $trash = ($request->segment(4) == 'trash') ? true : false;

        $query = Category::sort();
        $query->where('type', $this->categoryType);
        ($trash) ? $query->onlyTrashed() : '';

        $search = [];
        $search['name'] = $request->input('name', '');
        $search['status'] = $request->input('status', '');

        ($search['name']) ? $query->where('name', 'LIKE', '%' . $search['name'] . '%') : '';
        ($search['status']) ? $query->where('status', $search['status']) : '';

        $categories = $query->paginate(50);

        $view['search'] = $search;
        $view['categories'] = $categories;
        $view['trash'] = $trash;

        return view('mixdinternet/categories::admin.index', $view);
    }

    public function create(Category $category)
    {
        $view['category'] = $category;

        return view('mixdinternet/categories::admin.form', $view);
    }

    public function store(CreateEditCategoriesRequest $request)
    {
        $data = $request->all();
        $data['type'] = $this->categoryType;
        if (Category::create($data)) {
            Flash::success('Item inserido com sucesso.');
        } else {
            Flash::error('Falha no cadastro.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.' . $this->categoryType . '.categories.index');
    }

    public function edit(Category $category)
    {
        $view['category'] = $category;

        return view('mixdinternet/categories::admin.form', $view);
    }

    public function update(Category $category, CreateEditCategoriesRequest $request)
    {
        $data = $request->all();
        $data['type'] = $this->categoryType;
        if ($category->update($data)) {
            Flash::success('Item atualizado com sucesso.');
        } else {
            Flash::error('Falha na atualização.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.' . $this->categoryType . '.categories.index');
    }

    public function destroy(Request $request)
    {
        if (Category::destroy($request->input('id'))) {
            Flash::success('Item removido com sucesso.');
        } else {
            Flash::error('Falha na remoção.');
        }

        return ($url = session()->get('backUrl')) ? redirect($url) : redirect()->route('admin.categories.index');
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);

        if (!$category) {
            abort(404);
        }

        if ($category->restore()) {
            Flash::success('Item restaurado com sucesso.');
        } else {
            Flash::error('Falha na restauração.');
        }

        return back();
    }
}