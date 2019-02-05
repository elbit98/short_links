<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;
use App\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class LinkController extends Controller
{


    public function index($link)
    {
        $redirect = Cache::get($link);

        if (is_null($redirect)) {

            $redirect = Link::find($link);

            if (is_null($redirect)) {
                return abort(404);
            } else {
                $this->addCache($redirect->processed_link, $redirect->standard_link);
                $redirect = Cache::get($link);
            }

        }

        return redirect($redirect);
    }


    public function create(LinkRequest $request)
    {

        $processedLink = $this->linkGeneration();
        $standardLink = $request->standard_link;

        $linkModel = Link::create([
            'standard_link'  => $standardLink,
            'processed_link' => $processedLink
        ]);

        $this->addCache($processedLink, $standardLink);

        return Redirect::back()->with('success', \Config::get('app.url') . '/' . $linkModel->processed_link);
    }

    public function linkGeneration()
    {

        $randomLink = Str::random(10);
        $link = Link::find($randomLink);

        if ($link) {
            return $this->linkGeneration();
        }

        return $randomLink;

    }

    public function addCache($processedLink, $standardLink)
    {
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put($processedLink, $standardLink, $expiresAt);
    }


}
