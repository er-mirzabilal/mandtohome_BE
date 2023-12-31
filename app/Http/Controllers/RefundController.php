<?php

namespace App\Http\Controllers;

use App\Events\QuestionAnswered;
use App\Events\RefundApproved;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\Order;
use App\Models\Wallet;
use App\Repositories\RefundRepository;
use App\Enums\Permission;
use App\Enums\RefundStatus;
use App\Exceptions\ShopException;
use App\Http\Requests\RefundRequest;
use App\Traits\Wallets;

class RefundController extends CoreController
{
    use Wallets;

    public $repository;

    public function __construct(RefundRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Type[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit;
        return $this->fetchRefunds($request)->paginate($limit)->withQueryString();
    }

    public function fetchRefunds(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
            return $this->repository->with(['order', 'shop', 'customer'])->where('id', '!=', null)->where('shop_id', '=', null);
        } else if ($this->repository->hasPermission($user, $request->shop_id)) {
            return $this->repository->with(['order', 'shop', 'customer'])->where('shop_id', '=', $request->shop_id);
        } else if ($user && $user->hasPermissionTo(Permission::CUSTOMER)) {
            return $this->repository->with(['order', 'shop', 'customer'])->where('customer_id', $user->id)->where('shop_id', null);
        }
        throw new ShopException(NOT_AUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RefundRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(RefundRequest $request)
    {
        if (!$request->user()) {
            throw new ShopException(NOT_AUTHORIZED);
        }
        return $this->repository->storeRefund($request);
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
            return $this->repository->with(['shop', 'order', 'customer'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->id = $id;
        return $this->updateRefund($request);
    }

    public function updateRefund(Request $request)
    {
        $user = $request->user();

        if ($this->repository->hasPermission($user)) {
            try {
                $refund = $this->repository->with(['shop', 'order', 'customer'])->findOrFail($request->id);
            } catch (\Exception $e) {
                throw new ShopException(NOT_FOUND);
            }
            if ($refund->status == RefundStatus::APPROVED) {
                throw new ShopException(ALREADY_REFUNDED);
            }
            $this->repository->updateRefund($request, $refund);
            if ($request->status == RefundStatus::APPROVED) {
                try {
                    $order = Order::findOrFail($refund->order_id);
                    foreach ($order->children as $childOrder) {
                        $balance = Balance::where('shop_id', $childOrder->shop_id)->first();
                        $balance->total_earnings = $balance->total_earnings - $childOrder->amount;
                        $balance->current_balance = $balance->current_balance - $childOrder->amount;
                        $balance->save();
                    }
                } catch (Exception $e) {
                    throw new ShopException(NOT_FOUND);
                }
                $wallet = Wallet::firstOrCreate(['customer_id' => $refund->customer_id]);
                $walletPoints = $this->currencyToWalletPoints($refund->amount);
                $wallet->total_points = $wallet->total_points + $walletPoints;
                $wallet->available_points = $wallet->available_points + $walletPoints;
                $wallet->save();

                // refund approved event
                event(new RefundApproved($refund));
            }
            return $refund;
        } else {
            throw new ShopException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request->id = $id;
        return $this->deleteRefund($request);
    }

    public function deleteRefund(Request $request)
    {
        try {
            $refund = $this->repository->findOrFail($request->id);
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
        if ($this->repository->hasPermission($request->user())) {
            $refund->delete();
            return $refund;
        } else {
            throw new ShopException(NOT_AUTHORIZED);
        }
    }
}
