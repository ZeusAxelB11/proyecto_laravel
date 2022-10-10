<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Casilla;

class CasillaController extends Controller
{
    /** ===========================================================================================
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $casillas = Casilla::all();
        return view('casilla/list', compact('casillas'));
    }

    /** ===========================================================================================
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('casilla/create');
    }

    // ============================================================================================
    private function validateData(request $request) {
        $request->validate([
            'ubicacion' => 'required|max:100',
        ]);
    }

    /** ===========================================================================================
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validateData($request);

        $data['ubicacion'] = $request->ubicacion;
        $casilla = casilla::create($data);
        return redirect('casilla')->with(
            'success',
            $casilla->ubicacion . 'guardado satisfactoriamente ...'
        );  
    }

    /** ===========================================================================================
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        echo "Element $id";
    }

    /** ===========================================================================================
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $casilla = Casilla::find($id);
        if ($casilla) {
            return view('casilla/edit', compact('casilla'));
        } else {
            echo 'No se encontro';
        }
    }

    /** ===========================================================================================
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validateData($request);
        $data['ubicacion'] = $request->ubicacion;
        Casilla::whereId($id)->update($data);
        return redirect('casilla')
            ->with('success', 'El cambio se realizó correctamente ...'
        );
    }

    /** ===========================================================================================
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        casilla::whereId($id)->delete();
        return redirect('casilla')
        ->with('success', 'El elemento fué borrado ...'
        );
    }
}
