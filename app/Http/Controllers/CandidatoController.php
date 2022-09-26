<?php

namespace App\Http\Controllers;
use App\Models\Candidato;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidatos= Candidato::all();
        return view("candidato.list", compact("candidatos"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ("candidato.create");
    }

    private function validateData(Request $request){
        $request->validate([
            'nombrecompleto' => 'required|max:100',
            'sexo'=>'required'
        ]);
    }

    private function prepareData(Request $request){
        $foto = "";
        $perfil = "";
        if ($request->hasFile('foto')) {
            $foto= $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('images'), $foto);

        }
        if ($request->hasFile('perfil')) {
            $perfil= $request->file('perfil')->getClientOriginalName();
            $request->file('perfil')->move(public_path('pdf'), $perfil);
        }

        $data= [
            "nombrecompleto"=>$request->nombrecompleto,
            "sexo"=>$request->sexo,
            "foto"=>$foto,
            "perfil"=>$perfil
        ];
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request);
        $data = $this->prepareData($request);
        $candidato= Candidato::create($data);
        return redirect('candidato')->with('success',
                $candidato->nombrecompleto . ' guardado satisfactoriamente ...');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = intval($id);
        $candidato = Candidato::whereId($id)->first();

        if ($candidato){
            return view("candidato.edit", compact("candidato"));
        } else {
            echo "Dato no encontrado";
        }
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
        $this->validateData($request);
        $data = $this->prepareData($request);
        $candidato= Candidato::whereId($id)->update($data);
        return redirect('candidato')->with('success',
                $data['nombrecompleto'] . ' guardado satisfactoriamente ...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Candidato::whereId($id)->delete();
    }
}
