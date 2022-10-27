<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        return view('list_image', ['images' => Image::get()]);
    }

    public function create()
    {
        return view('upload_create');
    }

    public function store(Request $request)
    {
        $image  = $request->file('image');
        $result = CloudinaryStorage::upload($image->getRealPath(), $image->getClientOriginalName());
        Image::create(['image' => $result['result'], 'public_id' => $result['public_id']]);

        return redirect()->route('home')->withSuccess('berhasil upload');
    }

    public function edit(Image $image)
    {
        return view('upload_update', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $file   = $request->file('image');
        $result = CloudinaryStorage::replace($image->image, $file->getRealPath(), $file->getClientOriginalName());
        $image->update(['image' => $result['result'], 'public_id' => $result['public_id']]);
        return redirect()->route('home')->withSuccess('berhasil upload');
    }

    public function destroy(Image $image)
    {
        CloudinaryStorage::delete($image->image);
        $image->delete();
        return redirect()->route('home')->withSuccess('berhasil hapus');
    }
}
