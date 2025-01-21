<?php
    session_start();
    header('Content-Type: application/json');

    try {
        $jsonFile = 'products.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        
        echo json_encode($data['products']);
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
?>