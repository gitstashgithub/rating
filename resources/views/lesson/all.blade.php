@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Lessons</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <div class="col-md-10">
                                    <div id="lessons">
                                        <ul>
                                            @foreach ($lessons as $lesson)
                                                <li>
                                                    <a href="/lesson/{{ $lesson->id }}">{{ $lesson->lesson_date }} {{ $lesson->lesson_time }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.14/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js"></script>
    {!! HTML::script('js/lesson.js') !!}
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css" rel='stylesheet' type='text/css'>
@endsection