<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    /**
     * Mostrar el formulario de importación.
     */
    public function show()
    {
        return view('products.import');
    }

    /**
     * Manejar la petición de importación.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            
            return back()->with('success', 'Productes importats correctament.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            
            return back()->with('error', 'Error en la validació de les dades.')->with('failures', $failures);
        } catch (\Exception $e) {
            return back()->with('error', 'S\'ha produït un error durant la importació: ' . $e->getMessage());
        }
    }
    /**
     * Manejar la petición de importación vía API.
     */
    public function storeAPI(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return response()->json(['message' => 'Productes importats correctament.'], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json(['message' => 'Error en la validació de les dades.', 'failures' => $e->failures()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'S\'ha produït un error durant la importació: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Manejar la petición de exportación vía API.
     */
    public function exportAPI(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        return Excel::download(new \App\Exports\ProductsExport, 'productos.csv');
    }
}
