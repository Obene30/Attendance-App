<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subcategory;



class Group extends Model
{
    protected $fillable = ['name', 'category', 'subcategories'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function externalMembers()
{
    return $this->hasMany(ExternalMember::class);
}

public function subcategories()
{
    return $this->hasMany(Subcategory::class);
}


}
