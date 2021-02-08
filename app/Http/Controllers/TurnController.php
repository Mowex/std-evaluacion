<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Turn;

class TurnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $turns = Turn::all();
        return response()->json([
            'success' => true,
            'turns' => $turns,
        ]);
    }

    public function withOutAssign($movie_id)
    {
        $turns = Turn::whereNull('movie_id')->get();
        $turnsAssign = Turn::where('movie_id', $movie_id)->first();
        return response()->json([
            'success' => true,
            'turns' => $turns,
            'turnsAssign' => $turnsAssign,
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
            'hour' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Verifique la hora del turno',
            ]);
        }

        $turn = Turn::create([
            'hour'       => $request->hour,
            'is_active'  => $request->is_active,
        ]);

        if ($turn) {
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
        $turn = Turn::findOrFail($id);
        return response()->json([
            'success' => true,
            'turn' => $turn,
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
            'hour' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Verifique la hora del turno',
            ]);
        }

        $turn = Turn::findOrFail($id);
        $turn->hour = $request->hour;
        $turn->is_active = $request->is_active;

        if ($turn->save()){
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
        $turn = Turn::findOrFail($id);

        if ($turn->destroy($id)) {
            $turns = Turn::all();
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado correctamente',
                'turns' => $turns
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Problemas al eliminar el registro',
                'turns' => $turns
            ]);
        }
    }
}
