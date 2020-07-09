<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\SupportTicket;
use App\SupportAnswers;


class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(){
        $user = auth()->user();
        $tickets = SupportTicket::where('cid', $user->id)
               ->orderBy('id', 'desc')
               ->take(10)
               ->get();

        if ($user) {
            $reposne['result'] = true;
            $reposne['tickets'] = $tickets;
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $reposne;
    }

    public function show($id)
        {
            $user = auth()->user();
            $ticket = SupportTicket::where('cid', $user->id)
                    ->where('id', $id)
                   ->with('answers')
                   ->orderBy('id', 'desc')
                   ->take(10)
                   ->get();

            if ($user) {
                $reposne['result'] = true;
                $reposne['ticket'] = $ticket;
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $reposne;


        }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return SupportTicket::create($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAnswer(Request $request)
    {
        return SupportAnswers::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function edit(Support $support)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Support $support)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
        //
    }
}
