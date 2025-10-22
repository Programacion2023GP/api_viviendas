<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
       public function index()
    {
        try {
            $permission = Permission::where("active",1)->get();
            return ApiResponse::success($permission, 'permisos recuperados con éxito');
        } catch (Exception $e) {
            Log::error('Error al recuperar fichas técnicas: ' . $e->getMessage());
            return ApiResponse::error('Error al recuperar los permisos', 500);
        }
    }
}
