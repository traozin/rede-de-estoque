<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $this->populateRoles();

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(3); // Usuário comum
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }

    private function populateRoles() {
        // Verifica se os roles já existem ou insere novos
        \DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Administrador'],
            ['id' => 2, 'name' => 'Operador'],
            ['id' => 3, 'name' => 'Usuário comum'],
        ]);
    }
};
