@extends('docgen::layouts.app')

@section('content')
    <div style="font-family: Arial" class="container">
        @foreach($routes as $key => $new_routes)
            <div class="card mb-4 container">
                <div class="card-header card-title"><h3>{{$key}}</h3></div>
                <div class="card-body">
                    @foreach($new_routes as $route)
                        <div>
                            <div style="cursor: pointer" class="row routes">
                                <div class="col-md-2 font-italic">{{$route['methods'][0]}}</div>
                                <div class="col-md-10 font-weight-bold">{{$route['uri']}}</div>
                            </div>
                            <div class="container p-4 mb-4" style="border: #5a5a85 solid 1px; border-radius: 5px; display: none">
                                <h6 style="color: #5a5a85" class="font-weight-bold">Method</h6>
                                <hr/>
                                <div class="mb-4">
                                    {{$route['methods'][0]}}
                                </div>

                                <h6 style="color: #5a5a85" class="font-weight-bold">Uri</h6>
                                <hr/>
                                <div class="mb-4">
                                    {{$route['uri']}}
                                </div>
                                @if(array_key_exists("request_body", $route))
                                    <h6 style="color: #5a5a85" class="font-weight-bold">Request Body</h6>
                                    <hr/>
                                    <div class="mb-4">
                                        @foreach($route['request_body'] as $key => $item)
                                            <div>
                                                <span class="font-weight-bold">{{$key}}</span>: {{(is_string($item))?$item:""}}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(array_key_exists("output_keys", $route))
                                    <h6 style="color: #5a5a85" class="font-weight-bold">Output Keys</h6>
                                    <hr/>
                                    <div class="mb-4">
                                        @foreach($route['output_keys'] as $key => $item)
                                            <div>{{(is_string($item))?$item:""}}</div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(is_array($route['middleware']))
                                    <h6 style="color: #5a5a85" class="font-weight-bold">Middlewares</h6>
                                    <hr/>
                                    <div class="mb-4">
                                        @foreach($route['middleware'] as $item)
                                            <div><span class="font-weight-bold"></span> {{($item)}}</div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection