@section('title')
   Create new course
@stop

@include('includes.header')

    {{ Form::open(array('url' => 'course')) }}
        {{ Form::label('name', 'Course name') }}
        {{ Form::text('name') }}
        {{ Form::submit('Add course!') }}
    {{ Form::close() }}

@include('includes.footer')