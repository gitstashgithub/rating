@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$lecture->name}}</div>
                    <div class="panel-body">
                        {!! Form::model($lesson, ['action' => $action,'method' => $method]) !!}
                        {!! csrf_field() !!}
                        <input type="hidden" name="lectureId" value="{{ $lecture->id }}">

                        <div class="form-group{{ $errors->has('lesson_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="description"
                                       value="{{ $lesson->description }}">

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lesson_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Date</label>

                            <div class="col-md-6">
                                <input type="date" class="form-control" name="lesson_date"
                                       value="{{ $lesson->lesson_date }}">

                                @if ($errors->has('lesson_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lesson_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lesson_time') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Time</label>

                            <div class="col-md-6">
                                <input type="time" class="form-control" name="lesson_time"
                                       value="{{ $lesson->lesson_time }}">

                                @if ($errors->has('lesson_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lesson_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lesson_date') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Message</label>

                            <div class="col-md-6">
                                <textarea rows="5" type="text" class="form-control"
                                          name="message">{{ $lesson->message }}</textarea>
                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    {!! HTML::script('js/lesson.js') !!}
@endsection
@section('stylesheet')
    {!! HTML::style('css/lesson.css')  !!}
@endsection