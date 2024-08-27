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
        $procedure = "DROP PROCEDURE IF EXISTS `subjects_count_test_global`;
        CREATE PROCEDURE subjects_count_test_global()
        BEGIN
            select t1.id, t1.name, IFNULL(t2.rep, 0) as repeticion from (select id, name from subjects) t1 left join
            (select ut.subject_id,count(subject_id) rep
            from user_tests ut  right join subjects s on ut.subject_id = s.id  where completed = 1 group by subject_id)
            as t2 on t1.id = t2.subject_id group by t1.name;
        END;";
        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `subjects_count_test_global`";
        \DB::unprepared($procedure);
    }
}
