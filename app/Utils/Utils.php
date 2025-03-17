<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Services\InvoiceService;
use App\Services\ClientService;
use App\Models\Invoice;
use App\Models\User;
use App\Services\ProviderService;
use Spatie\Permission\Models\Role;


class Utils
{
    private $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public static function formatDate($date)
    {
        return Carbon::parse($date)->translatedFormat('D d/m Y');
    }

    public static function getFields($table, $id = false)
    {
        $fields = Schema::getColumnListing($table);
        $token = csrf_token();
        $fieldsEnd = [['slug' => '_token', 'type' => 'hidden', 'value' => $token]];
        $fieldsToExclude = ['created_at', 'public_id', 'updated_at', 'id', 'invoice_id', 'deleted_at', 'password', 'email_verified_at', 'remember_token'];
        $fieldsToHide = ['project_id', 'user_id'];


        if ($table === 'files') {
            array_push($fieldsToExclude, 'name', 'extension', 'project_id', 'preview');
        }

        foreach ($fields as $index => $field) {
            if (!in_array($field, $fieldsToExclude)) {
                if ($field === 'provider_id') {
                    $providerService = new InvoiceService();
                    $providers = $providerService->getProviders();
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => $providers];
                } elseif ($field === 'client_id') {
                    $clientService = new ClientService();
                    $clients = $clientService->getClients();
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => $clients];
                } elseif ($field === 'image') {
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'file'];
                } elseif ($field === 'type' && ($table === 'incomes' || $table === 'outcomes')) {
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => [['id' => 'cash', 'name' => Utils::getLabel('cash')], ['id' => 'card', 'name' => Utils::getLabel('card')], ['id' => 'transfer', 'name' => Utils::getLabel('transfer')], ['id' => 'check', 'name' => Utils::getLabel('check')]]];
                } elseif ($field === 'url' && $table === 'files') {
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'file'];
                } elseif ($field === 'status') {
                    if ($table === 'incomes' || $table === 'outcomes') {
                        $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => [['id' => 'pending', 'name' => Utils::getLabel('pending')], ['id' => 'completed', 'name' => Utils::getLabel('completed')]]];
                    } else if ($table === 'notes') {
                        $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => [['id' => 'pending', 'name' => Utils::getLabel('pending')], ['id' => 'completed', 'name' => Utils::getLabel('completed')]]];
                    } else {
                        $fieldsEnd[] = ['slug' => $field, 'type' => 'select', 'options' => [['id' => 'pending', 'name' => Utils::getLabel('pending')], ['id' => 'completed', 'name' => Utils::getLabel('completed')], ['id' => 'rejected', 'name' => Utils::getLabel('rejected')]]];
                    }
                } elseif ($field === 'category_id' && $table === 'invoice_items' && $id) {
                    $InvoiceService = new InvoiceService();
                    $categories = $InvoiceService->getItemCategories($id);
                    $fieldsEnd[] = ['slug' => "category", 'type' => 'varchar', 'autocomplete' => $categories];
                } elseif ($field === 'user_id' && $table === 'invoice_items' && $id) {
                    $InvoiceService = new InvoiceService();
                    $users = User::all();
                    $fieldsEnd[] = ['slug' => "user_id", 'type' => 'select', 'options' => $users];
                } elseif (in_array($field, $fieldsToHide)) {
                    $fieldsEnd[] = ['slug' => $field, 'type' => 'hidden', 'label' => null];
                } else {
                    $fieldsEnd[] = ['slug' => $field, 'type' => Schema::getColumnType($table, $field)];
                }
            } else {
                unset($fields[$index]);
            }
        }

        if ($table === 'users') {
            $roles =  Role::all()->select('id', 'name');
            $fieldsEnd[] = ['slug' => 'role', 'type' => 'select', 'options' => $roles];
        }



        return $fieldsEnd;
    }

    public static function getLabel($slug)
    {
        $strings = include app_path('Utils/strings.php');
        if (isset($strings[$slug])) {
            return $strings[$slug];
        } else {
            return $slug;
        }
    }

    public static function generateInvoiceId()
    {
        $year = date('Y');

        // Contar el número de facturas en el año actual
        $count = Invoice::withTrashed()->where('id', 'like', "$year%")->count();

        // Generar el siguiente número de factura
        if ($count > 0) {
            $nextInvoiceNumber = $count + 1;
        } else {
            $nextInvoiceNumber = 1; // Si es la primera factura del año
        }

        // Formatear el número de factura con ceros a la izquierda si es necesario
        $nextInvoiceNumberFormatted = str_pad($nextInvoiceNumber, 4, '0', STR_PAD_LEFT);

        return $year . '_' . $nextInvoiceNumberFormatted;
    }

    public static function publishMoney($field)
    {
        return "$" . number_format(round($field));
    }
    public static function publishPercentage($field)
    {
        return number_format(round($field)) . "%";
    }
}
