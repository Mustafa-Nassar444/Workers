<?php

namespace App\Repositories\ClientOrderRepository;

use App\Http\Requests\ClientOrderRequest;
use App\Models\ClientOrder;
use Illuminate\Http\Request;

class ClientOrderModelRepository implements ClientOrderRepository
{
    public function add(ClientOrderRequest $request){
        $data = $request->all();
        $data['client_id'] = auth()->guard('client')->id();
        $exists = ClientOrder::where('client_id', $data['client_id'])->where('post_id', $request->post_id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Duplicated order']);
        }
        $order = ClientOrder::create($data);

        return response()->json(['message' => 'Order created successfully']);
    }

    public function update(ClientOrder $clientOrder, Request $request)
    {
        // TODO: Implement update() method.
        $clientOrder->setAttribute('status',$request->status)->save();
        return response()->json([
            'message'=>'Status updated successfully',
        ]);
    }

}
