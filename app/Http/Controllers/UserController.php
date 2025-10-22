<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use App\Models\User;
use App\Models\UserPermission;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Si hay ID, es actualización; si no, es creación
            $isUpdate = $request->id > 0;

            // Reglas dinámicas
            $rules = [
                'firstName' => 'required|string|max:255',
                'paternalSurname' => 'required|string|max:255',
                'maternalSurname' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],
            ];

            // Si es creación, el email debe ser único
            if (!$isUpdate) {
                $rules['email'][] = 'unique:users,email';
            } else {
                // En actualización, el email debe ser único excepto para el mismo usuario
                $rules['email'][] = 'unique:users,email,' . $request->id;
            }

            $messages = [
                'email.unique' => 'El correo electrónico ya está registrado',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->validate();

            // Buscar o crear usuario
            $user = $isUpdate ? User::find($request->id) : new User();

            if ($isUpdate && !$user) {
                return ApiResponse::error('Usuario no encontrado', 404);
            }

            // Solo si es nuevo usuario, generar contraseña
            $rawPassword = null;
            if (!$isUpdate) {
                // $first = preg_replace('/\s+/', '', $request->firstName);
                // $paternal = preg_replace('/\s+/', '', $request->paternalSurname);
                // $maternal = preg_replace('/\s+/', '', $request->maternalSurname);

                // $rawPassword = substr($first, 0, 5)       // 5 primeras letras del nombre
                //     . substr($paternal, 0, 1)    // 1ra letra apellido paterno
                //     . substr($maternal, 0, 1)    // 1ra letra apellido materno
                //     . substr(bin2hex(random_bytes(1)), 0, 2); // 2 caracteres aleatorios
                $rawPassword = "123456";
                $user->password = Hash::make($rawPassword);
            }



            // Asignar datos comunes
            $user->firstName = $request->firstName;
            $user->paternalSurname = $request->paternalSurname;
            $user->maternalSurname = $request->maternalSurname;
            $user->email = $request->email;
            $user->active = 1;

            $user->save();
            // Dentro de register(), después de guardar $user:
            // Dentro de register(), después de guardar $user:
            if ($request->has('permissions')) {
                app(UserPermissionController::class)->saveUserPermissions(
                    $user->id,
                    $request->permissions
                );
            }


            // Token solo si es nuevo (opcional)
            $token = $user->createToken('auth_token')->plainTextToken;

            return ApiResponse::success([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'password' => $rawPassword, // solo lo verás si es creación
            ], $isUpdate ? 'Usuario actualizado con éxito' : 'Usuario registrado con éxito');
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return ApiResponse::error('El correo electrónico ya está registrado', 500);
            }
            return ApiResponse::error('Error en la base de datos: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            return ApiResponse::error('Error inesperado: ' . $e->getMessage(), 500);
        }
    }
    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Credenciales incorrectas', 401);
        }
        $permisos = DB::table('user_permissions')
            ->join('permissions', 'permissions.id', '=', 'user_permissions.id_permission')
            ->where('user_permissions.id_user', $user->id)
            ->pluck('permissions.name');        // Crear token
$token = $user->createToken('auth_token', $permisos->toArray())->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
            'permisos'=>$permisos,
            'token_type' => 'Bearer',
        ], 'Login exitoso');
    }

    /**
     * Logout (revocar token actual)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, 'Logout exitoso');
    }
    public function index()
    {
        try {
            $users = User::where("active", 1)->get();
            return ApiResponse::success(
                $users,
                'Lista de usuarios'
            );
        } catch (Error $th) {
            return ApiResponse::success(
                null,
                'No se pudo cargar los usuarios   '
            );
        }
    }
    public function destroy(Request $request)
    {
        try {
            $technical = User::find($request->id);

            if (!$technical) {
                return ApiResponse::error('Usuario no encontrado', 404);
            }

            $technical->update(['active' => false]);

            return ApiResponse::success(null, 'Usuario eliminado correctamente');
        } catch (Exception $e) {
            return ApiResponse::error('Error al eliminar el usuario', 500);
        }
    }
}
