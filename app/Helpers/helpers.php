<?php

use App\Models\Category;

function get_all_category() {
    return Category::all();
}