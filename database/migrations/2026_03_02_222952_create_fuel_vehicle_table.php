<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void {
		Schema::create( 'fuel_vehicle', function ( Blueprint $table ) {
			$table->id();
            $table->foreignId( 'asset_id' )->constrained( 'assets' );
            $table->date('date');
            $table->string( 'route');
            $table->integer( 'mileage');
            $table->integer( 'fuel_consumption_by_norm');
            $table->integer( 'fuel_filling')->nullable();
            $table->integer( 'fuel_remaining');
            $table->enum( 'fuel_source' , ['Автотранс', 'Склад']);
			$table->timestamps();
		} );
	}

	public function down(): void {
		Schema::dropIfExists( 'fuel_vehicle' );
	}
};
