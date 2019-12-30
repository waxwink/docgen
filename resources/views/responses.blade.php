@extends('docgen::layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($responses as $response_key => $response)

                    <div class="font-weight-bolder mt-4 mb-2" style="font-size: large;">{{$response_key}}</div>
                    <table class="mt-1 table table-hover">
                        @foreach($response as $key => $value)
                            <tr>
                                <td class="col-3">{{$key}}</td>
                                <td class="col-9">{{json_encode($value)}}</td>
                            </tr>
                        @endforeach
                    </table>

                @endforeach
            </div>
        </div>
    </div>


@endsection