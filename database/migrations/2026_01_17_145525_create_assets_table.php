<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void {
		Schema::create( 'assets', function ( Blueprint $table ) {
			$table->id();
            $table->enum( 'asset_type' , ['Транспортні засоби', 'Спецтехніка та обладнання']);
            $table->enum( 'unit' , ['Полтава', 'Супрунівка', 'Решетилівка', 'Всі']);
            $table->string( 'asset_name');
            $table->string( 'asset_details');
            $table->string( 'vehicle_registration_number')->nullable();
            $table->enum( 'asset_status' , ['Власний', 'Орендований', 'Позичка']);
            $table->enum( 'vehicle_type' , ['Легковий', 'Вантажний'])->nullable();
            $table->enum( 'asset_fuel_type' , ['D', 'B', 'E']);
            $table->integer( 'asset_fuel_consumption_rate');
            $table->integer( 'vehicle_tank_volume')->nullable();
            $table->string( 'asset_owner');
            $table->string( 'contract')->nullable();
            $table->integer( 'contract_amount')->nullable();
            $table->date( 'intake_date')->nullable();
            $table->date( 'decommissioning_date')->nullable();
			$table->timestamps();
		} );
	}

	public function down(): void {
		Schema::dropIfExists( 'assets' );
	}
};
