@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Rate</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/rate') }}">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="range" min="0" max="4" name="rate" value="0" id="rate"
                                           onchange="updateRange(this)">
                                </div>
                                <div class="col-md-2">
                                    <select id="rate-value" onchange="updateRange(this)">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <input type="hidden" name="lesson_id" value="{{$lesson_id}}"/>
                                    <button type="submit" class="btn btn-primary">
                                        Rate
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10">
                                    <div id="chart"></div>
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