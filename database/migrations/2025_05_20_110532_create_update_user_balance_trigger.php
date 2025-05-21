<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration {
  /*  public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER update_user_balance_after_ticket_update
            AFTER UPDATE ON tickets
            FOR EACH ROW
            BEGIN
                IF NEW.payout IS NOT NULL THEN
                    UPDATE users
                    SET balance = balance + NEW.payout
                    WHERE id = NEW.user_id;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS update_user_balance_after_ticket_update");
    }*/
};

