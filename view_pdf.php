<?php
include 'database.php';

// Verificar si se proporcionó un ID válido
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del registro específico
    $sql = "SELECT * FROM datos WHERE id = $id";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdfPath = $row['id'];

        // Mostrar el PDF si existe
        if(!empty($pdfPath)) {
            echo "<embed src='$pdfPath' type='application/pdf' width='100%' height='600px' />";
        } else {
            echo "No se encontró el PDF asociado.";
        }
    } else {
        echo "No se encontró el registro.";
    }
} else {
    echo "ID no válido.";
}

$conn->close();
?>
