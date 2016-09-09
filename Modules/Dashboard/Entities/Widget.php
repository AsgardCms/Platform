<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['widgets', 'user_id'];
    protected $table = 'dashboard__widgets';

    public function user()
    {
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
    }
}
