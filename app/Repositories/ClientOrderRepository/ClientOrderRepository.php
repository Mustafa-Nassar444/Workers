<?php

namespace App\Repositories\ClientOrderRepository;

use App\Http\Requests\ClientOrderRequest;
use App\Models\ClientOrder;
use Illuminate\Http\Request;

interface ClientOrderRepository
{
    public function add(ClientOrderRequest $request);

    public function update(ClientOrder $clientOrder,  Request $request);

}
