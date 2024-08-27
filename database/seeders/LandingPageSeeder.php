<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('landing_page_contents')->truncate();
        DB::table('landing_page_contents')->insert([
            'title' => 'Bienvenido Capitán',
            'subtitle' => 'Aviation In Sight ATP',
            'principal_text' => 'En Aviation In Sight ATP podrás prepárate de la mejor manera
                      para el examen de titulación CIAAC. Tendrás la oportunidad
                      de administrar tu estudio, seleccionando cuestionarios por
                      materia o con simulacros tipo CIAAC. Podrás practicar las
                      veces que quieras, desde cualquier dispositivo (pc,
                      tableta o celular) en cualquier horario.',
            'footer_title' => 'En Aviation In Sight ATP podrás realizar lo siguiente:',
            'link_video' => 'https://www.youtube.com/embed/H6K9QtaJCWQ',
            'subscribe_button' => 'Inscribete',
            'compatible_text' => ' Compatible con:',
            'login_link_text' => 'Iniciar sesión',
            'footer_text_1' => 'Estudiar con cuestionarios por materia.',
            'footer_text_2' => 'Identificar respuestas correctas.',
            'footer_text_3' => 'Revisar explicación de ciertos escenarios.',
            'footer_text_4' => 'Visualizar resultado final y progreso.',
            'ws_number' => '5531096343',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
