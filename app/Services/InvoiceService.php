<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Provider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Utils\Utils;


class InvoiceService
{

    protected string $mainRoute;

    public function __construct()
    {
        $this->mainRoute = 'cotizaciones.index';
    }



    public function create()
    {
        return $fields = Utils::getFields('projects');
    }

    public function edit($id)
    {
        $invoice =  Invoice::find($id);
        $invoice =  [
            'status' => ['value' => $invoice->status, 'type' => 'select'],
            'iva' => ['value' => $invoice->iva, 'type' => 'number'],
            'fee' => ['value' => $invoice->fee, 'type' => 'number'],
            'agent_comission' => ['value' => $invoice->agent_comission, 'type' => 'number'],
        ];

        $fields = Utils::getFields('invoices');

        return ["item" => $invoice, "fields" => $fields];
    }

    public function store($request)
    {
        $request['id'] = Utils::generateInvoiceId();
        return $invoice =  Invoice::create($request);
    }

    public function update($id, $request)
    {

        $invoice = Invoice::find($id);
        if (!$request['iva']) {
            $request['iva'] = 0;
        }
        return $invoice->update($request);
    }

    public function getItemCategories($id)
    {
        $invoice = Invoice::find($id);
        $categories = [];
        foreach ($invoice->categories as $category) {
            $categories[] = $category->name;
        }
        return $categories;
    }

    public function delete($id)
    {
        $invoice = Invoice::find($id);
        $projectID = $invoice->project_id;
        $invoice->delete($id);

        return $projectID;
    }

    public function getById($id)
    {
        $invoice = Invoice::with([
            'incomes',
            'project',
            'outcomes',
            'invoiceItems' => function ($query) {
                $query
                    ->join('categories', 'invoice_items.category_id', '=', 'categories.id')
                    ->orderBy('categories.order', 'asc')
                    ->orderBy('invoice_items.category_id', 'asc')
                    ->orderBy('invoice_items.created_at', 'desc')
                    ->select('invoice_items.*');
            }
        ])->find($id);


        if ($invoice) {

            $invoiceItems = $invoice->invoiceItems
                ->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "label" => $item->label,
                        "description" => $item->description,
                        "units" => $item->units,
                        "category" => $item->category->name ?? '',
                        "unit_cost" => Utils::publishMoney($item->unit_cost),
                        "unit_price" => Utils::publishMoney($item->unit_price),
                        "percentage_profit" => Utils::publishPercentage($item->percentage_profit),
                        "total_profit" => Utils::publishMoney($item->total_profit),
                        "total" => Utils::publishMoney($item->total),
                        "total_raw" => $item->total,
                        "provider" => $item->provider->name ?? '',
                        "agent" => $item->user->name ?? '',
                    ];
                });

            $debtsByProvider = $invoice->invoiceItems()
                ->with('provider')
                ->selectRaw('provider_id, providers.name as provider_name, SUM(units * unit_price) as total_amount')
                ->join('providers', 'providers.id', '=', 'invoice_items.provider_id')
                ->whereNotNull('providers.id')
                ->orderBy('provider_id')
                ->groupBy('provider_id', 'providers.name')
                ->get();


            $debtsByProvider->transform(function ($item) use ($invoice) {
                $totalPaid = $invoice->outcomes
                    ->where('provider_id', $item['provider_id'])
                    ->where('status', 'completed')
                    ->sum('amount');

                $totalAmount = $item['total_amount'];
                unset($item['total_amount']);

                if ($invoice->iva > 0) {
                    $iva = $invoice->iva / 100;
                    $amountIVA = $totalAmount * $iva;
                } else {
                    $amountIVA =  0;
                }

                $item['id'] = $item['provider_id'];
                $item['subtotal'] = Utils::publishMoney($totalAmount);
                $item['iva'] = Utils::publishMoney($amountIVA);
                $item['total_paid'] = Utils::publishMoney($totalPaid);
                $item['total_amount'] = Utils::publishMoney($totalAmount + $amountIVA);
                $item['balance'] = Utils::publishMoney(($totalAmount + $amountIVA) - $totalPaid);

                unset($item['provider']);
                unset($item['provider']);
                unset($item['provider_id']);
                return $item;
            });

            $incomes = $invoice->incomes->map(function ($item) {
                return [
                    "id" => $item->id,
                    "description" => $item->description,
                    "type" => $item->type,
                    "amount" => Utils::publishMoney($item->amount),
                    "reference" => $item->reference,
                    "image" => $item->image,
                    "invoice_id" => $item->invoice_id,
                    "date" => $item->format_date,
                ];
            });

            $outcomes = $invoice->outcomes->map(function ($item) {
                return [
                    "id" => $item->id,
                    "provider" => $item->provider->name,
                    "description" => $item->description,
                    "type" => $item->type,
                    "amount" => Utils::publishMoney($item->amount),
                    "reference" => $item->reference,
                    "image" => $item->image,
                    "status" => $item->status,
                    "date" => $item->format_date,

                ];
            });

            $comissions = $invoice->invoiceItems
                ->groupBy('user_id')
                ->map(function ($items, $userId) use ($invoice) {
                    $user = $items->first()->user ?? null;
                    if (!$user) {
                        return null;
                    }
                    $comission = $items->sum('agent_comission');

                    return [
                        "id" => $userId,
                        "user" => $user->name,
                        "total" => Utils::publishMoney($comission),
                    ];
                })
                ->filter()
                ->values();

            return [
                'id' => $invoice->id,
                'project' => $invoice->project,
                'categories' => $invoice->categories,
                'status' => $invoice->status,
                'comission' => $invoice->project->comission,
                'client' => $invoice->project->client->name,
                'invoiceItems' => $invoiceItems,
                'executive' => $invoice->project->user->name ?? null,
                'incomes' => $incomes,
                'outcomes' => $outcomes,
                'comissions' => $comissions,
                'debts' => $debtsByProvider,
                'balance' => Utils::publishMoney($invoice->balance),
                "subtotal" => Utils::publishMoney($invoice->subtotal),
                "fee_amount" => Utils::publishMoney($invoice->fee_amount),
                "total" => Utils::publishMoney($invoice->total),
                "iva_amount" => Utils::publishMoney($invoice->iva_amount),
                "iva" => Utils::publishPercentage($invoice->iva),
                "fee" => Utils::publishPercentage($invoice->fee),
                "subtotal_fee" => Utils::publishMoney($invoice->subtotal_fee),
                "amount_paid" => Utils::publishMoney($invoice->amount_paid),
                'format_date' => $invoice->format_date,
            ];
        } else {
            return null;
        }
    }

    public function getAll($request)
    {

        $invoices = Invoice::with('project')->orderBy('id', 'desc');

        if ($request &&  $request->input('search')) {
            $invoices->where('id', 'like', '%' . $request->input('search') . '%')
                ->orWhereHas('project', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('search') . '%');
                });
        }

        $invoices = $invoices->paginate();

        $invoices->getCollection()->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'file' => $invoice->id,
                'project' => $invoice->project->name,
                'client' => $invoice->project->client->name,
                'subtotal' => Utils::publishMoney($invoice->subtotal),
                'iva_amount' => Utils::publishMoney($invoice->iva_amount),
                'amount_paid' => Utils::publishMoney($invoice->amount_paid),
                'total' => Utils::publishMoney($invoice->total),
                'format_date' => $invoice->format_date,
            ];
        });


        return $invoices;
    }

    public function getProviders()
    {

        $providers = Provider::all();

        $providers->transform(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'contact_name' => $provider->contact_name,
            ];
        });

        return $providers;
    }

    public function getInvoices()
    {
        $invoices = Invoice::all();

        $invoices->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'name' => $invoice->project->name . " " . $invoice->project->client->name,
            ];
        });

        return $invoices;
    }

    public function comissionsByUser($invoiceId, $userId)
    {
        $invoice = Invoice::with(['invoiceItems.category', 'invoiceItems.provider', 'invoiceItems.user'])
            ->find($invoiceId);

        if (!$invoice) {
            return [];
        }

        $comissions = $invoice->invoiceItems
            ->where('user_id', $userId)
            ->map(function ($item) use ($invoice) {
                return [
                    "id" => $item->id,
                    "label" => $item->label,
                    "description" => $item->description,
                    "units" => $item->units,
                    "category" => $item->category->name ?? '',
                    "unit_cost" => Utils::publishMoney($item->unit_cost),
                    "unit_price" => Utils::publishMoney($item->unit_price),
                    "percentage_profit" => Utils::publishPercentage($item->percentage_profit),
                    "total_profit" => Utils::publishMoney($item->total_profit),
                    "total" => Utils::publishMoney($item->total),
                    "total_raw" => $item->total,
                    "provider" => $item->provider->name ?? '',
                    "agent_comission_percentage" => Utils::publishPercentage($invoice->agent_comission),
                    "agent_comission" => Utils::publishMoney($item->agent_comission),
                    "agent_comission_raw" => $item->agent_comission,
                ];
            })
            ->values();

        return $comissions;
    }
}
