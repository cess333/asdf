<?php
include 'database.php';

$resultados_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $resultados_por_pagina;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$año = isset($_GET['año']) ? $_GET['año'] : '';

$where = "WHERE (nombre LIKE '%$search%' OR id LIKE '%$search%' OR fecha LIKE '%$search%' OR año LIKE '%$search%')";
if ($fecha_inicio && $fecha_fin) {
    $where .= " AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
} elseif ($fecha_inicio) {
    $where .= " AND fecha >= '$fecha_inicio'";
} elseif ($fecha_fin) {
    $where .= " AND fecha <= '$fecha_fin'";
}
if ($año) {
    $where .= " AND año = '$año'";
}

$sql = "SELECT * FROM datos $where LIMIT $inicio, $resultados_por_pagina";
$result = $conn->query($sql);

echo '<table id="dataTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Año</th>
            <th>Descargar</th>
            <th>Ver PDF</th> <!-- Nueva columna -->
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["fecha"] . "</td>";
        echo "<td>" . $row["año"] . "</td>";
        echo "<td><a href='download.php?id=" . $row["id"] . "' target='_blank'>Descargar</a></td>"; // Enlace de descarga
        echo "<td><a href='view_pdf.php?id=" . $row["id"] . "' target='_blank'>Ver PDF</a></td>"; // Enlace para ver PDF
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No se encontraron resultados.</td></tr>";
}

echo '</tbody>
</table>';

// Paginación
$sql_total = "SELECT COUNT(*) AS total FROM datos $where";
$resultado_total = $conn->query($sql_total);
$fila_total = $resultado_total->fetch_assoc();
$total_registros = $fila_total['total'];
$total_paginas = ceil($total_registros / $resultados_por_pagina);

$rango = 3; // Número de páginas a mostrar antes y después de la página actual

echo "<div class='pagination'>";
if ($pagina > 1) {
    echo "<a href='#' onclick='fetchData(1)'>&laquo;&laquo; Primera</a>";
    echo "<a href='#' onclick='fetchData(" . ($pagina - 1) . ")'>&laquo; Anterior</a>";
}
for ($i = max(1, $pagina - $rango); $i <= min($pagina + $rango, $total_paginas); $i++) {
    $class = $pagina == $i ? 'class="active"' : '';
    echo "<a href='#' $class onclick='fetchData($i)'>" . $i . "</a>";
}
if ($pagina < $total_paginas) {
    echo "<a href='#' onclick='fetchData(" . ($pagina + 1) . ")'>Siguiente &raquo;</a>";
    echo "<a href='#' onclick='fetchData($total_paginas)'>Última &raquo;&raquo;</a>";
}
echo "</div>";

$conn->close();
?>





