<?php
    session_start();
    header('Content-Type: application/json');

    try {
        // Read the existing products
        $jsonFile = 'products.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        
        $id = $_POST['id'] ?? null;
        if (!$id) {
            throw new Exception('Product ID is required');
        }
        
        // Find and remove the product
        $found = false;
        foreach ($data['products'] as $key => $product) {
            if ($product['id'] == $id) {
                unset($data['products'][$key]);
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            throw new Exception('Product not found');
        }
        
        // Reindex the array
        $data['products'] = array_values($data['products']);
        
        // Save back to file
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        
        echo json_encode([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
?>
