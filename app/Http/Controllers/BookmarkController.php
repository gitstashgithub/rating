<?php

namespace App\Http\Controllers;

use App\Bookmark;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookmarkController extends Controller{
    public function store(Request $request)
    {
        $session_id = Session::getId();

        if (!$session_id) {
            return response('', 400);
        }
        $this->validate($request, [
            'lesson_id' => 'required|integer',
            'bookmark' => 'required|string',
        ]);

        $bookmark = new Bookmark();
        $bookmark->lesson_id = $request->lesson_id;
        $bookmark->bookmark = $request->bookmark;
        $bookmark->save();

        if($request->bookmarked_at){
            $bookmarked_at = new Carbon($request->bookmarked_at,auth()->user()->timezone);
            $bookmarked_at->setTimezone('UTC');
            $bookmark->bookmarked_at = $bookmarked_at->toDateTimeString();
            $bookmark->save();
        }else{
            $bookmark->bookmarked_at = $bookmark->created_at;
            $bookmark->save();
        }
        return response('', 201);
    }

    public function update(Request $request,$id)
    {
        $session_id = Session::getId();


        if (!$session_id) {
            return response('', 400);
        }
        $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $bookmark = Bookmark::find($id);

        if($request->bookmark){
            $bookmark->bookmark = $request->bookmark;
            $bookmark->save();
        }

        if($request->bookmarked_at){
            $bookmarked_at = new Carbon($request->bookmarked_at,auth()->user()->timezone);
            $bookmarked_at->setTimezone('UTC');
            $bookmark->bookmarked_at = $bookmarked_at->toDateTimeString();
            $bookmark->save();
        }

        return response('', 204);
    }
}