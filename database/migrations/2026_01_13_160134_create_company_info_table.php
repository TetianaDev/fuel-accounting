<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void {
		Schema::create( 'company_info', function ( Blueprint $table ) {
			$table->id();
            $table->string( 'name' );
            $table->integer('edrpou_code');
            $table->string( 'address' );
            $table->string( 'create_report_position' );
            $table->string( 'create_report_name' );
            $table->string( 'check_report_position' );
            $table->string( 'check_report_name' );
			$table->timestamps();
		} );
	}

	public function down(): void {
		Schema::dropIfExists( 'company_info' );
	}
};
