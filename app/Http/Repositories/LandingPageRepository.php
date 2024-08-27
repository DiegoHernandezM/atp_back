<?php

namespace App\Http\Repositories;

use App\Models\LandingPageContent;

class LandingPageRepository
{

    public function getContent()
    {
        return LandingPageContent::first();
    }

    public function saveContent($request)
    {
        return LandingPageContent::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'principal_text' => $request->principal_text,
            'footer_title' => $request->footer_title,
            'link_video' => $request->link_video,
            'subscribe_button' => $request->subscribe_button,
            'compatible_text' => $request->compatible_text,
            'login_link_text' => $request->login_link_text,
            'footer_text_1' => $request->footer_text_1,
            'footer_text_2' => $request->footer_text_2,
            'footer_text_3' => $request->footer_text_3,
            'footer_text_4' => $request->footer_text_4,
            'ws_number' => $request->ws_number
        ]);
    }

    public function updateContent($request)
    {
        $content = $this->getContent();
        $content->title = $request->title;
        $content->subtitle = $request->subtitle;
        $content->principal_text = $request->principal_text;
        $content->footer_title = $request->footer_title;
        $content->link_video = $request->link_video;
        $content->subscribe_button = $request->subscribe_button;
        $content->compatible_text = $request->compatible_text;
        $content->login_link_text = $request->login_link_text;
        $content->footer_text_1 = $request->footer_text_1;
        $content->footer_text_2 = $request->footer_text_2;
        $content->footer_text_3 = $request->footer_text_3;
        $content->footer_text_4 = $request->footer_text_4;
        $content->ws_number = $request->ws_number;
        $content->save();
        return $content;
    }

}
