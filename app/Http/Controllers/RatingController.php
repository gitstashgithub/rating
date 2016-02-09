<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class RatingController extends Controller
{
    public function show()
    {
        return response()
            ->view('rate.result');
    }


    public function getResult(Request $request, $id)
    {
        $session_id = Session::getId();

        if (!$session_id) {
            return response('', 400);
        }

        $array = [];

        $time = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->select('created_at')
            ->select(DB::raw('MAX(created_at) as finish, Min(created_at) as start'))->first();
        //dd($time);
        $start = strtotime($time->start);
        $start = floor($start / 60) * 60;
        $finish = strtotime($time->finish);
        $finish = floor($finish / 60) * 60 + 60;

        $results = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->orderBy('created_at')
            ->groupBy(DB::raw('HOUR(created_at), MINUTE(created_at),session_id'))
            ->get();
        //dd($results);

        $j = 0;
        $allSessions = [];
        for ($i = $start; $i <= $finish; $i = $i + 60) {
            $ratings = [];
            foreach ($results as $result) {
                $session_id = $result->session_id;
                $allSessions[] = $session_id;
                $created_at = strtotime($result->created_at);
                if ($created_at > $i && $created_at < $i + 60) {
                    $ratings[$session_id] = $result->rating;
                    break;
                }
            }
//var_dump($ratings);
            $array[] = $ratings;
            $j++;
        }

        foreach($array as $item=>$value){
            foreach($allSessions as $session_id){
                if($item>0 && !array_key_exists($session_id, $value) && array_key_exists($session_id,$array[$item-1])){
                    $array[$item][$session_id] = $array[$item-1][$session_id];
                }
            }
        }
        //dd($array);
        $return = [];
        $return['median'] = [];
        $return['mean'] = [];

        foreach ($array as $a) {
            sort($a);
            $count = count($a);
            $middle = $count / 2;
            //dd($a, $count,$middle);
            $return['median'][] = ($count % 2 == 0) ? (($a[floor($middle)] + $a[ceil($middle)]) / 2) : $a[$middle];
            $return['mean'][] = number_format(array_sum($a) / $count, 1);
        }
        $returns = [];
        $returns['ratings'] = $return;
//        foreach($result)
//        dd($return);
        $bookmarks = DB
            ::table('bookmarks')
            ->where('lesson_id', '=', $id)
            ->orderBy('created_at')
            ->groupBy(DB::raw('HOUR(created_at), MINUTE(created_at)'))
            ->select(DB::raw('max(created_at) AS created_at,group_concat(bookmark) AS bookmark'))
            ->get();
        $return2 = [];
        foreach($bookmarks as $bookmark){
            $bookmarkTime = strtotime($bookmark->created_at);
            $bookmarkTime = floor($bookmarkTime / 60) * 60;
            $bm = [];
            $bm['value'] = ($bookmarkTime-$start)/60;
            $bm['text'] = $bookmark->bookmark;
            $return2[] = $bm;
        }
        $returns['bookmarks'] = $return2;
        return json_encode($returns);
    }

    public function setRate(Request $request)
    {
        $session_id = Session::getId();


        if (!$session_id) {
            return response('', 400);
        }
        $this->validate($request, [
            'lesson_id' => 'required|integer',
            'rating' => 'required|integer',
        ]);

        $rate = new Rating();

        $rate->lesson_id = $request->lesson_id;
        $rate->rating = $request->rating;
        $rate->session_id = $session_id;

        $rate->save();

        return response('', 201);
    }

    public function setBookmark(Request $request)
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

        return response('', 201);
    }
}
