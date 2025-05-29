<?php
session_start();
include "../../backend/data/db.conexion.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['ID_Reservacion']) || !isset($input['Cargos']) || !is_array($input['Cargos']) || empty($input['Cargos'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos de cargo inválidos o incompletos.']);
    exit();
}

$idReservacion = $input['ID_Reservacion'];
$cargosData = $input['Cargos'];
$totalFactura = 0;

try {
    $conexion->beginTransaction();

    $stmt = $conexion->prepare("INSERT INTO Factura_Encabezado (FK_ID_Reservacion, Fecha_Factura, Total, Estatus) VALUES (:idReservacion, NOW(), :total, 'pendiente')");
    $stmt->bindParam(':idReservacion', $idReservacion, PDO::PARAM_INT);
    $stmt->bindParam(':total', $totalFactura, PDO::PARAM_STR);
    $stmt->execute();
    $idFacturaEncabezado = $conexion->lastInsertId();

    if (!$idFacturaEncabezado) {
        $conexion->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'No se pudo crear el encabezado de la factura.']);
        exit();
    }

    foreach ($cargosData as $cargo) {

        $idCargo = $cargo['ID_Cargo'] ?? null;
        $cantidad = $cargo['Cantidad'] ?? null;
        $precioUnitario = $cargo['Precio_Unitario'] ?? null;

        if (empty($idCargo) || !is_numeric($cantidad) || $cantidad <= 0 || !is_numeric($precioUnitario) || $precioUnitario < 0) {
            $conexion->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Datos de cargo individuales inválidos.']);
            exit();
        }

        $subtotalCalculado = round($precioUnitario * $cantidad, 2);
        
        $totalFactura += $subtotalCalculado;

        $stmt = $conexion->prepare("INSERT INTO Factura_Detalle (FK_ID_Factura_Encabezado, FK_ID_Cargo, Cantidad, Precio_Unitario, Subtotal) VALUES (:idFacturaEncabezado, :idCargo, :cantidad, :precioUnitario, :subtotal)");
        $stmt->bindParam(':idFacturaEncabezado', $idFacturaEncabezado, PDO::PARAM_INT);
        $stmt->bindParam(':idCargo', $idCargo, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':precioUnitario', $precioUnitario, PDO::PARAM_STR);
        $stmt->bindParam(':subtotal', $subtotalCalculado, PDO::PARAM_STR);
        $stmt->execute();
    }

    $stmt = $conexion->prepare("UPDATE Factura_Encabezado SET Total = :total WHERE ID_Factura_Encabezado = :idFacturaEncabezado");
    $stmt->bindParam(':total', $totalFactura, PDO::PARAM_STR);
    $stmt->bindParam(':idFacturaEncabezado', $idFacturaEncabezado, PDO::PARAM_INT);
    $stmt->execute();

    $conexion->commit();
    echo json_encode(['status' => 'success', 'message' => 'Cargo procesado y factura generada con éxito. Total: Q' . number_format($totalFactura, 2)]);

} catch (PDOException $e) {
    $conexion->rollBack();
    error_log("Error al procesar cargo (procesar_cargo.php): " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error interno al procesar el cargo. Por favor, inténtelo de nuevo.']);
} catch (Exception $e) {
    $conexion->rollBack();
    error_log("Error general al procesar cargo (procesar_cargo.php): " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error inesperado.']);
}
?>