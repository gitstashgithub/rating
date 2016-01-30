<?php

namespace App\Http\Controllers;

use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class RateController extends Controller
{
<<<<<<< HEAD
    public function show()
    {
=======
    public function show(){
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
        return response()
            ->view('rate.result');
    }


    public function getResult(Request $request, $id)
    {
        $session_id = Session::getId();

<<<<<<< HEAD
        if (!$session_id) {
            return response('', 400);
=======
        if(!$session_id){
            return response('',400);
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
        }

        $array = [];

<<<<<<< HEAD
        $time = DB
            ::table('rates')
            ->where('lesson_id', '=', $id)
            ->select('created_at')
            ->select(DB::raw('MAX(created_at) as finish, Min(created_at) as start'))->first();
        //dd($time);
        $start = strtotime($time->start);
        $start = round($start / 60) * 60;
        $finish = strtotime($time->finish);
        $finish = round($finish / 60) * 60 + 60;

        $results = DB
            ::table('rates')
            ->where('lesson_id', '=', $id)
            ->orderBy('created_at')
            ->groupBy(DB::raw('HOUR(created_at), MINUTE(created_at),session_id'))
            ->get();
        //dd($results);
        $j = 0;
        for ($i = $start; $i <= $finish; $i = $i + 60) {

            //write your if conditions and implement your logic here

            //echo date('Y-m-d h:i:s', $i) . '<br>';
            $ratings = [];
            foreach ($results as $result) {
                $session_id = $result->session_id;
                $created_at = strtotime($result->created_at);
                if ($created_at > $i && $created_at < $i + 60) {
                    $ratings[$session_id] = $result->rate;
                } else {
                    if ($j > 0 && array_key_exists($session_id, $array[$j - 1])) {
//                        var_dump($session_id, $array[$j-1]);
                        $ratings[$session_id] = $array[$j - 1][$session_id];
                    }
                }
            }
//var_dump($ratings);
            $array[] = $ratings;
            $j++;
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
//        foreach($result)
//        dd($return);
        return json_encode($return);
=======
        $results = DB
            ::table('rates')
            ->where('lesson_id','=',$id)
            ->orderBy('created_at')
            ->each(function($rate){
            var_dump($rate) ;
        });
//        foreach($result)
        //dd($results);
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
    }

    public function setRate(Request $request)
    {
        $session_id = Session::getId();

<<<<<<< HEAD
        if (!$session_id) {
            return response('', 400);
=======
        if(!$session_id){
            return response('',400);
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
        }
        $this->validate($request, [
            'lesson_id' => 'required|integer',
            'rate' => 'required|integer',
        ]);

        $rate = new Rate();

        $rate->lesson_id = $request->lesson_id;
        $rate->rate = $request->rate;
        $rate->session_id = $session_id;

        $rate->save();

<<<<<<< HEAD
        return response('', 201);
=======
        return response('',201);
>>>>>>> 66fab914a8312dd5b42b607974cc38613e2fac6b
    }
}
