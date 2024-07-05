<?php

class Ventas{
    public $id;
    public $fecha;
    public $email;
    public $usuario;
    public $idProducto;
    public $cantidad;
    public $imagen;
    public $precio;

    public function AltaVenta(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (fecha, email, usuario, idProducto, precio, cantidad, imagen) VALUES (:fecha, :email, :usuario, :idProducto, :precio, :cantidad, :imagen)");
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function GuardarFoto($foto, $tipo, $nombre, $marca, $extension, $email)
    {
        $nombreEmail = explode('@', $email);
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeVentas/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $tipo . "-" . $marca."-" .$nombre ."-".$nombreEmail[0]. "." . $extension;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return $destino;
        } else {
            return "Error al cargar la foto";
        }
    }

    public static function FiltrarPorFecha($fecha){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) AS productosVendidos FROM ventas WHERE fecha = :fecha");
        $consulta->bindValue(':fecha',$fecha);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function ListarVentasPorUsuario($usuario){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE email = :email");
        $consulta->bindValue(':email',$usuario);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ventas');
    }

    public static function ListarVentasPorProducto($tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT ventas.id, tienda.tipo FROM ventas JOIN tienda ON ventas.idProducto = tienda.id WHERE tienda.tipo = :tipo");
        $consulta->bindValue(':tipo',$tipo);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }

    public static function ListarVentasEntrePrecios($min,$max){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas WHERE precio BETWEEN :min AND :max");
        $consulta->bindValue(':min',$min);
        $consulta->bindValue(':max',$max);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Ventas");
    }

    public static function SumarGananciasDelDia($fecha){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(precio) AS gananciasDelDia FROM ventas WHERE fecha = :fecha");
        $consulta->bindValue(':fecha',$fecha);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }

    public static function SumarGananciasTotales(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(precio) AS gananciasDelDia FROM ventas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }

    public static function BuscarProductoMasVendido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT tienda.nombre, SUM(ventas.cantidad) as cantidad FROM ventas JOIN tienda on ventas.idProducto = tienda.id GROUP BY tienda.nombre ORDER BY cantidad DESC LIMIT 1;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }
}