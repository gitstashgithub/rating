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
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/rating') }}">
                            {!! csrf_field() !!}
                            <input type="hidden" name="lesson_id" id="lesson_id" value="{{$lesson->id}}"/>
                            @if($lesson->enabled && !Auth::check())
                                <div class="form-group">
                                    <div class="col-md-8">
                                        <div>Rate your understanding (1=very poor, 5=very good)</div>
                                        <input id="rate" name="rate" type="number" class="rating" min=0 max=5 step=1>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div id="chart"></div>
                                </div>
                            </div>
                            @if(!$lesson->enabled && Auth::check())
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div id="chart-individual"></div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div id="ratings">
                                            <table class="table table-bordered table-responsive">
                                                @foreach ($ratings as $rating)
                                                    <tr>

                                                        <td>{{$rating->created_at->timezone('Australia/Melbourne')}}</td>
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
                                            </table>
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
    {!! HTML::script('js/star-rating.min.js') !!}
    {!! HTML::script('js/lesson.js') !!}
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel='stylesheet' type='text/css'>
    {!! HTML::style('css/star-rating.min.css')  !!}
    {!! HTML::style('css/lesson.css')  !!}
@endsection