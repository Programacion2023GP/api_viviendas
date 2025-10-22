<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserPermission;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;

class UserPermissionController extends Controller
{
    public function assignPermissions(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'permissions' => 'required|array|min:1',
                'permissions.*' => 'exists:permissions,id'
            ]);

            $validator->validate();

            $this->saveUserPermissions($request->user_id, $request->permissions);

            return ApiResponse::success(null, 'Permisos asignados correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error('Error al asignar permisos: ' . $e->getMessage(), 500);
        }
    }

    /**
     * 🔹 Método reutilizable sin necesidad de Request
     */
    public function saveUserPermissions($userId, array $permissions)
    {
        DB::beginTransaction();

        try {
            // Elimina permisos antiguos
            UserPermission::where('id_user', $userId)->delete();

            // Inserta nuevos
            $data = collect($permissions)->map(function ($permissionId) use ($userId) {
                return [
                    'id_user' => $userId,
                    'id_permission' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            UserPermission::insert($data->toArray());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // se propaga el error al método que lo llama
        }
    }
}
