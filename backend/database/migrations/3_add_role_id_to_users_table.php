<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $this->populate();

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

    /**
     * Popula a tabela de roles e adiciona um usuário "super" administrador
     */
    private function populate() {
        // Verifica se os roles já existem ou insere novos
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'Administrador'],
            ['id' => 2, 'name' => 'Operador'],
            ['id' => 3, 'name' => 'Usuário comum'],
        ]);

        // Adiciona um usuário administrador de exemplo
        DB::table('users')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Certifique-se de usar uma senha segura
                'role_id' => 1, // Administrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
};
