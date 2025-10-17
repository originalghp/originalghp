<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Obtener origen
function getOrigin() {
    global $conn;
    $sql = "SELECT * FROM origin LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Obtener zonas
function getZones() {
    global $conn;
    $sql = "SELECT * FROM zones";
    $result = $conn->query($sql);
    $zones = array();
    while($row = $result->fetch_assoc()) {
        $zones[$row['zone_name']] = $row;
    }
    return $zones;
}

// Obtener destino específico
function getDestination($name) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM destinations WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Obtener todos los destinos
function getAllDestinations() {
    global $conn;
    $sql = "SELECT * FROM destinations ORDER BY region, name";
    $result = $conn->query($sql);
    $destinations = array(
        'caba' => array(),
        'gba_norte' => array()
    );
    
    while($row = $result->fetch_assoc()) {
        $destinations[$row['region']][] = array(
            'name' => $row['name'],
            'distance_km' => $row['distance_km'],
            'zone' => $row['zone_name']
        );
    }
    return $destinations;
}

// Procesar la solicitud
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'origin':
        echo json_encode(getOrigin());
        break;
    case 'zones':
        echo json_encode(getZones());
        break;
    case 'destination':
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        echo json_encode(getDestination($name));
        break;
    case 'all_destinations':
        echo json_encode(getAllDestinations());
        break;
    default:
        echo json_encode(array('error' => 'Acción no válida'));
}

$conn->close();
?>