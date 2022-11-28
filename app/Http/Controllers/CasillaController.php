<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Casilla;
use Barryvdh\DomPDF\Facade as PDF;

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
    public function create()
    {
        return view('casilla/create');
    }
    // ============================================================================================
    private function validateData(request $request)
    {
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
    public function store(Request $request)
    {
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
    public function show($id)
    {
        echo "Element $id";
    }
    /** ===========================================================================================
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
    public function update(Request $request, $id)
    {
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
    public function destroy($id)
    {
        casilla::whereId($id)->delete();
        return redirect('casilla')
        ->with('success', 'El elemento fué borrado ...'
        );
    }
    //===========================================================================================
    public function generatepdf()
    {
        $casillas = Casilla::all();
        $pdf = PDF::loadView('casilla/list', ['casillas'=>$casillas]);
        return $pdf->stream('archivo.pdf');

         $html = "<div style='text-align:center;'><h1>PDF generado desde etiquetas html</h1>
                     <br><h3>&copy;Mario.dev</h3> </div>";
                     $pdf = PDF::loadHTML($html);
                     return $pdf->stream('archivo.pdf');

           $html="<style>
           .page-break {page-break-after: always;}
           </style><h1>Pagina 1</h1><div class='page-break'></div>
               <h1>Pagina 2</h1>";
               $pdf = PDF::loadHTML($html);
               return $pdf->stream('archivo.pdf');
    }
}
