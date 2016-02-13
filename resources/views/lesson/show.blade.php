@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$lesson->description}}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/rating') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="lesson_id" id="lesson_id" value="{{$lesson->id}}"/>
                            @if($lesson->enabled && !Auth::check())
                                <div class="form-group">
                                    <div class="col-md-8">
                                        {{--<input type="range" min="0" max="4" name="rate" value="0" id="rate"--}}
                                        {{--onchange="updateRange(this)">--}}
                                        <div>Rate your understanding (1=very poor, 5=very good)</div>
                                        <input id="rate" name="rate" type="number" class="rating" min=0 max=5 step=1>
                                    </div>
                                    {{--<div class="col-md-2">--}}
                                    {{--<select id="rate-value" onchange="updateRange(this)">--}}
                                    {{--<option value="0">0</option>--}}
                                    {{--<option value="1">1</option>--}}
                                    {{--<option value="2">2</option>--}}
                                    {{--<option value="3">3</option>--}}
                                    {{--<option value="4">4</option>--}}
                                    {{--</select>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-md-2">--}}

                                    {{--<button type="button" class="btn btn-primary" id="submit-rate">--}}
                                    {{--Rate--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div id="chart"></div>
                                </div>
                            </div>
                            @if (Auth::check() && $lesson->enabled)
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="input-group">
                                            <input type="text" id="bookmark" class="form-control"
                                                   placeholder="bookmark">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="submit-bookmark">
                                                    Save
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.14/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
    {!! HTML::script('js/star-rating.min.js') !!}
    {!! HTML::script('js/lesson.js') !!}
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel='stylesheet' type='text/css'>
    {!! HTML::style('css/star-rating.min.css')  !!}
    {!! HTML::style('css/lesson.css')  !!}
@endsection