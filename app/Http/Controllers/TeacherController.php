<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Teacher::latest() -> get();
        return view('teacher.index', [
            'teacher' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teacher.create');
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
            'email' => 'required | unique:teachers',
            'cell' => 'unique:students',
            'uname' => 'required | unique:teachers',
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

            $file -> move(public_path('media/teacher'), $unique_name);
        }

        Teacher::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'cell' => $request -> cell,
            'uname' => $request -> uname,
            'password' => password_hash($request -> uname, PASSWORD_DEFAULT),
            'age' => $request -> age,
            'photo' => $unique_name,
        ]);

        return redirect() -> back() -> with('success', 'Teacher added successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Teacher::find($id);
        return view('teacher.show', [
            'teacher' => $data
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
        $data = Teacher::find($id);
        return view('teacher.edit', [
            'teacher' => $data
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

        $updated_data = Teacher::find($id);

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
            $file -> move(public_path('media/teacher'), $unique_name);

            if(file_exists('media/teacher/'.$request -> old_photo)){
                unlink('media/teacher/'.$request -> old_photo);
            }
        }else{
            $unique_name = $request -> old_photo;
        }

        $updated_data -> name = $request -> name;
        $updated_data -> email = $request -> email;
        $updated_data -> cell = $request -> cell;
        $updated_data -> age = $request -> age;
        $updated_data -> photo = $unique_name;
        $updated_data -> update();

        return redirect() -> back() -> with('success', 'Teacher data updated successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Teacher::find($id);
        $data -> delete();

        if(file_exists('media/teacher/'.$data -> photo)){
            unlink('media/teacher/'.$data -> photo);
        }

        return redirect() -> back() -> with('success', 'Data deleted successful');
    }
}
