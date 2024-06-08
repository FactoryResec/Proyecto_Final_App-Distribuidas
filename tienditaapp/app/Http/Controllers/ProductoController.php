<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Proveedor;
use App\Models\Producto;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use SoapClient;

class ProductoController extends Controller
{

    // Declarar la URL del servicio SOAP una vez
    private $wsdl = 'http://localhost:8080/ProyectoTiendita/ProveedorService?WSDL';
    private $wsdl2 = 'http://localhost:8080/ProyectoTiendita/ProductoService?WSDL';
    

    public function regreso()
    {
        return view('auth.principal');
    }

    public function create()
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
            return view('producto.create', ['proveedores' => $proveedores]);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
        }
    }


    public function search(Request $request)
    {
        $query = $request->get('query');
    
        try {
            // Crear cliente SOAP
            $client = new \SoapClient($this->wsdl2);
    
            // Llamar al método SOAP para obtener todos los productos
            $response = $client->mostrarProductos()->return;
    
            // Convertir la respuesta a una colección de Laravel
            $productos = collect($response)->map(function($item) {
                return (object) [
                    'id' => $item->id,
                    'codigoDeBarras' => $item->codigoDeBarras,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'stock' => $item->stock,
                    'precio' => $item->precio,
                    'proveedorId' => $item->proveedorId,
                    'image' => $item->image,
                ];
            });
    
            // Filtrar los productos por código de barras
            $producto = $productos->first(function($producto) use ($query) {
                return strpos($producto->codigoDeBarras, $query) !== false;
            });
    
            // Si se encuentra el producto, agregar la URL de la imagen
            if ($producto) {
                $producto->image_url = $producto->image ? asset('storage/' . $producto->image) : null;
            }
    
            return response()->json(['product' => $producto]);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Error al conectar con el servicio SOAP: ' . $e->getMessage()], 500);
        }
    }
    



public function store(Request $request)
{
    // Validación de los datos del formulario
    $request->validate([
        'codigo_de_barras' => 'required|string',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'stock' => 'required|integer',
        'precio' => 'required|numeric',
        'proveedor_id' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Datos del producto a insertar
    $data = [
        'codigoDeBarras' => $request->input('codigo_de_barras'),
        'nombre' => $request->input('nombre'),
        'descripcion' => $request->input('descripcion'),
        'stock' => $request->input('stock'),
        'precio' => $request->input('precio'),
        'proveedorId' => $request->input('proveedor_id'),
        'image' => $request->hasFile('image') ? $request->file('image')->getClientOriginalName() : null,
    ];

    if ($request->hasFile('image')) {
        // La imagen se guarda con un nombre único generado por Laravel
        $imagePath = $request->file('image')->store('images', 'public');
        // Guardar la ruta generada por Laravel
        $data['image'] = $imagePath;
    }

    try {
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl2);

        // Llamar al método SOAP para insertar el producto
        $response = $client->insertarProducto($data);

        // Verificar la respuesta del servicio SOAP
        if (is_object($response)) {
            // Suponiendo que el servicio devuelve una propiedad 'mensaje' en la respuesta
            if (isset($response->mensaje) && $response->mensaje == 'Producto insertado con éxito') {
                // Redirigir al índice de productos con un mensaje de éxito
                return redirect()->route('productos.index')->with('success', $response->mensaje);
            } else {
                // En caso de error en la respuesta del servicio SOAP
                $errorMessage = isset($response->mensaje) ? $response->mensaje : 'Error desconocido';
                return redirect()->route('productos.index')->with('error', 'Error al crear el producto: ' . $errorMessage);
            }
        } else {
            // Manejar otro tipo de respuesta inesperada
            return redirect()->route('productos.index')->with('error', 'Respuesta inesperada del servicio SOAP.');
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return redirect()->route('productos.index')->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


    public function index()
    {
        try {
            // Crear cliente SOAP
            $client = new \SoapClient($this->wsdl2);
            
            // Llamar al método SOAP para obtener los productos
            $response = $client->mostrarProductos()->return;
            
            // Convertir la respuesta a una colección de Laravel
            $productos = collect($response)->map(function($item) {
                return (object) [
                    'id' => $item->id,
                    'codigoDeBarras' => $item->codigoDeBarras,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'stock' => $item->stock,
                    'precio' => $item->precio,
                    'proveedorId' => $item->proveedorId,
                    'image' => $item->image
                ];
            });
    
            // Pasar la colección de productos a la vista
            return view('producto.index', compact('productos'));
        } catch (\Exception $e) {
            // Manejo de excepciones con establecimiento de la variable $productos
            $error = 'Error al conectar con el servicio SOAP: ' . $e->getMessage();
            $productos = []; // Define una matriz vacía de productos
            return view('producto.index', compact('productos', 'error'));
        }
    }
    
    

    public function edit($id)
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
    

             // Crear cliente SOAP
            $client2 = new \SoapClient($this->wsdl2);
            // Llamar al método SOAP para obtener los productos
            $response2 = $client2->mostrarProductos()->return;

            // Buscar el proveedor por su ID
        $producto = null;

        foreach ($response2 as $item) {
            if ($item->id == $id) {
                $producto = (object) [
                    'id' => $item->id,
                    'codigoDeBarras' => $item->codigoDeBarras,
                    'nombre' => $item->nombre,
                    'descripcion' => $item->descripcion,
                    'stock' => $item->stock,
                    'precio' => $item->precio,
                    'proveedorId' => $item->proveedorId,
                    'image' => $item->image
                ];
                break;
            }
        }

            // Pasar los proveedores y el producto a la vista
            return view('producto.edit', ['proveedores' => $proveedores, 'producto' => $producto]);
        } catch (\Exception $e) {
            // Manejo de excepciones
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $id)
{
    // Validación de los datos del formulario
    $request->validate([
        'codigo_de_barras' => 'required|string',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'stock' => 'required|integer',
        'precio' => 'required|numeric',
        'proveedor_id' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    try {
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl2);

        // Obtener los datos del formulario
        $data = [
            'id' => $id,
            'codigoDeBarras' => $request->input('codigo_de_barras'),
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'stock' => $request->input('stock'),
            'precio' => $request->input('precio'),
            'proveedorId' => $request->input('proveedor_id'),
            'image' => $request->hasFile('image') ? $request->file('image')->getClientOriginalName() : null,
        ];

        // Si se carga una nueva imagen, almacenarla en la carpeta y actualizar el nombre en los datos
        if ($request->hasFile('image')) {
            // La imagen se guarda con un nombre único generado por Laravel
            $imagePath = $request->file('image')->store('images', 'public');
            // Guardar la ruta generada por Laravel
            $data['image'] = $imagePath;
        }

        // Llamar al método SOAP para actualizar el producto
        $response = $client->modificarProducto($data);

        // Verificar la respuesta del servicio SOAP
        if (is_object($response) && isset($response->mensaje)) {
            // Suponiendo que el servicio devuelve una propiedad 'mensaje' en la respuesta
            if ($response->mensaje == 'Producto modificado con éxito') {
                // Redirigir al índice de productos con un mensaje de éxito
                return redirect()->route('productos.index')->with('success', $response->mensaje);
            } else {
                // En caso de error en la respuesta del servicio SOAP
                return back()->with('error', 'Error al modificar el producto: ' . $response->mensaje);
            }
        } else {
            // Manejar otro tipo de respuesta inesperada
            return back()->with('error', 'Respuesta inesperada del servicio SOAP.');
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return back()->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function destroy($id)
{
    try {
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl2);

        // Llamar al método SOAP para eliminar el producto
        $response = $client->eliminarProducto(['id' => $id]);

        // Verificar la respuesta del servicio SOAP
        if (is_string($response)) {
            // Suponiendo que el servicio devuelve un mensaje de éxito o error
            if ($response == 'Producto eliminado con éxito') {
                // Redirigir al índice de productos con un mensaje de éxito
                return redirect()->route('productos.index')->with('success', $response);
            } else {
                // En caso de error en la respuesta del servicio SOAP
                return back()->with('error', 'Error al eliminar el producto: ' . $response);
            }
        } else {
            // Manejar otro tipo de respuesta inesperada
            return back()->with('error', 'Respuesta inesperada del servicio SOAP.');
        }
    } catch (\Exception $e) {
        // Manejo de excepciones
        return back()->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function graficaProductosMasVendidos()
{
    try {
        // Crear cliente SOAP
        $client = new \SoapClient($this->wsdl2);

        // Llamar al método SOAP para obtener los productos más vendidos
        $response = $client->obtenerProductosMasVendidos()->return;

        // Convertir la respuesta a una colección de Laravel
        $productosMasVendidos = collect($response)->map(function($item) {
            return (object) [
                'id' => $item->id,
                'codigoDeBarras' => $item->codigoDeBarras,
                'nombre' => $item->nombre,
                'descripcion' => $item->descripcion,
                'stock' => $item->stock,
                'precio' => $item->precio,
                'proveedorId' => $item->proveedorId,
                'noVentas' => $item->noVentas,
                'image' => $item->image
            ];
        });

        // Retornar la vista con los datos de los productos más vendidos
        return view('producto.grafica', ['productosMasVendidos' => $productosMasVendidos]);

    } catch (\Exception $e) {
        // Manejo de excepciones
        return back()->with('error', 'Error al conectar con el servicio SOAP: ' . $e->getMessage());
    }
}


public function lowStock()
{
    // URL del servicio web
    $url = $this->wsdl2;
    
    // Hacer la solicitud al servicio web
    $response = Http::post($url, [
        'stockMinimo' => 10 // Valor del stock mínimo
    ]);

    // Verificar si la solicitud fue exitosa
    if ($response->successful()) {
        $lowStockProducts = $response->json();
        return response()->json(['lowStockProducts' => $lowStockProducts]);
    } else {
        // Manejar el error en caso de que la solicitud falle
        return response()->json(['error' => 'Error al obtener los productos con bajo stock.'], 500);
    }
}



}
