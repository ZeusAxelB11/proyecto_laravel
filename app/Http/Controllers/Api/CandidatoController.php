<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\GenericController as GenericController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Candidato;

class CandidatoController extends GenericController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //======================================================================================
    public function index()
    {
        $candidatos = Candidato::all();
        $resp = $this->sendResponse($candidatos, "Listado de candidatos");
        return ($resp);
    }

    /** ====================================================================================
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Empty
    }

    //======================================================================================
    private function validateData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nombrecompleto' => 'unique:candidato|required|max:200',
            'sexo' => 'required'
        ]);

        if ($validation->fails())
            return $this->sendError("Error de validacion", $validation->errors());
    }

    // =====================================================================================
    private function prepareData(Request $request)
    {
        $foto = "";
        $perfil = "";
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('images'), $foto);
        }
        if ($request->hasFile('perfil')) {
            $perfil = $request->file('perfil')->getClientOriginalName();
            $request->file('perfil')->move(public_path('pdf'), $perfil);
        }

        $data = [
            "nombrecompleto" => $request->nombrecompleto,
            "sexo" => $request->sexo,
            "foto" => $foto,
            "perfil" => $perfil
        ];
        return $data;
    }

    /** ====================================================================================
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response*/
    public function store(Request $request)
    {
        $this->validateData($request);

        $fields = $this->prepareData($request);

        $candidate = Candidato::create($fields);
        $resp = $this->sendResponse($candidate, "Guardado...");
        return ($resp);
    } //--- End store

    /**  ====================================================================================
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response*/
    public function show($id)
    {
        //
    }

    /**  ====================================================================================
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = intval($id);
        $candidato = Candidato::find($id);
        return $this->send($candidato, $id);
    }

    // =====================================================================================
    private function send($data, $id)
    {
        if ($data) {
            $resp = $this->sendResponse(
                $data,
                "Recuperado satisfactoriamente...."
            );
        } else {
            $resp = $this->sendError("No se encontró el candidato $id");
        }
        return ($resp);
    }

    /**  ====================================================================================
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validacion = Validator::make($request->all(), [
            'nombrecompleto' => 'unique:candidato|required|max:200',
            'sexo' => 'required'
        ]);
        if ($validacion->fails())
            return $this->sendError("Error de validacion", $validacion->errors());

        $fotoCandidato = "";
        $perfilCandidato = "";
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoCandidato = $foto->getClientOriginalName();
        }
        if ($request->hasFile('perfil')) {
            $perfil = $request->file('perfil');
            $perfilCandidato = $perfil->getClientOriginalName();
        }

        $currentValue = Candidato::find($id);
        if (empty($fotoCandidato)) $fotoCandidato = $currentValue->foto;
        if (empty($perfilCandidato)) $perfilCandidato = $currentValue->perfil;

        $campos = [
            'nombrecompleto' => $request->nombrecompleto,
            'sexo'           => $request->sexo,
            'foto'           => $fotoCandidato,
            'perfil'         => $perfilCandidato,
        ];
        if ($request->hasFile('foto')) $foto->move(public_path('image'), $fotoCandidato);
        if ($request->hasFile('perfil')) $perfil->move(public_path('pdf'), $perfilCandidato);

        $candidato = Candidato::find($id);
        Candidato::whereId($id)->update($campos);
        return $this->send($candidato, $id);
    }

    /**  ====================================================================================
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $candidato = Candidato::find($id);
        Candidato::whereId($id)->delete();
        return $this->send($candidato, $id);
    }
}
