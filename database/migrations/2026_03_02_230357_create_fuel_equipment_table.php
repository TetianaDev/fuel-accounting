<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void {
		Schema::create( 'fuel_equipment', function ( Blueprint $table ) {
			$table->id();
            $table->foreignId( 'asset_id' )->constrained( 'assets' );
            $table->date('date');
            $table->integer( 'time_worked')->nullable();
            $table->integer( 'fuel_used')->nullable();
            $table->integer( 'fuel_remaining_start');
            $table->integer( 'fuel_filling')->nullable();
            $table->integer( 'fuel_remaining_end');
            $table->enum( 'fuel_source' , ['Автотранс', 'Склад']);
			$table->timestamps();
		} );
	}

	public function down(): void {
		Schema::dropIfExists( 'fuel_equipment' );
	}
};
