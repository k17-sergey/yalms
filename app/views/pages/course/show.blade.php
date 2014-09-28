@section('title')
    {{$course->name}}
@stop

@include('includes.header')

 Course name:{{$course->name}}
 {{ link_to_route('course.edit', 'Edit', $course->id) }}
 {{ Form::open(array('route' => array('course.destroy', $course->id), 'method' => 'delete')) }}

         <button type="submit" href="{{ URL::route('course.destroy', $course->id) }}">Delete</button>
     {{ Form::close() }}


@include('includes.footer')