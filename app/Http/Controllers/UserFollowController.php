<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFollowController extends Controller
{
    //
    public function store(Request $request, $id){
        Auth::user()->follow($id);
        return redirect()->back()->with('success', 'フォローしました');
    }
    
    public function destroy($id){
        Auth::user()->unfollow($id);
        return redirect()->back()->with('success', 'フォローを外しました');
    }
}
