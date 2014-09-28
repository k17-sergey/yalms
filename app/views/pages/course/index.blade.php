@section('title')
    All courses
@stop

@include('includes.header')

    @foreach ($courses as $course)
      <div class="course">
        {{ link_to_route('course.show', $course->name, $course->id) }}
      </div>
    @endforeach

@include('includes.footer')