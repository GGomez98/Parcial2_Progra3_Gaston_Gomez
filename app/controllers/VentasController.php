<?php
require_once './models/Ventas.php';
require_once './models/Tienda.php';
require_once './interfaces/IApiUsable.php';

class VentasController extends Ventas implements IApiUsable
{
	public function CargarUno($request, $response, $args){
        $parametros = $request->getParsedBody();

        $nombreProducto = $parametros['producto'];
        $email = $parametros['email'];
        $usuario = $parametros['usuario'];
        $tipo = $parametros['tipo'];
        $marca = $parametros['marca'];
        $cantidad = $parametros['cantidad'];

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


        $prod = Tienda::VerificarAlta($nombreProducto, $marca, $tipo);

        if($prod){
            if($prod->stock < $cantidad){
                $payload = json_encode(array("mensaje" => "Stock insuficiente"));
            }
            else{
                $venta = new Ventas();
                $fecha = new DateTime();
                $venta->fecha = $fecha->format('Y-m-d');
                $venta->idProducto = $prod->id;
                $venta->email = $email;
                $venta->usuario = $usuario;
                $venta->cantidad = $cantidad;
                $venta->precio = $prod->precio * $cantidad;
                $venta->imagen = Ventas::GuardarFoto($_FILES['imagen'], $tipo, $nombreProducto, $marca, $extension, $email);

                $venta->AltaVenta();

                $prod->RestarStock($venta->cantidad);

                $payload = json_encode(array("mensaje" => "Venta realizada con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "El producto no existe"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ConsultarProductosVendidos ($request, $response, $args)
    {
        $parametros = $request->getQueryParams();
        if(isset($parametros['fecha'])){
            $fecha = $parametros['fecha'];
        }
        else{
            $fecha = new DateTime('yesterday');
            $fecha = $fecha->format('Y-m-d');
        }
        $ventas = Ventas::FiltrarPorFecha($fecha);

        $payload = "Ventas del ".$fecha.": ".(string)$ventas[0];

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerVentasPorUsuario($request, $response, $args){
        $parametros = $request->getQueryParams();
        $usuario = $parametros["email"];

        $ventas = Ventas::ListarVentasPorUsuario($usuario);

        $payload = json_encode(array("Ventas al usuario"=>$ventas));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerVentasPorProducto($request, $response, $args){
        $parametros = $request->getQueryParams();
        $tipo = $parametros["tipo"];

        $ventas = Ventas::ListarVentasPorProducto($tipo);

        $payload = json_encode(array("Ventas del producto"=>$ventas));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerVentasEntreValores($request, $response, $args){
        $parametros = $request->getQueryParams();
        $min = $parametros['min'];
        $max = $parametros['max'];

        $ventas = Ventas::ListarVentasEntrePrecios($min,$max);

        $payload = json_encode(array("Ventas por rango"=>$ventas));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerGananciasPorDia($request, $response, $args){
        $parametros = $request->getQueryParams();
        if(isset($parametros['fecha'])){
            $fecha = $parametros['fecha'];
            $ganancias = Ventas::SumarGananciasDelDia($fecha);
            $payload = json_encode(array("Ganancias de la fecha: "=>$ganancias));
        }
        else{
            $ganancias = Ventas::SumarGananciasTotales();
            $payload = json_encode(array("Ganancias totales: "=>$ganancias));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerElProductoMasVendido($request, $response, $args){
        $productoMasVendido = Ventas::BuscarProductoMasVendido();
        $payload = json_encode(array("Producto mas vendido: "=>$productoMasVendido));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
	public function BorrarUno($request, $response, $args){}
	public function ModificarUno($request, $response, $args){}
    public function TraerUno($request, $response, $args){}
	public function TraerTodos($request, $response, $args){}
}