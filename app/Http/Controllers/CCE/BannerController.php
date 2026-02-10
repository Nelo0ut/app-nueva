<?php

namespace App\Http\Controllers\CCE;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    //
    public function index(){
        return view('CCE.banners.index', [
            'banners' => Banner::where('flestado', 1)->orderby('posicion', 'ASC')->get()
        ]);
    }

    public function create(){
        // $max = DB::table('banners')->max('posicion');
        // dd($max);
        return view('CCE.banners.create', []);
    }

    public function store(BannerRequest $request){
        //Registramos los banners
        if ($request->hasfile('banners')) {
            $max = DB::table('banners')->max('posicion');
            if($max){
                $max = $max + 1;
            }
            else{
                $max=1;
            }
            
            $file = $request->file('banners');
            $original_name = time() . "-" . Str::slug( preg_replace('/\..+$/', '', $file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();

            $banner = new Banner;
            $request->banners->move(public_path('banners'), $original_name);
            $banner->url_banner = "banners/". $original_name;
            $banner->titulo = $request->titulo;
            $banner->subtitulo = $request->subtitulo;
            $banner->posicion = $max;
            $banner->flestado = 1;
            $banner->user_id = auth()->user()->id;
            $banner->save();
            
            return redirect('admin/banner')->with('status', 'Se registró el banner con éxito'); 
        }else{
            return back()->with('status', 'No se pudo crear el banner'); 
        }
        
    }

    public function edit($id){
        $banner = Banner::findOrFail($id);
        if ($banner) {
            return view('CCE.banners.editar', ['banner' => $banner]);
        }else{
            return back()->with('status', 'No se encontró el banner'); 
        }
    }

    public function update(BannerRequest $request, Banner $banner){
        //Registramos los banners
        if ($request->hasfile('banners')) {
            $banner->url_banner = $request->file('banners')->store('banners', 'public');
        }
        $banner->titulo = $request->titulo;
        $banner->subtitulo = $request->subtitulo;
        $banner->update();
        return redirect('admin/banner')->with('status', 'Se actualizó el banner con éxito'); 
    }
}
