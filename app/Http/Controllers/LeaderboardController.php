<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Factories\MemberFactory;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Tampilkan leaderboard — ranking pelanggan berdasarkan poin
     */
    public function index()
    {
        $leaderboard = User::where('role', 'pelanggan')
            ->orderBy('member_poin', 'desc')
            ->orderBy('name', 'asc')
            ->take(50)
            ->get()
            ->map(function ($user, $index) {
                $user->rank = $index + 1;
                $user->benefits = MemberFactory::getBenefits($user->member_type ?? 'Bronze');
                return $user;
            });

        $myRank = null;
        if (auth()->user()->role === 'pelanggan') {
            $allUsers = User::where('role', 'pelanggan')->orderBy('member_poin', 'desc')->pluck('id')->toArray();
            $myRank   = array_search(auth()->id(), $allUsers) + 1;
        }

        $allBenefits = MemberFactory::getAllBenefits();

        return view('customer.leaderboard', compact('leaderboard', 'myRank', 'allBenefits'));
    }
}
