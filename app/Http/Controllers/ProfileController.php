<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
         * - Tomar el token del usuario
         * - Verificar la indentidad del admin
         * - listar todos los perfiles para el admin
         * - de no ser el admin entonces no devolver nada
         * TODO Pasar todas las tareas del admin a un controlador
         */

        $data = [
            'message' => 'this is the index method',
        ];

        return response()->json([
            $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'vat' => 'required|unique:profiles|max:255',
            'addres' => 'required|max:255',
            'phone' => 'required|unique:profiles|max:255',
            'zip_code' => 'required|max:255',
        ]);

        $profile = $request->user()->profile()->create($validatedData);

        return response()->json($profile);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $profile = $request->user()->profile;

        return response()->json($profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
