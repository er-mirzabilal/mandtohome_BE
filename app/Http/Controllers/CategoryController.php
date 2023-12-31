<?php


namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Exceptions\ShopException;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class CategoryController extends CoreController
{
    public $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @param Request $request
    //  * @return Collection|Category[]
    //  */
    // public function fetchOnlyParent(Request $request)
    // {
    //     $limit = $request->limit ?   $request->limit : 15;
    //     return $this->repository->withCount(['products'])->with(['type', 'parent', 'children'])->where('parent', null)->paginate($limit);
    //     // $limit = $request->limit ?   $request->limit : 15;
    //     // return $this->repository->withCount(['children', 'products'])->with(['type', 'parent', 'children.type', 'children.children.type', 'children.children' => function ($query) {
    //     //     $query->withCount('products');
    //     // },  'children' => function ($query) {
    //     //     $query->withCount('products');
    //     // }])->where('parent', null)->paginate($limit);
    // }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @param Request $request
    //  * @return Collection|Category[]
    //  */
    // public function fetchCategoryRecursively(Request $request)
    // {
    //     $limit = $request->limit ?   $request->limit : 15;
    //     return $this->repository->withCount(['products'])->with(['parent', 'subCategories'])->where('parent', null)->paginate($limit);
    // }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Category[]
     */
    public function index(Request $request)
    {
        $parent = $request->parent;
        $limit = $request->limit ?   $request->limit : 15;
        if ($parent === 'null') {
            return $this->repository->with(['type', 'parent', 'children'])->where('parent', null)->paginate($limit);
        } else {
            return $this->repository->with(['type', 'parent', 'children'])->paginate($limit);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryCreateRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(CategoryCreateRequest $request)
    {
        $validatedData = $request->validated();
        return $this->repository->create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($params)
    {
        try {
            return $this->repository->with(['type', 'parent', 'children'])->where('id', $params)->orWhere('slug', $params)->firstOrFail();
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            return $this->repository->findOrFail($id)->update($validatedData);
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            return $this->repository->findOrFail($id)->delete();
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }
}
