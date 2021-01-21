<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Staff::latest() -> get();
        return view('staff.index', [
            'staff' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.create');
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
            $file -> move(public_path('media/staff'), $unique_name);
        }

        Staff::create([
            'name' => $request -> name,
            'email' => $request -> email,
            'cell' => $request -> cell,
            'age' => $request -> age,
            'uname' => $request -> uname,
            'password' => password_hash($request -> password, PASSWORD_DEFAULT),
            'photo' => $unique_name,
        ]);

        return redirect() -> back() -> with('success', 'Data added successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Staff::find($id);
        return view('staff.show', [
            'staff' => $data
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
        $data = Staff::find($id);
        return view('staff.edit', [
            'staff' => $data
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
        $data = Staff::find($id);
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
            $file -> move(public_path('media/staff'), $unique_name);

            if(file_exists('media/staff/'.$request -> old_photo)){
                unlink('media/staff/'.$request -> old_photo);
            }
        }else{
            $unique_name = $request -> old_photo;
        }

        $data -> name = $request -> name;
        $data -> email = $request -> email;
        $data -> cell = $request -> cell;
        $data -> age = $request -> age;
        $data -> photo = $unique_name;
        $data -> update();

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
        $data = Staff::find($id);

        $data -> delete();

        if(file_exists('media/staff/'.$data -> photo)){
            unlink('media/staff/'.$data -> photo);
        }
        return redirect() -> back() -> with('success', 'Data deleted successful');
    }
}
