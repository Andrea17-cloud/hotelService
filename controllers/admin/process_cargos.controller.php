<?php
session_start();
include "../../backend/data/db.conexion.php"; // Asegúrate de que esta ruta sea correcta para tu estructura de archivos

header('Content-Type: application/json'); // Indica que la respuesta será JSON

// Obtener los datos JSON del cuerpo de la solicitud (enviados por el fetch en JavaScript)
$input = json_decode(file_get_contents('php://input'), true);

// Validar que los datos esenciales estén presentes
if (!isset($input['ID_Reservacion']) || !isset($input['Cargos']) || !is_array($input['Cargos']) || empty($input['Cargos'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos de cargo inválidos o incompletos.']);
    exit();
}

$idReservacion = $input['ID_Reservacion'];
$cargosData = $input['Cargos'];
$totalFactura = 0; // Este total se calculará en el backend para mayor seguridad

try {
    $conexion->beginTransaction(); // Iniciar una transacción para asegurar la atomicidad de las operaciones

    // 1. Crear Factura_Encabezado
    // El total se actualizará después de procesar los detalles
    $stmt = $conexion->prepare("INSERT INTO Factura_Encabezado (FK_ID_Reservacion, Fecha_Factura, Total, Estatus) VALUES (:idReservacion, NOW(), :total, 'pendiente')");
    $stmt->bindParam(':idReservacion', $idReservacion, PDO::PARAM_INT);
    $stmt->bindParam(':total', $totalFactura, PDO::PARAM_STR); // Valor inicial (0.00), se actualizará al final
    $stmt->execute();
    $idFacturaEncabezado = $conexion->lastInsertId(); // Obtener el ID del encabezado recién insertado

    if (!$idFacturaEncabezado) {
        $conexion->rollBack(); // Deshacer la transacción si el encabezado no se pudo crear
        echo json_encode(['status' => 'error', 'message' => 'No se pudo crear el encabezado de la factura.']);
        exit();
    }

    // 2. Insertar Factura_Detalle por cada cargo enviado desde el frontend
    foreach ($cargosData as $cargo) {
        // Validar y obtener datos del cargo individual
        $idCargo = $cargo['ID_Cargo'] ?? null;
        $cantidad = $cargo['Cantidad'] ?? null;
        $precioUnitario = $cargo['Precio_Unitario'] ?? null;
        // $subtotal = $cargo['Subtotal'] ?? null; // No usaremos el subtotal del frontend directamente para el cálculo final

        // Validaciones básicas para cada cargo
        if (empty($idCargo) || !is_numeric($cantidad) || $cantidad <= 0 || !is_numeric($precioUnitario) || $precioUnitario < 0) {
            $conexion->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Datos de cargo individuales inválidos.']);
            exit();
        }

        // Recalcular subtotal en el backend para mayor seguridad
        $subtotalCalculado = round($precioUnitario * $cantidad, 2);
        
        // Sumar al total general de la factura
        $totalFactura += $subtotalCalculado;

        // Insertar detalle en Factura_Detalle
        $stmt = $conexion->prepare("INSERT INTO Factura_Detalle (FK_ID_Factura_Encabezado, FK_ID_Cargo, Cantidad, Precio_Unitario, Subtotal) VALUES (:idFacturaEncabezado, :idCargo, :cantidad, :precioUnitario, :subtotal)");
        $stmt->bindParam(':idFacturaEncabezado', $idFacturaEncabezado, PDO::PARAM_INT);
        $stmt->bindParam(':idCargo', $idCargo, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':precioUnitario', $precioUnitario, PDO::PARAM_STR);
        $stmt->bindParam(':subtotal', $subtotalCalculado, PDO::PARAM_STR);
        $stmt->execute();
    }

    // 3. Actualizar el Total en Factura_Encabezado (ahora que tenemos el total calculado)
    $stmt = $conexion->prepare("UPDATE Factura_Encabezado SET Total = :total WHERE ID_Factura_Encabezado = :idFacturaEncabezado");
    $stmt->bindParam(':total', $totalFactura, PDO::PARAM_STR);
    $stmt->bindParam(':idFacturaEncabezado', $idFacturaEncabezado, PDO::PARAM_INT);
    $stmt->execute();

    $conexion->commit(); // Confirmar la transacción si todo fue exitoso
    echo json_encode(['status' => 'success', 'message' => 'Cargo procesado y factura generada con éxito. Total: Q' . number_format($totalFactura, 2)]);

} catch (PDOException $e) {
    $conexion->rollBack(); // Deshacer la transacción en caso de cualquier error en la base de datos
    error_log("Error al procesar cargo (procesar_cargo.php): " . $e->getMessage()); // Registrar el error
    echo json_encode(['status' => 'error', 'message' => 'Error interno al procesar el cargo. Por favor, inténtelo de nuevo.']);
} catch (Exception $e) {
    $conexion->rollBack(); // También revertir para errores que no sean PDOException
    error_log("Error general al procesar cargo (procesar_cargo.php): " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error inesperado.']);
}
?>