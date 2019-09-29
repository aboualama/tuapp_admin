<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyApp extends Model
{
    protected $fillable = [
        'appname', 'logoapp', 'splashscreen', 'appidentificationkey'
    ];

    protected $appends = ['logo_path', 'splash_path']; 

    public function getLogoPathAttribute()
    {
        return asset('uploads/logo/' . $this->logoapp);

    } 

    public function getSplashPathAttribute()
    {
        return asset('uploads/splash/' . $this->splashscreen);

    }
}
