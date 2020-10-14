<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function getCategoryNameAttribute() {

        $category = Category::find($this->category);
        return $category->category_name;
    }
}
