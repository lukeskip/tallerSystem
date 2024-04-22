<?php
namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Services\InvoiceService;
use App\Services\ClientService;
use App\Models\Invoice;


class Utils 
{
    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public static function formatDate($date){
        return Carbon::parse($date)->translatedFormat('D d/m Y');
    }

    public static function getFields($table,$id = false){
        $fields = Schema::getColumnListing($table);
        $fieldsEnd = [];
        $fieldsToExclude = ['created_at','updated_at','id','invoice_id'];
        $fieldsToHide = ['project_id'];

        foreach ($fields as $index => $field) {
            if(!in_array($field, $fieldsToExclude)){
                if($field === 'provider_id'){
                    $providerService = new InvoiceService();
                    $providers = $providerService->getProviders();
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>$providers];
                }elseif($field === 'client_id'){
                    $clientService = new ClientService();
                    $clients = $clientService->getClients();
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>$clients];
                }elseif($field === 'image'){
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'file','label'=>Utils::getLabel($field)];
                }elseif($field === 'type' && ($table === 'incomes' || $table === 'outcomes')){
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>[['id'=>'cash','name'=>Utils::getLabel('cash')],['id'=>'transfer','name'=>Utils::getLabel('transfer')],['id'=>'check','name'=>Utils::getLabel('check')]]];
                }elseif($field === 'status'){
                    if($table === 'incomes' || $table === 'outcomes'){
                        $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>[['id'=>'pending','name'=>Utils::getLabel('pending')],['id'=>'completed','name'=>Utils::getLabel('completed')]]];
                    }else{
                        $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>[['id'=>'pending','name'=>Utils::getLabel('pending')],['id'=>'completed','name'=>Utils::getLabel('completed')],['id'=>'rejected','name'=>Utils::getLabel('rejected')]]];
                    }
                }elseif($field === 'category' && $table === 'invoice_items' && $id){
                    $InvoiceService = new InvoiceService();
                    $categories = $InvoiceService->getItemCategories($id);
                    $fieldsEnd[] = ['slug'=>$field,'type'=> Schema::getColumnType($table, $field),'label'=>Utils::getLabel($field),'autocomplete'=>$categories];
                }elseif(in_array($field, $fieldsToHide)){
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'hidden','label'=>null];
                }else{
                    $fieldsEnd[] = ['slug'=>$field,'type'=> Schema::getColumnType($table, $field),'label'=>Utils::getLabel($field)];
                }

            }else{
                unset($fields[$index]);
            }
        }

        return $fieldsEnd;
    }

    public static function getLabel($slug){
        $strings = include app_path('utils/strings.php');
        if(isset($strings[$slug])){
            return $strings[$slug];
        }else{
            return $slug;
        }
    }

    public static function generateInvoiceId (){
        $year = date('Y');
    
        // Contar el número de facturas en el año actual
        $count = Invoice::where('id', 'like', "$year%")->count();

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
}