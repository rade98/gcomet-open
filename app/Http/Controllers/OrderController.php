<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use \App\Mail\OrderCreated;

class OrderController extends Controller
{
    public $details;


    public function index()
    {
        $user = auth()->user();
        $orders =  Orders::where('clientid', $user->id)
               ->orderBy('id', 'desc')
               ->get();

        if ($user) {
            $reposne['result'] = true;
            $reposne['orders'] = $orders;
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $reposne;

    }
 
    public function show($id)
    {
        return Orders::find($id);
    }

    public function cart(Request $request)
    {
        $orders =  Orders::with('games')
               ->orderBy('id', 'desc')
                //->where('ip', $request->ip)
               ->get();

            $reposne['result'] = true;
            $reposne['orders'] = $orders;

        return $reposne;
    }

    public function store(Request $request)
    {

        $user = auth()->user();

        if(auth()->user()){
        $order = [
            'user' => $user->name,
            'body' => 'test body'
        ];
    }

        \Mail::to($user->email)->send(new OrderCreated($order));
        
        return Orders::create([
            'clientid' => $request->clientid,
            'serverid' => $request->serverid,
            'gameid' => $request->gameid,
            'method' => $request->method,
            'slots' => $request->slots,
            'price' => $request->price,
            'text' => $request->text,
            'modid' => $request->modid,
            'ip' => $request->ip,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $order->update($request->all());

        return $order;
    }

    public function delete(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $order->delete();

        return 204;
    }
}
