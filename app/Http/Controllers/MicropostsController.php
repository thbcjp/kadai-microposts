<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Micropost;

class MicropostsController extends Controller
{
    //indexメソッド
    public function index()
    {
        $data = [];
        if(Auth::check()){
            $user = Auth::user();
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            $data += $this->counts($user);
            return view('users.show', $data);
        } else {
            return redirect('/');
        }
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',    
        ]);
        
        $request->user()->microposts()->create([
            'content' => $request->content,    
        ]);
        
        return redirect()->back()->with('success', '新規Tweetの投稿完了！');
    }
    
    public function destroy($id)
    {
        $micropost = Micropost::find($id);
        
        if(Auth::id() === $micropost->user_id){
            $micropost->delete();
        }
        
        return redirect()->back();
    }
}
