<?php
    session_start();

    // Check if products.json exists, if not create it
    if (!file_exists('products.json')) {
        $initial_data = ['products' => []];
        file_put_contents('products.json', json_encode($initial_data, JSON_PRETTY_PRINT));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
     <!-- Add SweetAlert2 CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #B9E5E8;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        
        .button:hover {
            background-color: #45a049;
        }
        
        .form-container {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .products-table th, .products-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .products-table th {
            background-color: #f5f5f5;
        }
        
        .products-table img {
            max-width: 100px;
            height: auto;
        }
        
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .edit-btn {
            background-color: #ffc107;
            color: black;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        
        @media (max-width: 768px) {
            .products-table {
                display: block;
                overflow-x: auto;
            }
            
            .products-table td {
                min-width: 120px;
            }
        }
    </style>
</head>
    <body>
        <h1><center>Product Management System </center></h1>
        &nbsp; &nbsp;
        <center><button class="button" onclick="toggleForm()">Add New Product</button>
        <button class="button" onclick="loadProducts()">Show Products</button></center> 

        <div id="addProductForm" class="form-container" style="display: none;">
            <h2>Add New Product</h2>
            <form id="productForm" onsubmit="event.preventDefault(); handleSubmit();">
                <input type="hidden" id="productId">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="url" id="image_url" required>
                </div>
                <button type="submit" class="button" id="submitBtn">Submit</button>
            </form>
        </div>

        <div id="message-container"></div>
        <div id="products-container" style="display: none;"></div>

            <!-- Add SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
        <script>
            let isEditing = false;

            function toggleForm(show = true) {
                const form = document.getElementById('addProductForm');
                form.style.display = show ? 'block' : 'none';
                if (!show) {
                    resetForm();
                }
            }

            function resetForm() {
                document.getElementById('productForm').reset();
                document.getElementById('productId').value = '';
                document.getElementById('submitBtn').textContent = 'Submit';
                isEditing = false;
            }

            function handleSubmit() {
                if (isEditing) {
                    updateProduct();
                } else {
                    addProduct();
                }
            }

            function addProduct() {
            const name = document.getElementById('name').value;
            const price = document.getElementById('price').value;
            const image_url = document.getElementById('image_url').value;

            if (!name || !price || !image_url) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill all fields'
                });
                return;
            }

            const product = {
                name: name,
                price: parseFloat(price),
                image_url: image_url
            };

            fetch('add_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(product)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Product added successfully!'
                    });
                    toggleForm(false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error adding product'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error adding product'
                });
            });
        }

            function editProduct(product) {
                isEditing = true;
                document.getElementById('productId').value = product.id;
                document.getElementById('name').value = product.name;
                document.getElementById('price').value = product.price;
                document.getElementById('image_url').value = product.image_url;
                document.getElementById('submitBtn').textContent = 'Update Product';
                toggleForm(true);
            }

            function updateProduct() {
                const id = document.getElementById('productId').value;
                const name = document.getElementById('name').value;
                const price = document.getElementById('price').value;
                const image_url = document.getElementById('image_url').value;

                const product = {
                    id: id,
                    name: name,
                    price: parseFloat(price),
                    image_url: image_url
                };

                fetch('update_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(product)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Product updated successfully!'
                        });
                        toggleForm(false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error updating product'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error updating product'
                    });
                });
            }

            function deleteProduct(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('id', id);

                        fetch('delete_product.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Product has been deleted.',
                                    'success'
                                );
                                loadProducts();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Error deleting product'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error deleting product'
                            });
                        });
                    }
                });
            }

            function loadProducts() {
                fetch('get_products.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        displayProducts(data);
                        document.getElementById('products-container').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error loading products'
                        });
                    });
            }

            function displayProducts(products) {
                const container = document.getElementById('products-container');
                
                const table = document.createElement('table');
                table.className = 'products-table';
                
                const thead = document.createElement('thead');
                thead.innerHTML = `
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                `;
                table.appendChild(thead);
                
                const tbody = document.createElement('tbody');
                products.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td>₹${product.price.toFixed(2)}</td>
                        <td><img src="${product.image_url}" alt="${product.name}"></td>
                        <td class="action-buttons">
                            <button class="button edit-btn" onclick='editProduct(${JSON.stringify(product)})'>Edit</button>
                            <button class="button delete-btn" onclick="deleteProduct(${product.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                table.appendChild(tbody);
                
                container.innerHTML = '';
                container.appendChild(table);
            }

            // Load products when page loads
            document.addEventListener('DOMContentLoaded', loadProducts);
        </script>
    </body>
</html>