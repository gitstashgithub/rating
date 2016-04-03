@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Settings</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ action('SettingController@store') }}">
                            {!! csrf_field() !!}
                            @if(session('setting-saved'))
                                <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {{session('setting-saved')}}</em></div>
                            @endif
                            @foreach ($settings as $setting)
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="col-md-4 control-label"
                                               for="setting-{{$setting->id}}">{{$setting->label}}</label>
                                        <input class="col-md-6" type="text" id="setting-{{$setting->id}}"
                                               name="{{$setting->id}}" value="{{$setting->value}}">
                                        <label class="col-md-2"
                                               for="setting-{{$setting->id}}">{{$setting->unit}}</label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection