<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Candidato;
use App\Models\Casilla;
use App\Models\Eleccion;
use App\Models\Voto;
use App\Models\Votocandidato;
use Exception;
use Illuminate\Support\Facades\DB;

class VotoController extends Controller
{
    private $DUPLICATE_KEY_CODE=23000;
    private $DUPLICATE_KEY_MESSAGE="Dato duplicado en la BD, ".
            "no se permiten duplicados";

    /** ===========================================================================================
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $votos = Voto::all();
        return view('voto/list', compact('votos'));

    }

    /** ===========================================================================================
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $casillas = Casilla::all();
        $elecciones = Eleccion::all();
        $candidatos = Candidato::all();
        return view ("voto.create", compact (["casillas","elecciones","candidatos"]));
    }

    // ============================================================================================
    private function validateVote($request) {
        foreach($request->all() as $key=>$value){
            if (substr($key,0,10)=="candidato_")
                if ($value<0){
                    return false;
                }
        }
        return true;
    }

    // ============================================================================================
    private function getEvidence(Request $request) {
        $evidenceFileName="";
        if ($request->hasFile('evidencia')) {
            $evidenceFileName = $request->file('evidencia')->getClientOriginalName();
            $request->file('evidencia')->move(public_path('pdf'), $evidenceFileName);
        }
        return $evidenceFileName;
    }

    // ============================================================================================
    private function getDataVote(Request $request) {
        $dataVote= [
            "eleccion_id"=>$request->eleccion_id,
            "casilla_id"=>$request->casilla_id,
            "evidencia"=> $this->getEvidence($request)
        ];
        return $dataVote;
    }

    // ============================================================================================
    private function getCandidates(Request $request) {
        $candidates = [];
        foreach($request->all() as $key=>$value){
            if (substr($key,0,10)=="candidato_")
                $candidates[substr($key,10)]=$value;
        }
        return $candidates;
    }

    // ============================================================================================
    private function saveVoteCandidates($candidates, $vote_id) {
        foreach($candidates as $key=>$value){
            $voteCandidate=[];
            $voteCandidate['voto_id']= $vote_id;
            $voteCandidate['candidato_id'] = $key;
            $voteCandidate['votos']=$value;
            Votocandidato::create($voteCandidate);
        }
    }

    // ============================================================================================
    private function updateVoteCandidates($candidates, $vote_id) {
        foreach($candidates as $key=>$value){
            $voteCandidate=[];
            $voteCandidate['voto_id']= $vote_id;
            $voteCandidate['candidato_id'] = $key;
            $voteCandidate['votos']=$value;
            Votocandidato::where('voto_id',$vote_id)->
                    where('candidato_id',$key)->update($voteCandidate);

        }
    }

    /** ===========================================================================================
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        if ($this->validateVote($request)) {
            $dataVote= $this->getDataVote($request);
            $candidates =  $this->getCandidates($request);
            $success= true;
            $message="save successful...";
            DB::beginTransaction();
            try {

                $vote =Voto::create($dataVote);
                $this->saveVoteCandidates($candidates, $vote->id);
                DB::commit();

            } catch (\Exception $e) {
                $success=false;
                DB::rollback();
                if ($e->getCode()==$this->DUPLICATE_KEY_CODE)
                    $message=$this->DUPLICATE_KEY_MESSAGE;
                else
                    $message=$e->getMessage();
            }
            return view('message',compact('message','success'));

        } else {
            $message="Votos no válidos";
            $success=false;
            return view('message',compact('message','success'));
        }


    }

    /** ===========================================================================================
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //Empty
    }

    /** ===========================================================================================
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $voto= Voto::find($id);
        if ($voto) { 
            return view('voto.edit',compact('voto'));
        } else {
            $message="Busqueda no coincide";
            $success=false;
            return view('message',compact('message','success'));
        }
    }

    /** ===========================================================================================
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        if ($this->validateVote($request)){
            $dataVote= $this->getDataVote($request);
            $candidates =  $this->getCandidates($request);
            $success= true;
            $message="save successful...";
            DB::beginTransaction();
            try {

                $vote = Voto::whereId($id)->update($dataVote);
                $this->updateVoteCandidates($candidates,$id);
                DB::commit();

            } catch (\Exception $e) {
                $success=false;
                DB::rollback();
                $message=$e->getMessage();
            }
            return view('message',compact('message','success'));

        } else {
            $message="Votos no válidos";
            $success=false;
            return view('message',compact('message','success'));
        }
    }

    /** ===========================================================================================
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::beginTransaction();
        $success=true;
        try {
            Votocandidato::where('voto_id', '=', $id)->delete();
            Voto::whereId($id)->delete();
            DB::commit();
            $message="Operacion exitosa";

        } catch (\Exception $ex){
            DB::rollBack();
            $message = $ex->getMessage();
            $success=false;
        }     
        return view ('message',compact('message','success'));
    }
}

