<?php
namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Services\InvoiceService;


class Utils 
{
    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public static function formatDate($date){
        return Carbon::parse($date)->translatedFormat('D d/m Y');
    }

    public static function getFields($table){
        $fields = Schema::getColumnListing($table);
        $fieldsEnd = [];
        $fieldsToExclude = ['created_at','updated_at','id'];
        $fieldsToHide = ['invoice_id'];
        foreach ($fields as $index => $field) {
            if(!in_array($field, $fieldsToExclude)){
                if($field === 'provider_id'){
                    $providerService = new InvoiceService();
                    $providers = $providerService->getProviders();
                    $fieldsEnd[] = ['slug'=>$field,'type'=> 'select','label'=>Utils::getLabel($field),'options'=>$providers];
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
}