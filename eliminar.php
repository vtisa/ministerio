<?php
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_productos = $_GET['id'];

    $sql = "DELETE FROM productos WHERE id = $id_productos";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        $mensaje = "Producto eliminado satisfactoriamente";
    } else {
        $mensaje = "Error al eliminar el producto: " . mysqli_error($conn);
    }

    header("Location: index.php?msg=" . urlencode($mensaje));
    exit();
}
?>

