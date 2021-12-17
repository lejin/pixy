<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Tags;

class ImageController extends Controller
{

    /**
     *Return all files uploaded by the user
     */
    public function index()
    {
        return Image::where('userid',auth()->user()->id)->get();
    }

    /**
     *Persist image data
     */
    public function store(Request $request)
    {
      return Image::create($request->all());
    }

    /**
     *Update single image data
     */
    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'is_public' => 'boolean|required',
            'category' => 'string|nullable']);
        $image = Image::find($id);
        if (!$image) {
            return response(['message' => 'File not available'], 404);
        }
        if (auth()->user()->id != $image->userid) {
            return response(['message' => 'Access denied'], 404);
        }

        $image->category = $input['category'];
        $image->is_public = $input['is_public'];
        $image->save();
        return 'success';
    }

    /**
     * Get an image with all associated tags
    `*/
    public function show($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response(['message' => 'File not available'], 404);
        }
        if (auth()->user()->id != $image->userid) {
            return response(['message' => 'Access denied'], 404);
        }
        $tags = Tags::where('image_id',$id)->get();
        $image->tags=$tags;
        return $image;
    }

    /**
     * Get all public images
    `*/
    public function getAllPublicImages(){
        return Image::where('is_public',true)->get();
    }

    /**
     * Get single public image with tag
    `*/
    public function showPublicImage($id)
    {
        $image = Image::find($id);
        if (!$image || !$image->is_public) {
            return response(['message' => 'File not available'], 404);
        }
        $tags = Tags::where('image_id',$id)->get();
        $image->tags=$tags;
        return $image;
    }
}
