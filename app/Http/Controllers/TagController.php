<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Persist tag information
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'image_id' => 'required|integer',
            'a_x' => 'required|integer',
            'a_y' => 'required|integer',
            'b_x' => 'required|integer',
            'b_y' => 'required|integer',
            'c_x' => 'required|integer',
            'c_y' => 'required|integer',
            'd_x' => 'required|integer',
            'd_y' => 'required|integer',
            'label' => 'required|string',
            'info'=>'json|nullable'
        ]);
        $image = Image::find($input['image_id']);
        if(!$image){
            return response(['message' => 'Image not found'], 404);
        }
        if(auth()->user()->id!=$image->userid){
            return response(['message' => 'Image not accessible'], 404);
        }
        return Tags::create($request->all());
    }
}
