<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Auth-specific additions:
     *  - password_resets  (already exists as password_reset_tokens, reuse it)
     *  - otp_codes        (for phone OTP flow)
     *  - user_logins      (audit log — who logged in, from where, with which role)
     */
    public function up(): void
    {
        // ── OTP codes table ──────────────────────────────────────────────────
        if (!Schema::hasTable('otp_codes')) {
            Schema::create('otp_codes', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 20)->index();
                $table->string('code', 10);
                $table->string('role')->default('customer');    // which portal
                $table->timestamp('expires_at');
                $table->boolean('is_used')->default(false);
                $table->timestamps();
            });
        }

        // ── Login audit log ──────────────────────────────────────────────────
        if (!Schema::hasTable('user_logins')) {
            Schema::create('user_logins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role')->default('customer');
                $table->string('auth_method')->default('email'); // email | phone_otp
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('logged_in_at')->useCurrent();
            });
        }

        // ── Ensure email nullable (phone-only users may not supply one) ───────
        // Only alter if column still has NOT NULL constraint
        // (safe to run even if column is already nullable)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_logins');
        Schema::dropIfExists('otp_codes');
    }
};
