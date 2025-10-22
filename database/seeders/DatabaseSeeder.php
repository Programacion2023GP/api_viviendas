<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Insertar permisos
        DB::table('permissions')->insert([
            ['name' => 'Catalogos', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Usuarios', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CapturistaFichas', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AdministrarFichas', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reportes', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Insertar usuario administrador
        DB::table('users')->insert([
            [
                'firstName' => 'Admin',
                'paternalSurname' => "Admin",
                'maternalSurname' => "Admin",
                'email' => "admin@gomezpalacio.gob.mx",
                "password" => Hash::make("desarrollo"),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
            DB::table('dependence')->insert([
            [
                'name' => 'PRESIDENCIA',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DIF',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'EXPOFERIA',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'SIDEAPA',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'SIDEAPAR',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

        ]);
        // 3. Asignar permisos al usuario (tabla pivot permission_user)
        DB::table('user_permissions')->insert([
            ['id_user' => 1, 'id_permission' => 1, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id_user' => 1, 'id_permission' => 2, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            // ['id_user' => 1, 'id_permission' => 3, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id_user' => 1, 'id_permission' => 4, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id_user' => 1, 'id_permission' => 5, 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('procedure')->insert([
            [
                'name' => 'LOGICO',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NATURAL',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ARTIFICIAL',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        

        ]);
        $localities = [
    "aeropuerto","Ampliación","barrio","Campamento","colonia","condominio","Congregación",
    "Conjunto habitacional","ejido","equipamiento","Estación","Exhacienda","finca","fraccionamiento",
    "Gran usuario","granja","hacienda","localidad","paraje","Parque industrial","Poblado comunal",
    "pueblo","Puerto","rancheria","rancho","Residencial","unidad habitacional","Villa",
    "Zona comercial","zona federal","zona industrial","Zona militar","Zona naval"
];

$dependenceIds = [1,2,3,4,5]; // IDs de tu tabla dependence
$procedureIds = [1,2,3]; // IDs de tu tabla procedure

for ($i = 1; $i <= 30; $i++) {
    DB::table('techinical')->insert([
        'procedureId' => $procedureIds[array_rand($procedureIds)],
        'dependeceAssignedId' => $dependenceIds[array_rand($dependenceIds)],
        'userId' => 1, // administrador
        'firstName' => "Nombre$i",
        'paternalSurname' => "ApellidoP$i",
        'maternalSurname' => "ApellidoM$i",
        'street' => "Calle $i",
        'number' => rand(1, 500),
        'city' => rand(1, 100), // puedes reemplazarlo con IDs de ciudades reales
        'section' => "Seccion $i",
        'postalCode' => 35000,
        'municipality' => "Municipio $i",
        'locality' => $localities[array_rand($localities)],
        'reference' => "Referencia $i",
        'cellphone' => "5550000".str_pad($i, 3, "0", STR_PAD_LEFT),
        'requestDescription' => "Descripción de solicitud $i",
        'solutionDescription' => "Descripción de solución $i",
        'active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

    }
}
