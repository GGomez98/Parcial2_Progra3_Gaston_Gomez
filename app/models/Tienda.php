<?php

class Tienda{
    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $marca;
    public $stock;
    public $imagen;

    public function altaTienda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO tienda (nombre, precio, tipo, marca, stock, imagen) VALUES (:nombre, :precio, :tipo, :marca, :stock, :imagen)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function GuardarFoto($foto, $tipo, $nombre, $extension)
    {
        //Carpeta donde voy a guardar los archivos
        $carpeta_archivos = 'ImagenesDeProductos/2024/';
        // Ruta final, carpeta + nombre del archivo
        $destino = $carpeta_archivos . $tipo . "-" . $nombre . "." . $extension;

        if (move_uploaded_file($foto['tmp_name'], $destino)) {
            return $destino;
        } else {
            return "Error al cargar la foto";
        }
    }

    public static function VerificarAlta($nombre, $marca, $tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM tienda WHERE nombre = :nombre AND marca = :marca AND tipo = :tipo");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->bindValue(':marca', $marca, PDO::PARAM_STR);
        $consulta->execute();

        $data = $consulta->fetchObject('Tienda');
        return $data;
    }

    public static function ProductoExiste($nombre, $marca, $tipo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $data = self::VerificarAlta($nombre, $marca, $tipo);

        if ($data) {
            $payload = json_encode(array("mensaje" => "El producto existe."));
        } else {
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM tienda WHERE nombre = :nombre AND marca = :marca");

            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':marca', $marca, PDO::PARAM_STR);
            $consulta->execute();
            $data = $consulta->fetchObject('Tienda');

            if ($data) {
                $payload = json_encode(array("mensaje" => "No hay productos del tipo " . $tipo));
            } else {
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM tienda WHERE nombre = :nombre AND tipo = :tipo");

                $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);

                $consulta->execute();

                $data = $consulta->fetchObject('Tienda');
                if ($data) {
                    $payload = json_encode(array("mensaje" => "No hay productos de la marca " . $marca));
                } else {
                    $payload = json_encode(array("mensaje" => "No hay productos que coincidan con el criterio."));
                }
            }
        }
        return $payload;
    }

    public function ActualizarPrecioYStock($nuevoPrecio, $stock)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        //Actualizo valores
        $nuevoStock = $this->stock + $stock;

        $consulta = $objAccesoDato->prepararConsulta("UPDATE tienda SET precio = :nuevo_precio, stock = :nuevo_stock WHERE id = :id");

        $consulta->bindValue(':nuevo_precio', $nuevoPrecio, PDO::PARAM_STR);
        $consulta->bindValue(':nuevo_stock', $nuevoStock, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function RestarStock($cantidad){
        $objAccesoDato = AccesoDatos::obtenerInstancia();

        $nuevoStock = $this->stock - $cantidad;

        $consulta = $objAccesoDato->prepararConsulta("UPDATE tienda SET stock = :nuevo_stock WHERE id = :id");

        $consulta->bindValue(':nuevo_stock', $nuevoStock, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
}