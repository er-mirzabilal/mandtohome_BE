<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Repositories\TaxRepository;
use App\Exceptions\ShopException;
use Prettus\Validator\Exceptions\ValidatorException;

class TaxController extends CoreController
{
    public $repository;

    public function __construct(TaxRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|Type[]
     */
    public function index(Request $request)
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaxRequest $request
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function store(CreateTaxRequest $request)
    {
        $validateData = $request->validated();
        return $this->repository->create($validateData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateTaxRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTaxRequest $request, $id)
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
