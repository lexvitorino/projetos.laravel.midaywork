<?php

namespace App\Models;

use App\Helpers\DateUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class License extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // chamados pela view

    function formatDateWithLocale($date)
    {
        return DateUtils::formatDateWithLocale($date, '%d/%m/%Y');
    }

    function formatStatus($variable)
    {
        $format = '';
        switch ($variable) {
            case 'forecast':
                $format = "Previsto";
                break;
            case 'denied':
                $format = "Negado";
                break;
            case 'approved':
                $format = "Aprovado";
                break;
            case 'processed':
                $format = "Processado";
                break;
            default:
                $format = "";
                break;
        }
        return $format;
    }

    function formatType($variable)
    {
        $format = '';
        switch ($variable) {
            case 'vacation':
                $format = "Férias";
                break;
            case 'others':
                $format = "Outros";
                break;
            default:
                $format = "";
                break;
        }
        return $format;
    }
}
