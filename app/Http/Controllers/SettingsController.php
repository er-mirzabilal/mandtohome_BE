<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Repositories\SettingsRepository;
use App\Exceptions\ShopException;
use App\Http\Requests\SettingsRequest;
use Prettus\Validator\Exceptions\ValidatorException;

class SettingsController extends CoreController
{
    public $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Address[]
     */
    public function index(Request $request)
    {
        return $this->repository->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SettingsRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(SettingsRequest $request)
    {
        $settings = $this->repository->first();
        if (isset($settings->id)) {
            return $this->repository->update($request->only(['options']), $settings->id);
        } else {
            return $this->repository->create(['options' => $request['options']]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->repository->first();
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SettingsRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidatorException
     */
    public function update(SettingsRequest $request, $id)
    {
        $settings = $this->repository->first();
        if (isset($settings->id)) {
            return $this->repository->update($request->only(['options']), $settings->id);
        } else {
            return $this->repository->create(['options' => $request['options']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        throw new ShopException(ACTION_NOT_VALID);
    }
}
