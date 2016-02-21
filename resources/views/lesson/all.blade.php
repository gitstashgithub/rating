@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $lecture->name }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div id="lessons">
                                        <ul class="list-group">
                                            @foreach ($lessons as $lesson)
                                                <li class="list-group-item">
                                                    <a href="/lesson/{{ $lesson->id }}">{{ $lesson->lesson_date }} {{ $lesson->lesson_time }} {{ $lesson->description }}</a>
                                                    <span class="enabled-label" id="enabled-label-{{ $lesson->id }}">
                                                        @if($lesson->enabled)
                                                            <span class="label label-success">Enabled</span>
                                                        @endif
                                                    </span>
                                                    @if (Auth::check())
                                                        <button
                                                                class="btn {{$lesson->enabled?"btn-primary":"btn-default"}} btn-sm enable-lesson"
                                                                type="button"
                                                                data-id="{{ $lesson->id }}"
                                                                data-enabled="{{$lesson->enabled?1:0}}">
                                                            {{$lesson->enabled?"Disable":"&nbsp;Enable"}}
                                                        </button>
                                                        <a href="{{URL::route('lesson.edit', ['id' => $lesson->id])}}"
                                                           class="btn btn-sm btn-default">Edit</a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if (Auth::check())
                                            <a href="{{URL::route('lesson.create', ['lectureId' => $lecture->id])}}"
                                               class="btn btn-default">Add New Lesson</a>
                                        @endif
                                        <a href="https://www.surveymonkey.com/r/SKLXKBB" class="btn btn-info">Survey</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    {!! HTML::script('js/lessons.js') !!}
@endsection
@section('stylesheet')
    {!! HTML::style('css/lessons.css')  !!}
@endsection