<?php

namespace App\Http\Middleware;

use App\Models\Image;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RestrictPrivateDownload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $name = $request->route('name');
        $image = Image::where('url',$name)->first();
        if(!$image){
            return response(['message' => 'File not available'], 404);
        }
        if ($image->is_public){
            return redirect('/api/download/public/'.$name);
        }else{
            return redirect('/api/download/private/'.$name);
        }
        return $next($request);
    }
}
