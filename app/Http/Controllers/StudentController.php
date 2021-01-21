<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_student = Student::latest() -> get();
//        $all_student = Student::latest() -> take(1) -> get();
//        $all_student = Student::where('uname', 'ari889') -> orWhere('age', 85) -> get();
//        $all_student = Student::where('uname', 'ari889') -> get();
//        $all_student = Student::latest() -> paginate(1);
        return view('student.index', [
            'student' => $all_student
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
           'name' => 'required',
            'email' => 'required | unique:students',
            'cell' => 'unique:students',
            'uname' => 'required | unique:students',
            'password' => 'required | min: 6',
        ],[
            'name.required' => 'Name required',
            'email.required' => 'Email required',
            'email.unique' => 'Email already taken',
            'cell.unique' => 'Cell already taken',
            'uname.required' => 'Username required',
            'uname.unique' => 'Username already taken',
            'password.required' => 'Password required'
        ]);

        if($request -> hasFile('photo')){
            $file = $request -> file('photo');
            $unique_name = md5(time().rand()).'.'.$file -> getClientOriginalExtension();
            $file -> move(public_path('media/student'), $unique_name);
        }

        Student::create([
            'name'      => $request -> name,
            'email'     => $request -> email,
            'cell'      => $request -> cell,
            'uname'     => $request -> uname,
            'password'  => password_hash($request -> password, PASSWORD_DEFAULT),
            'age'       => $request -> age,
            'photo'     => $unique_name
        ]);

        return redirect() -> back() -> with('success', 'Data added successful.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Student::find($id);
        return view('student.show', [
            'student' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = Student::find($id);
        return view('student.edit', [
            'student' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $update_data = Student::find($id);
        $this -> validate($request, [
            'name' => 'required',
            'email' => 'required',
        ],[
            'name.required' => 'Name required',
            'email.required' => 'Email required',
        ]);

        if($request -> hasFile('new_photo')){
            $file = $request -> file('new_photo');
            $unique_name = md5(time().rand()).'.'.$file -> getClientOriginalExtension();
            $file -> move(public_path('media/student'), $unique_name);

            if(file_exists('media/student/'.$request -> old_photo)){
                unlink('media/student/'.$request -> old_photo);
            }
        }else{
            $unique_name = $request -> old_photo;
        }

        $update_data -> name = $request -> name;
        $update_data -> email = $request -> email;
        $update_data -> cell = $request -> cell;
        $update_data -> age = $request -> age;
        $update_data -> photo = $unique_name;
        $update_data -> update();

        return redirect() -> back() -> with('success', 'Data updated successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Student::find($id);
        $data -> delete();

        if(file_exists('media/student/'.$data -> photo)){
            unlink('media/student/'.$data -> photo);
        }


        return redirect() -> back() -> with('success', 'Data deleted successful');
    }
}
