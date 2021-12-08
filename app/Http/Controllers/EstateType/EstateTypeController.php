<?php

namespace App\Http\Controllers\EstateType;

use App\Http\Controllers\Controller;
use App\Models\EstateType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EstateTypeController extends Controller
{
    public function index()
    {
        $type = EstateType::get();

        return response($type, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        EstateType::create($request->only('name'));

        return response(['message' => 'Estate type created successfully!'], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $type = EstateType::find($id);

        if (!$type) {
            return response(['message' => 'Estate type not found'], Response::HTTP_NOT_FOUND);
        }

        $type->update($request->only('name'));

        return response(['message' => 'Estate type updated successfully!'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $type = EstateType::with('posts.comment','posts.images')->get()->find($id);

        if (!$type) {
            return response(['message' => 'Estate type not found'], Response::HTTP_NOT_FOUND);
        }

        return response($type, Response::HTTP_OK);
    }

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
