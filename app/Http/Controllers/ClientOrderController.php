<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientOrderRequest;
use App\Models\ClientOrder;
use App\Repositories\ClientOrderRepository\ClientOrderRepository;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    //
    protected $clientOrder;
    public function __construct(ClientOrderRepository $clientOrder)
    {
        $this->clientOrder=$clientOrder;
    }

    public function store(ClientOrderRequest $request)
    {
        return $this->clientOrder->add($request);
    }

    public function workerOrder(){
        $orders=ClientOrder::with(['post','client'])->where('status','pending')->whereHas('post',function ($query){
           $query->where('worker_id',auth()->guard('worker')->id());
        })->get();
        return response()->json([
            'orders'=>$orders
        ]);
    }

    public function update(ClientOrder $clientOrder, Request $request)
    {
      return $this->clientOrder->update($clientOrder,$request);
    }

}
