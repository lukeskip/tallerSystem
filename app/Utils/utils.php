<?php
namespace App\Utils;

use Carbon\Carbon;

class Utils 
{
    public static function formatDate($date){
        return Carbon::parse($date)->translatedFormat('D d/m Y');
    }
}