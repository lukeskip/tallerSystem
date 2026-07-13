<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;

class OrderService
{
    public function create($request)
    {
        $order = Order::create($request);
        
        if (isset($request['categories']) && is_array($request['categories'])) {
            $order->categories()->sync($request['categories']);
        }
        
        return $order;
    }

    public function edit($id)
    {
        $order = $this->getById($id);

        $orderFormatted = [
            'description' => ['value' => $order['description'], 'type' => 'string'],
            'unit_cost' => ['value' => $order['unit_cost'], 'type' => 'money'],
            'units' => ['value' => $order['units'], 'type' => 'integer'],
            'has_iva' => ['value' => $order['has_iva'], 'type' => 'boolean'],
            'provider_id' => ['value' => $order['provider_id'], 'type' => 'select'],
        ];

        $fields = Utils::getFields('orders');

        return ["item" => $orderFormatted, "fields" => $fields];
    }

    public function update(Order $order, array $data)
    {
        $order->update($data);
        
        if (isset($data['categories']) && is_array($data['categories'])) {
            $order->categories()->sync($data['categories']);
        }

        return $order;
    }

    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
        }
    }

    public function getById($id)
    {
        $order = Order::with(['provider', 'categories'])->find($id);

        if ($order) {
            return [
                'id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'provider_id' => $order->provider_id,
                'description' => $order->description,
                'unit_cost' => $order->unit_cost,
                'units' => $order->units,
                'has_iva' => $order->has_iva,
                'categories' => $order->categories->pluck('id')->toArray(),
            ];
        } else {
            return null;
        }
    }

    public function getAll($request = null)
    {
        $orders = Order::with(['provider', 'categories'])->orderBy('created_at', 'desc');

        if ($request && $request->input('search')) {
            $orders->where('description', 'like', '%' . $request->input('search') . '%');
        }

        if ($request && $request->input('invoice_id')) {
            $orders->where('invoice_id', $request->input('invoice_id'));
        }

        $orders = $orders->paginate();

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'provider_id' => $order->provider_id,
                'provider_name' => $order->provider ? $order->provider->name : null,
                'description' => $order->description,
                'unit_cost' => $order->unit_cost,
                'units' => $order->units,
                'has_iva' => $order->has_iva,
                'total' => $order->total,
                'categories' => $order->categories,
            ];
        });

        return $orders;
    }
}
