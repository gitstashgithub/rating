@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        @if(!$lesson->enabled && Auth::check())
                            <a href="/lesson/{{$lesson->id}}/export" class="btn btn-default pull-right"><span
                                        class="glyphicon glyphicon-download-alt"></span> Export</a>
                        @endif
                        <h4 class="panel-title">{{$lesson->description}}</h4>
                    </div>
                    <div class="panel-body">
                        <div>
                            {!! nl2br($lesson->message) !!}
                            <hr>
                        </div>
                        <form id="lesson-form" class="form-horizontal" role="form" method="POST" action="{{ url('/rating') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="lesson_id" id="lesson_id" value="{{$lesson->id}}"/>
                            @foreach($settings as $setting)
                                <input type="hidden" id="setting-{{$setting->name}}" value="{{$setting->value}}"/>
                            @endforeach

                            @if($lesson->enabled && !Auth::check())
                                <label class="col-md-1" for="">Zoom: </label><div class="slider col-md-10"></div>
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <div>Rate your understanding (1=very poor, 5=very good)</div>
                                        <input id="rate" name="rate" type="number" class="rating" min=0 max=5 step=1>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-12">
                                    @if(!$lesson->enabled && Auth::check())
                                        <label>Ignore Ratings</label>
                                        <select id="ignore-ratings" multiple="multiple">
                                            <option value="1">1 Rating</option>
                                            <option value="2">2 Ratings</option>
                                            <option value="3">3 Ratings</option>
                                            <option value="4">4 Ratings</option>
                                            <option value="5">5 Ratings</option>
                                            <option value="6">6 Ratings</option>
                                            <option value="7">7 Ratings</option>
                                            <option value="8">8 Ratings</option>
                                            <option value="9">9 Ratings</option>
                                            <option value="10">10 Ratings</option>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>Exclude rating is more than</label>
                                        <select id="ignore-minutes">
                                            <option value="0">None</option>
                                            <option value="1">1 Minute</option>
                                            <option value="2">2 Minutes</option>
                                            <option value="3">3 Minutes</option>
                                            <option value="4">4 Minutes</option>
                                            <option value="5">5 Minutes</option>
                                            <option value="6">6 Minutes</option>
                                            <option value="7">7 Minutes</option>
                                            <option value="8">8 Minutes</option>
                                            <option value="9">9 Minutes</option>
                                            <option value="10">10 Minutes</option>
                                            <option value="11">11 Minutes</option>
                                            <option value="12">12 Minutes</option>
                                            <option value="13">13 Minutes</option>
                                            <option value="14">14 Minutes</option>
                                            <option value="15">15 Minutes</option>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-primary" type="button" id="update-rating-chart">
                                            Update
                                        </button>
                                    @endif
                                    <div id="chart"></div>
                                </div>
                            </div>
                            @if(!$lesson->enabled)
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div id="chart-individual"></div>
                                        <div id="chart-user-summary"></div>
                                        <div id="chart-user-summary-frequency"></div>
                                        <div id="chart-rating-range"></div>
                                        <div id="chart-rating-range-frequency"></div>
                                        <div id="chart-total-ratings"></div>
                                    </div>
                                </div>
                            @endif
                            @if(!$lesson->enabled && Auth::check())
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div>
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#ratings"
                                                                                          aria-controls="ratings"
                                                                                          role="tab" data-toggle="tab">Ratings</a>
                                                </li>
                                                <li role="presentation"><a href="#bookmarks" aria-controls="bookmarks"
                                                                           role="tab" data-toggle="tab">Bookmarks</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="ratings">
                                                    <table class="table table-bordered table-responsive">
                                                        <thead>
                                                        <tr>
                                                            <th>Date Time</th>
                                                            <th>Lesson Id</th>
                                                            <th>Rating</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($ratings as $rating)
                                                                <tr>

                                                                    <td>{{$rating->created_at->timezone(auth()->user()->timezone)}}</td>
                                                                    <td>{{$rating->session_id}}</td>
                                                                    <td>{{$rating->rating}}</td>
                                                                    <td>
                                                                        <button
                                                                                class="btn {{$rating->deleted_at?"btn-primary":"btn-default"}} btn-sm enable-rating"
                                                                                type="button"
                                                                                data-id="{{ $rating->id }}"
                                                                                data-enabled="{{$rating->deleted_at?0:1}}">
                                                                            {{!$rating->deleted_at?"Disable":"&nbsp;Enable"}}
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @include('lesson.bookmark',['bookmarks'=>$bookmarks])
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            @endif
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    {!! HTML::script('js/star-rating.min.js') !!}
    {!! HTML::script('js/bootstrap-multiselect.js') !!}
    {!! HTML::script('js/scale.js') !!}
    {!! HTML::script('js/jquery-ui-slider-pips.js') !!}
    {!! HTML::script('js/lesson.js') !!}
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css" rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel='stylesheet' type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    {!! HTML::style('css/star-rating.min.css')  !!}
    {!! HTML::style('css/bootstrap-multiselect.css')  !!}
    {!! HTML::style('css/jquery-ui-slider-pips.css')  !!}
    {!! HTML::style('css/lesson.css')  !!}
@endsection