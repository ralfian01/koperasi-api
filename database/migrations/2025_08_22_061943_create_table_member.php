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
        Schema::create('member', function (Blueprint $table) {
            $table->id();

            // # Relation to table Account
            $table->unsignedBigInteger('account_id')->nullable(false);
            $table->foreign('account_id')->references('id')->on('account')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->date('birth_date')->nullable(true);
            $table->enum('gender', ['M', 'F'])->nullable(true);
            $table->string('register_number', 20)->nullable(false);
            $table->string('nickname', 100)->nullable(false);
            $table->string('address_domicile')->nullable(true);
            $table->string('phone_number', 25)->nullable(true);
            $table->string('wa_number', 25)->nullable(true);
            $table->boolean('status_active')->nullable(true);
            $table->boolean('status_delete')->nullable(true);
            $table->json('meta_state')->nullable(true);

            $table->unsignedBigInteger('verified_by')->nullable(false)->comment('account id');
            $table->foreign('verified_by')->references('id')->on('account')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
        });

        // Schema::create('member__identity', function (Blueprint $table) {
        //     $table->id();

        //     // # Relation to table Member
        //     $table->unsignedBigInteger('member_id')->nullable(false);
        //     $table->foreign('member_id')->references('id')->on('member')
        //         ->onDelete('cascade')
        //         ->onUpdate('no action');

        //     $table->string('id_card_number', 17)->nullable(true);
        //     $table->string('full_name', 200)->nullable(true);
        //     $table->string('birth_place', 100)->nullable(true);
        //     $table->date('birth_date')->nullable(true);
        //     $table->enum('gender', ['M', 'F'])->nullable(true);
        //     $table->string('address')->nullable(true);
        //     $table->string('npwp', 50)->nullable(true);
        //     $table->string('photo_id_card')->nullable(true);

        //     $table->timestamps();
        // });

        Schema::create('member__request', function (Blueprint $table) {
            $table->id();

            // # Relation to table Member
            $table->unsignedBigInteger('member_id')->nullable(false);
            $table->foreign('member_id')->references('id')->on('member')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->string('type', 100)->nullable(true);
            $table->text('reason')->nullable(true);

            // # Relation to table Account
            $table->unsignedBigInteger('updated_by')->nullable(false);
            $table->foreign('updated_by')->references('id')->on('account')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->boolean('status_active')->nullable(true);

            $table->timestamps();
        });

        Schema::create('member__log', function (Blueprint $table) {
            $table->id();

            // # Relation to table Member
            $table->unsignedBigInteger('member_id')->nullable(false);
            $table->foreign('member_id')->references('id')->on('member')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // # Relation to table Member
            $table->unsignedBigInteger('created_by')->nullable(false)->comment('member id');
            $table->foreign('created_by')->references('id')->on('member')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->json('meta_data')->nullable(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
        Schema::dropIfExists('member__request');
        Schema::dropIfExists('member__identity');
        Schema::dropIfExists('member__log');
    }
};
