<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('techinical', function (Blueprint $table) {
          $table->id(); // id (primary key)

            // Foreign keys
            $table->unsignedBigInteger('procedureId'); // tramite / procedure
            $table->unsignedBigInteger('dependeceAssignedId'); // dependencia asignada
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');

            // Personal information
            $table->string('firstName');
            $table->string('paternalSurname');
            $table->string('maternalSurname')->nullable();
            $table->string('fullName')->storedAs('CONCAT(firstName, " ",paternalSurname, " ", maternalSurname)');

            // Address information
            $table->string('street');
            $table->integer('number');
            $table->integer('city');
            $table->string('section');
            $table->integer('postalCode');
            $table->string('municipality');
            $table->string('locality');
            $table->string('reference')->nullable();

            // Contact
            $table->string('cellphone');

            // Task details
            $table->text('requestDescription');
            $table->text('solutionDescription')->nullable();

            // Status
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('techinical');
    }
};
