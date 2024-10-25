<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include "conexion.php";

$sql = "SELECT productos.id, productos.nombre, productos.descripcion, flujo.tipo AS flujo_tipo, paises.nombre_pais, paises.tipo_relacion, paises.fecha_relacion
        FROM productos
        INNER JOIN flujo ON productos.flujo_id = flujo.id
        INNER JOIN paises ON productos.pais_id = paises.id
        ORDER BY productos.id ASC";

$resultado = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $id_a_eliminar = $_POST['eliminar'];
    $sql_eliminar = "DELETE FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql_eliminar);
    mysqli_stmt_bind_param($stmt, "i", $id_a_eliminar);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?msg=Producto eliminado satisfactoriamente");
        exit;
    } else {
        echo "Error al eliminar el producto: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f5f5f5; 
            font-family: Arial, sans-serif;
            font-size: small;
        }

        .header {
            background-color: #007bff; 
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .header h1 {
            margin: 0;
        }

        table {
            width: 100%;
        }

        table thead {
            background-color: #343A40;
            color: white;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: none;
        }

        .btn-success, .btn-primary, .btn-danger {
            color: white;
            border: none;
        }

        .btn-success {
            background-color: #4CAF50; 
        }

        .btn-danger {
            background-color: #FF5733; 
        }

        .btn-primary {
            background-color: #337AB7; 
        }

        .alert {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .close-message {
            cursor: pointer;
        }

        .product-section {
            display: flex;
            background-color: #007bff; 
            color: white;
            justify-content: center;
            height: 3.3rem;
            text-align: center;
            padding: 0.5px 0;
            margin-top: 2rem;
            width: 73rem;
        }

        .search-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }

        .search-input {
            flex: 0.7; 
        }

        .search-container {
            display: flex;
            align-items: center;
            width: 50rem;
            margin-left: 8rem;
        }

        .search-button {
            background: #4CAF50; 
            cursor: pointer;
            margin-left: 2rem;
        }

        .btn-add {
            background-color: #FF5733;
            color: white;
            margin-left: 2rem;
            height: 2rem;
            margin-top: 0.6rem;
        }

        .actions {
            display: flex;
            justify-content: space-between;
        }

        .actions button, .actions a {
            padding: 5px 10px;
            font-size: 14px;
            margin: 0 5px;
        }
    </style>
</head>

<body>
<nav class="header">
    <a href="index.php" class="btn btn-primary"><i class="bi bi-house-door-fill"></i> Inicio</a>
    <div class="search-container">
        <input type="text" id="search-input" class="form-control search-input" placeholder="Buscar productos..." aria-label="Search">
        <button class="search-button" id="search-button"><i class="bi bi-search"></i></button>
    </div>
    <a href="login.php" class="btn btn-danger">Cerrar Sesión <i class="bi bi-box-arrow-right"></i></a>
</nav>

<?php
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<div class="alert alert-warning" role="alert">
            ' . $msg . '
            <button type="button" class="btn-close close-message" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<div class="container" style="max-width: fit-content;" class="resposive">
    <div class="product-section">
        <h1>Lista de productos</h1>
        <?php
        if ($_SESSION['nivel'] === 'superadmin') {
            echo '<a href="agregar_producto.php" class="btn btn-add"><i class="bi bi-plus"></i> Agregar</a>';
        }
        ?>
    </div>
    <table class="table table-striped table-hover table-bordered table-outer-borders">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Tipo de Flujo</th>
                <th>Nombre del País</th>
                <th>Tipo de Relación</th>
                <th>Fecha de Relación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo '<tr class="table-row">';
                echo '<td>' . $fila['id'] . '</td>';
                echo '<td>' . $fila['nombre'] . '</td>';
                echo '<td>' . $fila['descripcion'] . '</td>';
                echo '<td>' . $fila['flujo_tipo'] . '</td>';
                echo '<td>' . $fila['nombre_pais'] . '</td>';
                echo '<td>' . $fila['tipo_relacion'] . '</td>';
                echo '<td>' . $fila['fecha_relacion'] . '</td>';
                echo '<td>';
                echo '<form method="POST" style="display: inline;">';
                if ($_SESSION['nivel'] === 'superadmin') {
                    echo '<button type="submit" name="eliminar" value="' . $fila['id'] . '" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>';
                }
                echo '</form>';
                if ($_SESSION['nivel'] === 'superadmin' || $_SESSION['nivel'] === 'usuario_general') {
                    echo '<a href="editar.php?id=' . $fila['id'] . '" class="btn btn-primary btn-sm mx-1"><i class="bi bi-pencil"></i></a>';
                }
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const closeMessageButtons = document.querySelectorAll('.close-message');
        closeMessageButtons.forEach(function(button) {
            button.addEventListener('click', function () {
                const message = button.parentElement;
                message.style.display = 'none';
            });
        });

        const searchInput = document.querySelector('#search-input');
        const searchButton = document.querySelector('#search-button');

        searchButton.addEventListener('click', function () {
            const searchTerm = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(function(row) {
                const rowData = row.textContent.toLowerCase();

                if (rowData.includes(searchTerm)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>
