<?php

namespace App\Http\Controllers;
use App\Models\Candidato;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    /** ===========================================================================================
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $candidatos= Candidato::all();
        return view("candidato.list", compact("candidatos"));
    }

    /** ===========================================================================================
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view ("candidato.create");
    }

    private function validateData(Request $request) {
        $request->validate([
            'nombrecompleto' => 'required|max:100',
            'sexo' => 'required'
        ]);
    }

    // ============================================================================================
    private function prepareData(Request $request) {
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

    /** ===========================================================================================
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request);

        $fotocandidato = "";
        $perfilcandidato = "";
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotocandidato = $foto->getClientOriginalName();
        }
        if ($request->hasFile('perfil')) {
            $perfil = $request->file('perfil');
            $perfilcandidato = $perfil->getClientOriginalName();
        }
        $campos=[
                'nombrecompleto' => $request->nombrecompleto,
                'sexo'           => $request->sexo,
                'foto'           => $fotocandidato,
                'perfil'         => $perfilcandidato,
        ];
        if ($request->hasFile('foto')) $foto->move(public_path('image'), $fotocandidato);
        if ($request->hasFile('perfil')) $perfil->move(public_path('pdf'), $perfilcandidato);
        $candidato = Candidato::create($campos);
        //echo $candidato->nombrecompleto . " se guardo existosamente ";
        return redirect("candidato");
    }

    /** ===========================================================================================
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Empty
    }

    /** ===========================================================================================
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**$id = intval($id);
        $candidato = Candidato::whereId($id)->first();

        if ($candidato){
            return view("candidato.edit", compact("candidato"));
        } else {
            echo "Dato no encontrado";
        }*/
        $candidato = Candidato::find($id);
        return view ('candidato/edit', compact('candidato'));
    }

    /** ===========================================================================================
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validateData($request);
        /**$data = $this->prepareData($request);
        $candidato= Candidato::whereId($id)->update($data);
        return redirect('candidato')->with('success',
                $data['nombrecompleto'] . ' guardado satisfactoriamente ...');*/

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

        $campos=[
                'nombrecompleto' => $request->nombrecompleto,
                'sexo'           => $request->sexo,
                'foto'           => $fotoCandidato,
                'perfil'         => $perfilCandidato,
        ];
        if ($request->hasFile('foto')) $foto->move(public_path('image'), $fotoCandidato);
        if ($request->hasFile('perfil')) $perfil->move(public_path('pdf'), $perfilCandidato);

        Candidato::whereId($id)->update($campos);
        return redirect('candidato')->with('success', 'Se ha actualizado...');
    }

    /** ===========================================================================================
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Candidato::whereId($id)->delete();
        return redirect('candidato');
    }
}
