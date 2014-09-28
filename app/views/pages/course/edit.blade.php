@section('title')
   Edit course {{$courseName}}
@stop

@include('includes.header')
    {{ Form::open(array('url' => $url, 'method' => 'PUT')) }}
        {{ Form::label('name', 'Course name') }}
        {{ Form::text('name',$courseName) }}
        {{ Form::submit('Save change!') }}
    {{ Form::close() }}

@include('includes.footer')