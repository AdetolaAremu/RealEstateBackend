<?php

namespace App\Http\Controllers\EstateType;

use App\Http\Controllers\Controller;
use App\Models\EstateType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EstateTypeController extends Controller
{
    // get all estate types
    public function index()
    {
        $type = EstateType::get();

        return response($type, Response::HTTP_OK);
    }

    // store a new estate type
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        EstateType::create($request->only('name'));

        return response(['message' => 'Estate type created successfully!'], Response::HTTP_CREATED);
    }

    // update a estate type
    public function update(Request $request, $id)
    {
        $type = EstateType::find($id);

        if (!$type) {
            return response(['message' => 'Estate type not found'], Response::HTTP_NOT_FOUND);
        }

        $type->update($request->only('name'));

        return response(['message' => 'Estate type updated successfully!'], Response::HTTP_OK);
    }

    // show a estate type
    public function show($id)
    {
        $type = EstateType::with('posts.comment','posts.images')->get()->find($id);

        $Alltypes = EstateType::get();

        if (!$type) {
            return response(['message' => 'Estate type not found'], Response::HTTP_NOT_FOUND);
        }

        if ($type === 'All') {
            return response($Alltypes, Response::HTTP_OK);
        }

        return response($type, Response::HTTP_OK);
    }

    // delete a estate type
    public function destroy($id)
    {
        $type = EstateType::find($id);

        if (!$type) {
            return response(['message' => 'Estate type not found'], Response::HTTP_NOT_FOUND);
        }

        $type->delete();

        return response(['message' => 'Estate type deleted successfully'], Response::HTTP_OK);
    }
}
