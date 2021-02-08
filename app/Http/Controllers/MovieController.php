<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Movie;
use App\Models\Turn;
use File;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::with('turn')->get();
        return response()->json([
            'success' => true,
            'movies' => $movies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'image' => 'required|mimes:jpg,jpeg,png',
            'name' => 'required',
            'publicationDate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Verifique que el archivo subido sea un jpg',
            ]);
        }

        $imageName = time().'.'.$request->image->extension();

        if ($request->image->move(public_path('images'), $imageName)) {
            $pathImage = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$imageName;

            $movie = Movie::create([
                'name'              => $request->name,
                'publication_date'  => $request->publicationDate,
                'image'             => $pathImage,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registro guardado correctamente',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ocurrio un problema al procesar la información',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json([
            'success' => true,
            'movie' => $movie,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            // 'image' => 'mimes:jpg,jpeg,png',
            'name' => 'required',
            'publicationDate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesarlos campos del formulario, por favor verifique los campos',
            ]);
        }

        $movie = Movie::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            if ($request->image->move(public_path('images'), $imageName)) {
                $newPathImage = DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$imageName;
                $beforePathImage = public_path().$movie->image;

                if(File::exists($beforePathImage)){
                    File::delete($beforePathImage);
                }

                $movie->name = $request->name;
                $movie->publication_date = $request->publicationDate;
                $movie->image = $newPathImage;
                $movie->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Registro actualizado correctamente',
                ]);
            }
        }

        $movie->name = $request->name;
        $movie->publication_date = $request->publicationDate;
        if ($movie->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado correctamente',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ocurrio un problema al procesar la información',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        // Delete Image
        $beforePathImage = public_path().$movie->image;
        if(File::exists($beforePathImage)){
            File::delete($beforePathImage);
        }

        // eliminar relación si la tiene
        if ($movie->turn()->exists()) {
            $movie->turn->movie_id = null;
            $movie->turn->save();
        }

        if ($movie->destroy($id)) {
            $movies = Movie::all();
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente',
                'movies' => $movies
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Problemas al eliminar el registro',
                'movies' => $movies
            ]);
        }
    }

    public function assignTurn(Request $request, $id) {

        $movie = Movie::findOrFail($id);
        $turn = Turn::find($request->turn_id);

        if ($turn) {
            $movie->turn()->save($turn);
            return response()->json([
                'success' => true,
                'message' => 'Turno asignado correctamente',
            ]);
        } else if (!$turn && $movie->turn()->exists()){
                $movie->turn->movie_id = null;
                $movie->turn->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Turno asignado correctamente',
                ]);
        } else if (!$turn && !$movie->turn()->exists()){
            return response()->json([
                'success' => true,
                'message' => 'Turno asignado correctamente',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Problemas para asignar el turno',
        ]);
    }
}
