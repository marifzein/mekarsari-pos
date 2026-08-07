<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $table = 'footer_settings';
    protected $fillable = ['section_left', 'section_center', 'section_right'];
}
