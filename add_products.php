<?php
    session_start();
    header('Content-Type: application/json');

    try {
        // Read the existing products
        $jsonFile = 'products.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        
        // Get the new product data
        $newProduct = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!isset($newProduct['name']) || !isset($newProduct['price']) || !isset($newProduct['image_url'])) {
            throw new Exception('Missing required fields');
        }
        
        // Generate a new ID
        $maxId = 0;
        foreach ($data['products'] as $product) {
            if (isset($product['id']) && $product['id'] > $maxId) {
                $maxId = $product['id'];
            }
        }
        $newProduct['id'] = $maxId + 1;
        
        // Add the new product
        $data['products'][] = $newProduct;
        
        // Save back to file
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added successfully',
            'id' => $newProduct['id']
        ]);
    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
?>