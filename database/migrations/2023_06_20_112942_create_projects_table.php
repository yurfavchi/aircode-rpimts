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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('project_name');
            $table->ulid('tracking_code');
            $table->unsignedBigInteger('call_for_proposal_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['Draft', 'Under Evaluation', 'For Revision', 'Approved', 'Deferred', 'Disapproved']);
            $table->text('research_group');
            $table->text('introduction');
            $table->text('aims_and_objectives');
            $table->text('background');
            $table->text('expected_research_contribution');
            $table->text('proposed_methodology');
            $table->text('workplan');
            $table->text('resources');
            $table->text('references');
            $table->decimal('total_budget', 10, 2)->default(0.00);
            $table->date('approval_date')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('call_for_proposal_id')->references('id')->on('call_for_proposals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
