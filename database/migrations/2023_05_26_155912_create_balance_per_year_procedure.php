<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancePerYearProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "DROP PROCEDURE IF EXISTS `balance_per_year`;
        CREATE PROCEDURE balance_per_year(IN IDATE DATE, EDATE DATE)
        BEGIN
        SELECT seq meses, IFNULL(monto, 0) monto FROM seq_1_to_12
        LEFT JOIN (SELECT SUM(amount) as monto, MONTH(created_at) mes
        from paypal_user
        where DATE (created_at) BETWEEN IDATE AND EDATE
        GROUP BY YEAR(created_at), MONTH(created_at)) t2 ON t2.mes = seq;
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
        $procedure = "DROP PROCEDURE IF EXISTS `balance_per_year`";
        \DB::unprepared($procedure);    }
}
