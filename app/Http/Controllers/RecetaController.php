<?php

namespace App\Http\Controllers;

use App\CategoriaReceta;
use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['show','search']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = auth()->user();
        $recetas = Receta::where('user_id',$usuario->id)->paginate(10);
        return view('recetas.index')
                            ->with('recetas',$recetas)
                            ->with('usuario',$usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Obtencion de categorias sin modelo
        //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre','id');

        //Categorias con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);
        return view('recetas.create')
                        ->with('categorias',$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = request()->validate([
            'titulo' => 'required|min:5',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image'
        ]);

        $ruta_imagen = $request['imagen']->store('upload-recetas','public');
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
        $img->save();
        
        /*Almacenar en la BD (sin modelo)
        DB::table('recetas')->insert([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'user_id' => Auth::user()->id,
            'categoria_id' => $data['categoria']
        ]);*/

        //Almacenar en la BD con modelo
        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);

        //redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        // Obtener si el usuario actual le gusta la receta y esta autenticado
        $like =  ( auth()->user() ) ? auth()->user()->meGusta->contains($receta->id ) : false;
        // Pasar cantidad de likes a vista
        $likes = $receta->likes->count();
        return view('recetas.show',compact('receta','like','likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        $this->authorize('view',$receta);
        $categorias = CategoriaReceta::all(['id','nombre']);
        return view('recetas.edit',compact('categorias','receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        $this->authorize('update',$receta);
        $data = request()->validate([
            'titulo' => 'required|min:5',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
        ]);
        $receta->titulo = $data['titulo'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->preparacion = $data['preparacion'];
        $receta->categoria_id = $data['categoria'];
        $receta->save();
        //Si usuario sube nueva imagen

        if(request('imagen')){
            $ruta_imagen = $request['imagen']->store('upload-recetas','public');
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
            $img->save();
            $receta->imagen = $ruta_imagen;
        }
        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Ejecutar el policy
        $this->authorize('delete',$receta);
        //Eliminar receta
        $receta->delete();
        return redirect()->action('RecetaController@index');
    }

    public function search(Request $request)
    {
        $busqueda = $request['buscar'];
        $recetas = Receta::where('titulo','like', '%' . $busqueda. '%')->paginate(10);
        $recetas->appends(['buscar'=> $busqueda]);
        return view('busquedas.show',compact('recetas','busqueda'));
    }
}
