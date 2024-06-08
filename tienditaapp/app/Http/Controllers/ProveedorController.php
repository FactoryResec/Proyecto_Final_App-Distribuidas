<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;


class ProveedorController extends Controller
{

    // Declarar la URL del servicio SOAP una vez
    private $wsdl = 'http://localhost:8080/ProyectoTiendita/ProveedorService?WSDL';

    public function create()
    {
        return view('proveedor.create');
    }

    public function store(Request $request)
{
    // Validación de los datos del formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'empresa' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'ciudad' => 'required|string|max:255',
    ]);

    // Datos del proveedor a insertar
    $data = [
        'nombre' => $request->input('nombre'),
        'empresa' => $request->input('empresa'),
        'telefono' => $request->input('telefono'),
        'ciudad' => $request->input('ciudad'),
    ];

    try {

        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl);

        // Llamar al método SOAP para insertar el proveedor
        $response = $client->insertarProveedor($data);

        // Imprimir la respuesta para inspección (puedes comentar o eliminar esta línea en producción)
        // dd($response);

        // Verificar la respuesta del servicio SOAP
        if (is_object($response)) {
            // Suponiendo que el servicio devuelve una propiedad 'mensaje' en la respuesta
            if (isset($response->mensaje) && $response->mensaje == 'Proveedor insertado con éxito') {
                // Redirigir al índice de proveedores con un mensaje de éxito
                return redirect()->route('proveedores.index')
                    ->with('success', $response->mensaje);
            } else {
                // En caso de error en la respuesta del servicio SOAP
                return redirect()->route('proveedores.index')
                    ->with('error', 'Error al crear el proveedor: ' . $response->mensaje);
            }
        } else {
            // Manejar otro tipo de respuesta inesperada
            return redirect()->route('proveedores.index')
                ->with('error', 'Respuesta inesperada del servicio SOAP.');
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('proveedores.index')
            ->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function index()
{
    try {
        
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl);
        
        // Llamar al método SOAP para obtener todos los proveedores
        $response = $client->getAllProveedores()->return;
        
        // Convertir la respuesta a una colección de Laravel
        $proveedores = collect($response)->map(function($item) {
            return (object) [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'empresa' => $item->empresa,
                'telefono' => $item->telefono,
                'ciudad' => $item->ciudad
            ];
        });

        // Pasar los proveedores a la vista
        return view('proveedor.index', ['proveedores' => $proveedores]);
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('proveedores.index')
            ->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function edit($id)
{
    try {
        // URL del servicio SOAP
        
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl);
        
        // Llamar al método SOAP para obtener todos los proveedores
        $response = $client->getAllProveedores()->return;
        
        // Buscar el proveedor por su ID
        $proveedor = null;
        foreach ($response as $item) {
            if ($item->id == $id) {
                $proveedor = (object) [
                    'id' => $item->id,
                    'nombre' => $item->nombre,
                    'empresa' => $item->empresa,
                    'telefono' => $item->telefono,
                    'ciudad' => $item->ciudad
                ];
                break;
            }
        }

        if (!$proveedor) {
            // Si el proveedor no se encuentra, redirigir a la lista de proveedores con un mensaje de error
            return redirect()->route('proveedores.index')
                ->with('error', 'Proveedor no encontrado.');
        }

        // Pasar el proveedor a la vista de edición
        return view('proveedor.edit', compact('proveedor'));
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('proveedores.index')
            ->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function update(Request $request, $id)
{
    // Validación de los datos del formulario
    $request->validate([
        'nombre' => 'required|string|max:255',
        'empresa' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'ciudad' => 'required|string|max:255',
    ]);

    try {
        // URL del servicio SOAP
        

        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl);

        // Llamar al método SOAP para actualizar el proveedor
        $response = $client->actualizarProveedor([
            'id' => $id,
            'nombre' => $request->input('nombre'),
            'empresa' => $request->input('empresa'),
            'telefono' => $request->input('telefono'),
            'ciudad' => $request->input('ciudad'),
        ]);

        // Verificar la respuesta del servicio SOAP
        if ($response === "Proveedor actualizado exitosamente.") {
            // Redirigir a la lista de proveedores con un mensaje de éxito
            return redirect()->route('proveedores.index')->with('success', $response);
        } else {
            // En caso de error en la respuesta del servicio SOAP
            return redirect()->route('proveedores.index')->with('error', $response);
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('proveedores.index')->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function destroy($id)
{
    try {

        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl);

        // Llamar al método SOAP para eliminar el proveedor
        $response = $client->eliminarProveedor(['id' => $id]);

        // Verificar la respuesta del servicio SOAP
        if ($response === "Proveedor eliminado exitosamente.") {
            // Redirigir a la lista de proveedores con un mensaje de éxito
            return redirect()->route('proveedores.index')->with('success', $response);
        } else {
            // En caso de error en la respuesta del servicio SOAP
            return redirect()->route('proveedores.index')->with('error', $response);
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('proveedores.index')->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}

}
