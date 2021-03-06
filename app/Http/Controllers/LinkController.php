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

    /**
     *  Переадресация по короткой ссылке
     *
     * @param $link
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
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


    /**
     *  Создание ссылки
     *
     * @param LinkRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
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


    /**
     *
     * Генерирует короткую ссылку
     *
     * @return string
     */
    public function linkGeneration()
    {

        $randomLink = Str::random(10);
        $link = Link::find($randomLink);

        if ($link) {
            return $this->linkGeneration();
        }

        return $randomLink;

    }

    /**
     * Добавляем ссылку в кеш
     *
     * @param $processedLink
     * @param $standardLink
     * @return string
     */
    public function addCache($processedLink, $standardLink)
    {
        $expiresAt = Carbon::now()->addMinutes(30);
        Cache::put($processedLink, $standardLink, $expiresAt);
    }


}
