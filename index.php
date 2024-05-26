<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Datos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Tabla de Datos</h1>

    <div class="search-container">
        <form id="searchForm" method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Buscar..." value="">
            <input type="date" id="fecha_inicio" name="fecha_inicio" placeholder="Fecha Inicio" value="">
            <input type="date" id="fecha_fin" name="fecha_fin" placeholder="Fecha Fin" value="">
            <select id="año" name="año">
                <option value="">Seleccionar Año</option>
                <?php
                $sql_años = "SELECT DISTINCT año FROM datos ORDER BY año ASC";
                $result_años = $conn->query($sql_años);
                while($row_años = $result_años->fetch_assoc()) {
                    echo "<option value='" . $row_años['año'] . "'>" . $row_años['año'] . "</option>";
                }
                ?>
            </select>
            <button type="button" id="resetButton">Borrar Filtros</button>
        </form>
    </div>

    <div id="table-container">
        <?php include 'fetch_data.php'; ?>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('input', function() {
            fetchData();
        });

        document.getElementById('resetButton').addEventListener('click', function() {
            document.getElementById('searchForm').reset();
            fetchData();
        });

        function fetchData(page = 1) {
            const search = document.getElementById('search').value;
            const fecha_inicio = document.getElementById('fecha_inicio').value;
            const fecha_fin = document.getElementById('fecha_fin').value;
            const año = document.getElementById('año').value;

            const params = new URLSearchParams({
                search,
                fecha_inicio,
                fecha_fin,
                año,
                pagina: page
            });

            fetch('fetch_data.php?' + params.toString())
                .then(response => response.text())
                .then(data => {
                    document.getElementById('table-container').innerHTML = data;
                });
        }
    </script>
</body>
</html>
