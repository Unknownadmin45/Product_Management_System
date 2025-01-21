<?php
    session_start();
    header('Content-Type: application/json');

    try {
        // Read the existing products
        $jsonFile = 'products.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        
        // Get the updated product data
        $updatedProduct = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!isset($updatedProduct['id']) || !isset($updatedProduct['name']) || 
            !isset($updatedProduct['price']) || !isset($updatedProduct['image_url'])) {
            throw new Exception('Missing required fields');
        }
        
        // Find and update the product
        $found = false;
        foreach ($data['products'] as &$product) {
            if ($product['id'] == $updatedProduct['id']) {
                $product = $updatedProduct;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            throw new Exception('Product not found');
        }
        
        // Save back to file
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        
        echo json_encode([
            'success' => true,
            'message' => 'Product updated successfully'
        ]);
    } catch(Exception $e) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
?>