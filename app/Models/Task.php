<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

class Task extends Model
{
    use SortableTrait;

    protected $fillable = ['title', 'position'];
}
