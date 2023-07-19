<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use App\Traits\Wallets;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\Permission;
use App\Events\OrderCreated;
use App\Models\Shop;
use App\Models\User;
use App\Exports\OrderExport;
use Illuminate\Http\JsonResponse;
use App\Models\Order;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Refund;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use App\Models\Balance;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Settings;
use App\Exceptions\ShopException;
use Illuminate\Support\Facades\Session;
use App\Models\DownloadToken;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Repositories\OrderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderController extends CoreController
{
    use Wallets;
    public $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Order[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 10;
        return $this->fetchOrders($request)->paginate($limit)->withQueryString();
//            ->paginate($limit)->withQueryString();
    }

    public function fetchOrders(Request $request)
    {
        $user = $request->user();
        if(isset($request->fromDate) && isset($request->toDate))
        {
            $from = Carbon::parse($request->fromDate);
            $fromDate = $from->format('Y-m-d H:i:s');
            $to = Carbon::parse($request->toDate);
            $toDate = $to->format('Y-m-d H:i:s');
            //super admin user
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
                return $this->repository->with('children')->where('id', '!=', null)->where('parent_id', '=', null)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            }
            else if ($this->repository->hasPermission($user, $request->shop_id)) {
                //shop owner user
                if ($user && $user->hasPermissionTo(Permission::STORE_OWNER)) {
                return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate); //->paginate($limit);
            }
                // shop staff user
                elseif ($user && $user->hasPermissionTo(Permission::STAFF)) {
                return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null)->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate); //->paginate($limit);
            }
            }

//            return $this->repository->with('children')->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
        }

                if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
                    return $this->repository->with('children')->where('id', '!=', null)->where('parent_id', '=', null); //->paginate($limit);
                }
                else if($this->repository->hasPermission($user, $request->shop_id))
                {
                    if ($user && $user->hasPermissionTo(Permission::STORE_OWNER)) {
                        return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
                    } elseif ($user && $user->hasPermissionTo(Permission::STAFF)) {
                        return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
                    }
                }
                else
                {
                    return $this->repository->with('children')->where('customer_id', '=', $user->id)->where('parent_id', '=', null); //->paginate($limit);
                }
//        return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null);

//        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN) && (!isset($request->shop_id) || $request->shop_id === 'undefined')) {
//            if(isset($request->fromDate) && isset($request->toDate))
//            {
//                return Order::with('children')->where('id', '!=', null)->where('parent_id', '=', null)->whereBetween('created_at',[$fromDate,$toDate]);
//            }
//            else
//            {
//                return $this->repository->with('children')->where('id', '!=', null)->where('parent_id', '=', null); //->paginate($limit);
//            }
//        } else if ($this->repository->hasPermission($user, $request->shop_id)) {
//            if ($user && $user->hasPermissionTo(Permission::STORE_OWNER)) {
//                return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
//            } elseif ($user && $user->hasPermissionTo(Permission::STAFF)) {
//                return $this->repository->with('children')->where('shop_id', '=', $request->shop_id)->where('parent_id', '!=', null); //->paginate($limit);
//            }
//        } else {
//            return $this->repository->with('children')->where('customer_id', '=', $user->id)->where('parent_id', '=', null); //->paginate($limit);
//        }


//            ->whereBetween('created_at',[$fromDate,$toDate]);
//            ->where('parent_id', '!=', null)->whereDate('created_at', '>=', $request->fromDate)->whereDate('created_at', '<=', $request->toDate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderCreateRequest $request
     * @return LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function store(OrderCreateRequest $request)
    {
        return $this->repository->storeOrder($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $params)
    {
        $user = $request->user() ?? null;
        try {
            $order = $this->repository->with(['products', 'status', 'children.shop', 'wallet_point'])->where('id', $params)->orWhere('tracking_number', $params)->firstOrFail();
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
        if (!$order->customer_id) {
            return $order;
        }
        if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return $order;
        } elseif (isset($order->shop_id)) {
            if ($user && ($this->repository->hasPermission($user, $order->shop_id) || $user->id == $order->customer_id)) {
                return $order;
            }
        } elseif ($user && $user->id == $order->customer_id) {
            return $order;
        } else {
            throw new ShopException(NOT_AUTHORIZED);
        }
    }
    public function findByTrackingNumber(Request $request, $tracking_number)
    {
        $user = $request->user() ?? null;
        try {
            $order = $this->repository->with(['products', 'status', 'children.shop', 'wallet_point'])->findOneByFieldOrFail('tracking_number', $tracking_number);
            if ($order->customer_id === null) {
                return $order;
            }
            if ($user && ($user->id === $order->customer_id || $user->can('super_admin'))) {
                return $order;
            } else {
                throw new ShopException(NOT_AUTHORIZED);
            }
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(OrderUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateOrder($request);
    }


    public function updateOrder(Request $request)
    {
        try {
            $order = $this->repository->findOrFail($request->id);
        } catch (\Exception $e) {
            throw new ShopException(NOT_FOUND);
        }
        $user = $request->user();
        if (isset($order->shop_id)) {
            if ($this->repository->hasPermission($user, $order->shop_id)) {
                return $this->changeOrderStatus($order, $request->status);
            }
        } else if ($user->hasPermissionTo(Permission::SUPER_ADMIN) || $user->hasPermissionTo(Permission::STAFF)) {
            return $this->changeOrderStatus($order, $request->status);
        } else {
            throw new ShopException(NOT_AUTHORIZED);
        }
    }

    public function changeOrderStatus($order, $status)
    {
        $order->status = $status;
        $order->save();
        try {
            $children = json_decode($order->children);
        } catch (\Throwable $th) {
            $children = $order->children;
        }
        if (is_array($children) && count($children)) {
            foreach ($order->children as $child_order) {
                $child_order->status = $status;
                $child_order->save();
            }
        }
        return $order;
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

    /**
     * Export order dynamic url
     *
     * @param Request $request
     * @param int $shop_id
     * @return string
     */
    public function exportOrderUrl(Request $request, $shop_id = null)
    {
        $user = $request->user();

        if ($user && !$this->repository->hasPermission($user, $request->shop_id)) {
            throw new ShopException(NOT_AUTHORIZED);
        }

        $dataArray = [
            'user_id' => $user->id,
            'token'   => Str::random(16),
            'payload' => $request->shop_id
        ];
        $newToken = DownloadToken::create($dataArray);

        return route('export_order.token', ['token' => $newToken->token]);
    }

    /**
     * Export order to excel sheet
     *
     * @param string $token
     * @return void
     */
    public function exportOrder($token)
    {
        $shop_id = 0;
        try {
            $downloadToken = DownloadToken::where('token', $token)->first();

            $shop_id = $downloadToken->payload;
            if ($downloadToken) {
                $downloadToken->delete();
            } else {
                return ['message' => TOKEN_NOT_FOUND];
            }
        } catch (Exception $e) {
            throw new ShopException(TOKEN_NOT_FOUND);
        }

        try {
            return Excel::download(new OrderExport($this->repository, $shop_id), 'orders.xlsx');
        } catch (Exception $e) {
            return ['message' => NOT_FOUND];
        }
    }
}
