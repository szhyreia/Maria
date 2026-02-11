<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return Users::all();
    }

    //for storing data
    public function store(Request $request)
    {
        return Users::create($request->all());
    }

    //for showing data
    public function show(string $id)
    {
        return Users::findOrFail($id);
    }

    //for deleting data
    public function destroy(string $id)
    {
        Users::destroy($id);
        return response()->noContent();
    }

    //for updating data
    public function update(Request $request, string $id)
    {
        $student = Users::findOrFail($id);
        $student->update($request->all());
        return $student;
    }
}