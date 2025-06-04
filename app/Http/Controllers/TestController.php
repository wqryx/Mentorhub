<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function checkTable()
    {
        $columns = Schema::getColumnListing('mentor_sessions');
        $tableInfo = DB::select('DESCRIBE mentor_sessions');
        
        return response()->json([
            'columns' => $columns,
            'table_info' => $tableInfo
        ]);
    }
}
