<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emails;

class EmailsController extends Controller
{
    public function getAll()
    {
        return response()->json(Emails::all());
    }

    public function store(Request $request)
    {
        $email = Emails::create($request->all());
        return response()->json($email, 201);
    }

    public function show($id)
    {
        $email = Emails::findOrFail($id);
        return response()->json($email);
    }

    public function update(Request $request, $id)
    {
        $email = Emails::findOrFail($id);

        $email->update($request->all());
        return response()->json($email);
    }

    public function softDelete($id)
    {
        $email = Emails::findOrFail($id);
        $email->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
