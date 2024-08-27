<?php

namespace App\Http\Controllers;

use App\Http\Repositories\LandingPageRepository;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function getContent(LandingPageRepository $repository)
    {
        try {
            $content = $repository->getContent();
            return ApiResponses::okObject($content);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function updateContent(Request $request, LandingPageRepository $repository)
    {
        try {
            return ApiResponses::okObject($repository->updateContent($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
