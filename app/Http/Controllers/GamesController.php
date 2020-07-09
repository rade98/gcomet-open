<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Games;


class GamesController extends Controller
{
    public function index(){

        $games = Games::with('prices')->get();

        $reposne['result'] = true;
        $reposne['games'] = $games;

        return $reposne;
    }

    public function show($id)
    {
        $game = Games::where('id', $id)->with('prices')->with('mods')->get();
        $reposne['result'] = true;
        $reposne['game'] = $game;

        return $reposne;
    }
}
