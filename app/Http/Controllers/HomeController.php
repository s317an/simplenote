<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Memo;
use App\Models\Tag;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        $tags  = Tag::where('user_id',$user['id'])->get();
        return view('create',compact('user','memos','tags'));
    }

    public function create()
    {
        //ログインしてるユーザーの情報をViewに渡す
        $user = Auth::user();
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        $tags  = Tag::where('user_id',$user['id'])->get();
        return view('create',compact('user','memos','tags'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $exit_tag = Tag::where('name',$data['tag']) -> where('user_id',$data['user_id']) -> first();
        // dd($exit_tag);

        //if文で既存のタグIDの確認
        //もし既存のタグIDが存在しなかった場合
        if( empty($exit_tag['id'])){
        //下記のタグIDを自動に作成する
            $tag_id = Tag::insertGetId([
                'name' => $data['tag'],
                'user_id' => $data['user_id'],
            ]);
        }else{
        //既存のタグIDが存在する場合はそのまま使用する
            $tag_id = $exit_tag['id'];
        };

        // dd($tag_id);
        Memo::insertGetId([
            'content' => $data['content'], 
            'user_id' => $data['user_id'],
            'tag_id' => $tag_id,
            'status'  => 1,

        ]);
        return redirect()-> route('index');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $memo = Memo::where('status',1)->where('id',$id)->where('user_id',$user['id'])->first();
        // dd($memo);
        $memos = Memo::where('user_id',$user['id'])->where('status',1)->orderBy('updated_at','DESC')->get();
        $tags  = Tag::where('user_id',$user['id'])->get();


        return view('edit',compact('memo','user','memos','tags'));
    }

    //メモ更新
    public function update(Request $request,$id)
    {
        $date = $request->all(); 
        // dd($date);
        Memo::where('id',$id)->update(['content'=> $date['content'], 'tag_id' => $date['tag_id']]);
        return redirect()-> route('create')->with('success','メモが更新されました。');
    }

    //メモ削除
    public function delete($id)
    {
        //論理的削除
        Memo::where('id',$id)->update(['status' => 2]);

        return redirect()-> route('create')->with('success','メモが削除されました。');
    }
}
