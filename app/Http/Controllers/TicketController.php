<?php

namespace App\Http\Controllers;

use App\Models\GameRound;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'numbers' => 'required|array|size:6',
            'numbers.*' => 'integer|min:1|max:48|distinct',
        ]);

        $pendingRound = GameRound::where('status','pending')
            ->orderBy('created_at','asc')
            ->first();

        if(!$pendingRound){
            return response()->json(['error' => 'No pending round available'], 400);
        }

        $user_id = Auth::guard('user')->id();
        $user = User::where('id',$user_id)->first();

        // Deduct 1 KM from the user's balance
        if ($user->balance < 1) {
            return response()->json(['message' => 'Insufficient balance']);
        }

        $user_id = Auth::guard('user')->id();

        DB::transaction(function () use ($request, $pendingRound, $user_id, $user) {
            // Deduct 1 KM from user balance
            $user->decrement('balance', 1);
            // Refresh user instance to get the updated balance
            $user->refresh();

            // Create the ticket

            $ticket = Ticket::create([
                'user_id' => $user_id,
                'numbers' => json_encode($request->numbers),
                'round_id' => $pendingRound->id,
            ]);


        });

        return response()->json(['message' => 'Ticket created successfully']);
    }

    public function index(Request $request){

        $query = Ticket::query();

        if($request->filled('from') && $request->filled('to')){
            $query->whereBetween('created_at', [
                $request->input('from'),
                $request->input('to')
            ]);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function showUserHistory(Request $request){
        // Get the user_id from the request (e.g., from a route parameter or query string)
        $userId = $request->input('user_id'); // Or you can use route('user_id') if it's a route parameter

        // Query tickets for the given user_id
        $tickets = Ticket::where('user_id', $userId)->get();

        // Pass the tickets to the view
        return view('tickets', ['tickets' => $tickets]);
    }


}
