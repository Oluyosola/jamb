<?php

use App\Models\DirectoryCompany;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCompanyLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_company_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DirectoryCompany::class)->constrained()->onDelete('cascade');
            $table->string('street_address');
            $table->string('address_landmark')->nullable();
            $table->unsignedBigInteger('local_government_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('website_url')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_company_locations');
    }
}
