<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class FileController extends Controller
{
    /**
     * Upload image
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $image = $request->file('image');
        $data = getimagesize($image);
        $width = $data[0];
        $height = $data[1];
        $file_name = Str::random(10) . "." . $image->extension();
        Storage::disk('minio')->put($file_name, $image->getContent());
        $public_url = config('app.url') . "/api/download/" . $file_name;
        $image = Image::create([
            'url' => $file_name,
            'userid' => $request->user()->id,
            'is_public' => false,
            'height' => $height,
            'width' => $width
        ]);
        return ['width' => $width, 'height' => $height, 'url' => $public_url,'image_id'=>$image->id];
    }

    /**
     * download private image
     */
    public function privateDownload($name)
    {
        //build validator
        $validator = Validator::make(
            ['name' => $name],
            ['name' => 'required']
        );
        // validate file name
        if ($validator->fails()) {
            return response(['message' => 'Invalid filename'], 400);
        }
        if (Storage::disk('minio')->missing($name)) {
            return response(['message' => 'Invalid file'], 404);
        }
        $image = Image::where('url',$name)->first();
        $logged_in = auth()->check();

        Log::info("logged in".auth()->user());
        $public_image=$image->is_public;
        if((!$public_image && !$logged_in) || (!$public_image && $image->userid!=$id = auth()->user()->id)){
            return response(['message' => 'File not available'], 404);
        }

        $headers = [
            'Content-Type' => 'Content-Type: image/jpeg'
        ];
        return Response::make(Storage::disk('minio')->get($name), 200, $headers);
    }

    /**
     * download public image
     */
    public function publicDownload($name)
    {
        //build validator
        $validator = Validator::make(
            ['name' => $name],
            ['name' => 'required']
        );
        // validate file name
        if ($validator->fails()) {
            return response(['message' => 'Invalid filename'], 400);
        }
        if (Storage::disk('minio')->missing($name)) {
            return response(['message' => 'Invalid file'], 404);
        }
        $image = Image::where('url',$name)->first();

        if(!$image){
            return response(['message' => 'File not available'], 404);
        }
        $public_image=$image->is_public;
        if(!$public_image ){
            return response(['message' => 'File not available'], 404);
        }

        $headers = [
            'Content-Type' => 'Content-Type: image/jpeg'
        ];
        return Response::make(Storage::disk('minio')->get($name), 200, $headers);
    }

    public function none($name){

        return response('File not available', 200);
    }
}
