<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Exceptions\ShopException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\Wallets;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class CustomerController extends CoreController
{
    use Wallets;
    public $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
//        $limit = $request->limit ?   $request->limit : 15;
        $data = User::permission(Permission::CUSTOMER)->with(['profile', 'address', 'permissions'])->get();
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function show($id)
    {
        try {
            return $this->repository->with(['profile', 'address'])->findOrFail($id);
        } catch (Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }
}
