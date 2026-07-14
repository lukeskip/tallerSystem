<?php

namespace App\Services;

use App\Models\Fabric;
use Illuminate\Http\Request;
use App\Utils\Utils;

class FabricService
{
    public function create($request)
    {
        $fabric = Fabric::create($request);
        return $fabric;
    }

    public function edit($id)
    {
        $fabric = Fabric::find($id);

        $fabricFormatted = [
            'invoice_id' => ['value' => $fabric->invoice_id, 'type' => 'hidden'],
            'provider_id' => ['value' => $fabric->provider_id, 'type' => 'select'],
            'brand' => ['value' => $fabric->brand, 'type' => 'string'],
            'pattern' => ['value' => $fabric->pattern, 'type' => 'string'],
            'color' => ['value' => $fabric->color, 'type' => 'string'],
            'units' => ['value' => $fabric->units, 'type' => 'decimal'],
        ];

        $fields = Utils::getFields('fabrics');
        
        return ["item" => $fabricFormatted, "fields" => $fields];
    }

    public function update($fabric, $request)
    {
        $fabric->update($request);
        return $fabric;
    }

    public function delete($id)
    {
        $fabric = Fabric::find($id);
        if ($fabric) {
            $fabric->delete();
        }
    }

    public function getById($id)
    {
        $fabric = Fabric::with(['provider'])->find($id);

        if ($fabric) {
            return [
                'id' => $fabric->id,
                'invoice_id' => $fabric->invoice_id,
                'provider_id' => $fabric->provider_id,
                'brand' => $fabric->brand,
                'pattern' => $fabric->pattern,
                'color' => $fabric->color,
                'units' => $fabric->units,
                'meters' => $fabric->meters,
            ];
        } else {
            return null;
        }
    }

    public function getAll($request = null)
    {
        $fabrics = Fabric::with(['provider', 'invoice'])->orderBy('created_at', 'desc');

        if ($request && $request->input('search')) {
            $fabrics->where(function($q) use ($request) {
                $q->where('brand', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('pattern', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('color', 'like', '%' . $request->input('search') . '%');
            });
        }

        if ($request && $request->input('invoice_id')) {
            $fabrics->where('invoice_id', $request->input('invoice_id'));
        }

        $fabrics = $fabrics->paginate();

        $fabrics->getCollection()->transform(function ($fabric) {
            return [
                'id' => $fabric->id,
                'invoice_id' => $fabric->invoice_id,
                'provider_id' => $fabric->provider_id,
                'provider_name' => $fabric->provider ? $fabric->provider->name : null,
                'brand' => $fabric->brand,
                'pattern' => $fabric->pattern,
                'color' => $fabric->color,
                'units' => $fabric->units,
                'meters' => $fabric->meters,
            ];
        });

        return $fabrics;
    }
}
