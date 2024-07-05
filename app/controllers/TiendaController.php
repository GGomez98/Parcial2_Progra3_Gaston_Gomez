<?php
require_once './models/Tienda.php';
require_once './interfaces/IApiUsable.php';

class TiendaController extends Tienda implements IApiUsable
{
	public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
        $marca = $parametros['marca'];
        $stock = $parametros['stock'];

        $tipo_archivo = $_FILES['imagen']['type'];
        $tamano_archivo = $_FILES['imagen']['size'];
        $extension = "";

        if ((strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg")) && ($tamano_archivo < 300000)) {
            $extension = substr($tipo_archivo, strpos($tipo_archivo, '/') + 1);
        } else {
            $payload = json_encode(array("mensaje" => "La imagen no tiene un formato o tamaño que sean admitidos. El producto no ha sido creado."));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $prod = Tienda::VerificarAlta($nombre, $marca, $tipo);
        if($prod){
            $prod->ActualizarPrecioYStock($precio,$stock);
            $payload = json_encode(array("mensaje" => "El producto fue actualizado"));
        }
        else{
            $imagen = Tienda::GuardarFoto($_FILES['imagen'], $tipo, $nombre, $extension);

        $prod = new Tienda();
        $prod->nombre = $nombre;
        $prod->precio = $precio;
        $prod->tipo = $tipo;
        $prod->marca = $marca;
        $prod->stock = $stock;
        $prod->imagen = $imagen;

        $prod->altaTienda();

        $payload = json_encode(array("mensaje" => "Producto cargado con exito"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ConsultarProducto ($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $marca = $parametros['marca'];
        $tipo = $parametros['tipo'];

        $payload = Tienda::productoExiste($nombre, $marca, $tipo);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
	public function BorrarUno($request, $response, $args){}
	public function ModificarUno($request, $response, $args){}
    public function TraerUno($request, $response, $args){}
	public function TraerTodos($request, $response, $args){}
}