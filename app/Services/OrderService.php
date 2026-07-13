<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;

class OrderService
{
    public function create($request)
    {
        $order = Order::create($request);
        
        if (isset($request['categories']) && is_array($request['categories'])) {
            $categoryIds = [];
            foreach ($request['categories'] as $cat) {
                if (is_array($cat)) {
                    // Existing category from vue-multiselect could be an object, or we might receive simple strings
                    $catName = $cat['name'] ?? null;
                    $catId = $cat['id'] ?? null;
                } else {
                    $catName = $cat;
                    $catId = is_numeric($cat) ? $cat : null;
                }

                if ($catId && Category::find($catId)) {
                    $categoryIds[] = $catId;
                } elseif ($catName) {
                    $category = Category::firstOrCreate([
                        'name' => $catName,
                        'invoice_id' => $request['invoice_id']
                    ]);
                    $categoryIds[] = $category->id;
                }
            }
            $order->categories()->sync($categoryIds);
        }
        
        return $order;
    }

    public function edit($id)
    {
        $order = Order::with(['categories', 'files'])->find($id);

        $orderFormatted = [
            'invoice_id' => ['value' => $order->invoice_id, 'type' => 'hidden'],
            'description' => ['value' => $order->description, 'type' => 'string'],
            'unit_cost' => ['value' => $order->unit_cost, 'type' => 'money'],
            'units' => ['value' => $order->units, 'type' => 'integer'],
            'has_iva' => ['value' => $order->has_iva, 'type' => 'boolean'],
            'provider_id' => ['value' => $order->provider_id, 'type' => 'select'],
            'categories' => ['value' => $order->categories->toArray(), 'type' => 'categories'],
            'image' => ['value' => $order->files->first()?->url, 'type' => 'file'],
        ];

        $fields = Utils::getFields('orders');
        
        $invoiceCategories = \App\Models\Category::where('invoice_id', $order->invoice_id)->get()->toArray();
        foreach ($fields as &$field) {
            if (isset($field['slug']) && $field['slug'] === 'categories') {
                $field['options'] = $invoiceCategories;
                break;
            }
        }

        return ["item" => $orderFormatted, "fields" => $fields];
    }

    public function update($order, $request)
    {
        $order->update($request);
        
        if (isset($request['categories']) && is_array($request['categories'])) {
            $categoryIds = [];
            foreach ($request['categories'] as $cat) {
                if (is_array($cat)) {
                    $catName = $cat['name'] ?? null;
                    $catId = $cat['id'] ?? null;
                } else {
                    $catName = $cat;
                    $catId = is_numeric($cat) ? $cat : null;
                }

                if ($catId && Category::find($catId)) {
                    $categoryIds[] = $catId;
                } elseif ($catName) {
                    $category = Category::firstOrCreate([
                        'name' => $catName,
                        'invoice_id' => $order->invoice_id
                    ]);
                    $categoryIds[] = $category->id;
                }
            }
            $order->categories()->sync($categoryIds);
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
        $orders = Order::with(['provider', 'categories', 'invoice'])->orderBy('created_at', 'desc');

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
