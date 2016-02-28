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

    public function getUsersResults(Request $request, $id)
    {
        $session_id = Session::getId();

        if (!$session_id) {
            return response('', 400);
        }

        $array = [];

        $time = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->whereNull('deleted_at')
            ->select('created_at')
            ->select(DB::raw('MAX(created_at) as finish, Min(created_at) as start'))->first();
        //dd($time);
        $start = strtotime($time->start);
        $start = floor($start / 60) * 60;
        $finish = strtotime($time->finish);
        $finish = floor($finish / 60) * 60;

        $results = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->whereNull('deleted_at')
            ->orderBy('created_at')
            ->groupBy(DB::raw('HOUR(created_at), MINUTE(created_at),session_id'))
            ->get();
        //dd($results);

        $sessionIds = collect($results)->pluck('session_id')->unique()->values()->flip()->all();
        $return = [];
        $return['xs'] = [];
        $return['json'] = [];
        foreach ($sessionIds as $id) {
            $return['xs']['User' . ($id + 1)] = 'x' . ($id + 1);
        }
        $j = 0;
        for ($i = $start; $i <= $finish; $i = $i + 60) {
            $ratings = [];
            foreach ($results as $result) {
                $id = $sessionIds[$result->session_id] + 1;
                $created_at = strtotime($result->created_at);
                if ($created_at >= $i && $created_at < $i + 60) {
                    $return['json']['User' . $id][] = $result->rating;
                    $return['json']['x' . $id][] = $j;
                }
            }
            $j++;
        }

        // foreach($ratings )

        return json_encode($return);
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
            ->whereNull('deleted_at')
            ->select('created_at')
            ->select(DB::raw('MAX(created_at) as finish, Min(created_at) as start'))->first();
        //dd($time);
        $start = strtotime($time->start);
        $start = floor($start / 60) * 60;
        $finish = strtotime($time->finish);
        $finish = floor($finish / 60) * 60;

        $ignoreUsers = null;
        if($request->ignoreRatings){
            $ignoreUsers = DB
                ::table('ratings')
                ->where('lesson_id', '=', $id)
                ->whereNull('deleted_at')
                ->groupBy('session_id')
                ->select(DB::raw('count(rating) as `total`,max(session_id) as session_id'))
                ->havingRaw('`total` not in ('.implode(',',$request->ignoreRatings).')')
                ->get();
            $ignoreUsers = collect($ignoreUsers)->pluck('session_id')->all();
        }

        $results = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id);
        if($request->ignoreRatings && $ignoreUsers) {
            $results = $results->whereIn('session_id',$ignoreUsers);
        }
        $results = $results
            ->whereNull('deleted_at')
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
                if ($created_at >= $i && $created_at < $i + 60) {
                    $ratings[$session_id] = $result->rating;
                }
            }
//var_dump($ratings);
            $array[] = $ratings;
            $j++;
        }
        //dd($array);
        $saved_array = $array;
        $allSessions = array_unique($allSessions);
        $last_value_item = [];
        foreach ($array as $item => $value) {
            foreach ($allSessions as $session_id) {
                if(key_exists($session_id,$saved_array[$item])){
                    $last_value_item[$session_id] = $item;
                }

                if ($item > 0 && !array_key_exists($session_id, $value) && array_key_exists($session_id, $array[$item - 1])) {
                    if(!$request->ignoreMinutes>0 || ($item-$last_value_item[$session_id]<$request->ignoreMinutes)){
                        $array[$item][$session_id] = $array[$item - 1][$session_id];
                    }
                }
            }
        }
        //dd($array);
        $return = [];
        $return['median'] = [];
        $return['mean'] = [];

        foreach ($array as $a) {
            if(count($a)>0){
                sort($a);
                $count = count($a);
                $middle = ($count-1) / 2;
                //var_dump($a, $count,$middle);
                $return['median'][] = ($count % 2 == 0) ? (($a[floor($middle)] + $a[ceil($middle)]) / 2) : $a[$middle];
                $return['mean'][] = number_format(array_sum($a) / $count, 1);
                //var_dump($a,$count,$middle,($count % 2 == 0) ? (($a[floor($middle)] + $a[ceil($middle)]) / 2) : $a[$middle]);
            }else{
                $return['median'][] = '';
                $return['mean'][] = '';
            }

        }
        $returns = [];
        $returns['ratings'] = $return;
//        foreach($result)
//        dd($return);
        $bookmarks = DB
            ::table('bookmarks')
            ->where('lesson_id', '=', $id)
            ->orderBy('bookmarked_at')
            ->groupBy(DB::raw('HOUR(bookmarked_at), MINUTE(bookmarked_at)'))
            ->select(DB::raw('max(bookmarked_at) AS bookmarked_at,group_concat(bookmark) AS bookmark'))
            ->get();
        $return2 = [];
        foreach ($bookmarks as $bookmark) {
            $bookmarkTime = strtotime($bookmark->bookmarked_at);
            $bookmarkTime = floor($bookmarkTime / 60) * 60;
            $bm = [];
            $bm['value'] = ($bookmarkTime - $start) / 60;
            $bm['text'] = $bookmark->bookmark;
            $return2[] = $bm;
        }
        $returns['bookmarks'] = $return2;
        //dd($returns);
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

    public function toggleDelete($id)
    {
        /** @var Rating $rating */
        $rating = Rating::withTrashed()->where('id','=',$id)->first();
        if ($rating->trashed()) {
            $rating->restore();
        } else {
            $rating->delete();
        }
    }

    public function ratingSummary($id){
        $return = [];
        $results = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->whereNull('deleted_at')
            ->groupBy('session_id')
            ->select(DB::raw('count(*) as rating_count,max(rating)-min(rating) as `range`'))
            ->orderby('created_at')
            ->get();
        $return['user'] = [];
        $return['user']['type'] = 'bar';
        $return['user']['columns'] = [];

        $return['range'] = [];
        $return['range']['type'] = 'bar';
        $return['range']['columns'] = [];
        $i=1;
        foreach($results as $result){
            $return['user']['columns'][] = ['User'.$i,$result->rating_count];
            $return['range']['columns'][] = ['User'.$i,$result->range];
            $i++;
        }

        $results = DB
            ::table('ratings')
            ->where('lesson_id', '=', $id)
            ->whereNull('deleted_at')
            ->groupBy('rating')
            ->select(DB::raw('count(*) as rating_total,max(rating) as rating'))
            ->get();
        $return['total'] = [];
        $return['total']['type'] = 'bar';
        $return['total']['columns'] = [];
        foreach($results as $result){
            $return['total']['columns'][] = [$result->rating . ' Star',$result->rating_total];
        }

        return json_encode($return);
    }
}
