<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class FavoriteController extends Controller
{
    public function store($id)
    {
        // お気に入り
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * お気に入り解除
     */
    public function destroy($id)
    {
        
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
