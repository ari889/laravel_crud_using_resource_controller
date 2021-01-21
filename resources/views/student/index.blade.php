<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP Form Validation</title>
	<link rel="stylesheet" href="{{asset('style.css')}}">
	<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
</head>
<body>
	<div class="container mt-5">
        <div class="btn-group">
            <a href="{{route('student.create')}}" class="btn btn-primary rounded-0">Add new student</a>
            <a href="{{route('crud.index')}}" class="btn btn-dark rounded-0">Home</a>
        </div>
    <div class="card rounded-0 shadow">
      <div class="card-header">
        <h3>All students</h3>
      </div>
      <div class="card-body p-0">
          <div class="message">
            @if(Session::has('success'))
                <p class="alert alert-success">{{Session::get('success')}} <button class="close" data-dismiss="alert">&times;</button></p>
                @endif
          </div>
        <table class="table table-hover table-dark mb-0 text-center">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Cell</th>
              <th scope="col">Username</th>
              <th scope="col">Photo</th>
              <th scope="col">Age</th>
              <th scope="col">Created at</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>

          @foreach($student as $stu)
              <tr>
                <th scope="row">{{$loop -> index + 1}}</th>
                <td>{{$stu -> name}}</td>
                <td>{{$stu -> email}}</td>
                <td>{{$stu -> cell}}</td>
                <td>{{$stu -> uname}}</td>
                <td><img src="{{URL::to('/')}}/media/student/{{$stu -> photo}}" alt="" style="width: 50px;height: 50px;"></td>
                <td>{{$stu -> age}}</td>
                <td>{{$stu -> created_at -> diffForHumans()}}</td>
                <td class="text-right">
                    <div class="btn-group">
                        <a href="{{route('student.show', $stu -> id)}}" class="btn btn-info rounded-0">View</a>
                        <a href="{{route('student.edit', $stu -> id)}}" class="btn btn-warning rounded-0">Edit</a>
                        <form action="{{route('student.destroy', $stu -> id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger rounded-0">Delete</button>
                        </form>
                    </div>
                </td>
              </tr>
          @endforeach


          </tbody>
        </table>
      </div>
    </div>
	</div>

{{--    {{$student -> links()}}--}}
	<script type="text/javascript" src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
</body>
</html>
