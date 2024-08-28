<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsCountTestGlobalProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
        DO $$
        BEGIN
            -- Elimina la función si ya existe
            IF EXISTS (SELECT 1 FROM pg_proc WHERE proname = 'subjects_count_test_global') THEN
                DROP FUNCTION subjects_count_test_global();
            END IF;
        END $$;

        CREATE OR REPLACE FUNCTION subjects_count_test_global()
        RETURNS TABLE(id INTEGER, name VARCHAR, repeticion INTEGER)
        LANGUAGE plpgsql
        AS $$
        BEGIN
            RETURN QUERY
            SELECT t1.id, t1.name, COALESCE(t2.rep, 0) as repeticion
            FROM (SELECT id, name FROM subjects) t1
            LEFT JOIN (
                SELECT ut.subject_id, COUNT(subject_id) as rep
                FROM user_tests ut
                RIGHT JOIN subjects s ON ut.subject_id = s.id
                WHERE completed = 1
                GROUP BY subject_id
            ) as t2
            ON t1.id = t2.subject_id;
        END $$;
        ";

        \DB::unprepared($procedure);
    }

    public function down()
    {
        $procedure = "
        DO $$
        BEGIN
            -- Elimina la función si ya existe
            IF EXISTS (SELECT 1 FROM pg_proc WHERE proname = 'subjects_count_test_global') THEN
                DROP FUNCTION subjects_count_test_global();
            END IF;
        END $$;
        ";

        \DB::unprepared($procedure);
    }
}
