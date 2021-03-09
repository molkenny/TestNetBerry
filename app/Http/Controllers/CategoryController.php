<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategories(Request $request){
        try {
            $categories = Category::All();
            return response()->json(
                [
                    'success' => true,
                    'data' => $categories
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'msg' => $e->getMessage(),
                    'code' => 100
                ]
            , 500);
        }
    }
}
