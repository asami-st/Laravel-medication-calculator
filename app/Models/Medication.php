<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    public function prescriptions()
    {
        return $this->belongsTo(Medication::class);
    }

    public function getMedicationName()
    {
        return "{$this->name}{$this->form}{$this->strength}";
    }
}
