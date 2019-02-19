<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    public function images(){
        return $this->hasMany('\App\ReportImage');
    }

    public function notifications(){
        return $this->hasMany('\App\Notification');
    }
}
