<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPdfEmail;
use App\Mail\InvitacionMailable;

class VentaController extends Controller
{
    private $wsdl2 = 'http://localhost:8080/ProyectoTiendita/ProductoService?WSDL';
    
    public function create()
    {
    
        return view('venta.create');
    }
    public function createMail()
    {
        
        return view('venta.mail');
    }

    public function generatePDF(Request $request)
{
    $selectedProducts = $request->input('selectedProducts');
    $totalAmount = $request->input('totalAmount');

    if ($selectedProducts && $totalAmount) {
        $selectedProducts = json_decode($selectedProducts, true); // Decodificar el JSON de los productos seleccionados

        try {
            // Crear cliente SOAP
            $client = new \SoapClient($this->wsdl2);

            foreach ($selectedProducts as $product) {
                // Obtener el producto desde el servicio SOAP
                $productData = $client->obtenerProducto(['id' => $product['id']]);

                if ($productData) {
                    $productData = $productData->return;

                    // Actualizar los datos del producto
                    $productData->stock -= $product['quantity'];
                    $productData->noVentas += $product['quantity'];

                    // Llamar al servicio SOAP para actualizar el producto
                    $client->modificarProductoStockYVentas([
                        'id' => $productData->id,
                        'stock' => $productData->stock,
                        'noVentas' => $productData->noVentas,
                    ]);
                }
            }

            $data = [
                'selectedProducts' => $selectedProducts,
                'totalAmount' => $totalAmount,
            ];

            $pdf = Pdf::loadView('venta.ticket', $data);
            return $pdf->download('ticket.pdf');
        
        } catch (\Exception $e) {
            Log::error('Error updating products: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating PDF and updating products'], 500);
        }
    } else {
        return response()->json(['error' => 'Invalid input data'], 400);
    }
}









    
    public function sendReport(Request $request)
    {
        $data = [
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
            'file' => $request->input('file') ?? ''
        ];

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $filename = $file->getClientOriginalName();

            $file->storeAs('pdfs', $filename);

            $data['file'] = $filename;
        }

        Mail::to($request->input('email'))->send(new InvitacionMailable($data));
        return redirect()->route('ventas.create')->with('success', 'Se compartió el archivo con éxito!!.');

    }
    
}
