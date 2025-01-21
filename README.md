# Product_Management_System

#### *Project Description*  
The *Product Management System* is an efficient web application for managing product data using a JSON file instead of a traditional database. By combining PHP for backend operations and JavaScript for frontend interactions, the system ensures fast and reliable performance.

---

#### *Features*  

*JSON Data Communication*  
- Manage product data using JSON encoding/decoding.  
- API endpoints for JSON-based operations.  

*Backend API Endpoints*  
- add_products.php: Add product data via JSON.  
- get_products.php: Fetch all product data in JSON format.  
- update_product.php: Update product details with JSON.  
- delete_product.php: Delete product entries and return confirmation.  

*Frontend Features*  
- *Dynamic Product Form*: Add new products dynamically.  
- *Product Display Table*: View products in a table format.  
- *Edit/Delete Functionality*: Modify or remove product entries.  
- *Enhanced Notifications*: User-friendly alerts using SweetAlert2.  

---

#### *Technologies Used*  
- *Frontend*: HTML5, CSS3, Bootstrap 
- *Backend*: PHP, JavaScript   
- *Data Storage*: JSON file (products.json)  
- *Libraries*: SweetAlert2  

---

#### *Installation Instructions*  
1. Clone the repository:  
   bash
   git clone <repository-url>
     
2. Set up a local server (e.g., XAMPP).  
3. Create an empty products.json file in the project directory:  
   json
   []
     
4. Place the project folder in the server's root directory (e.g., htdocs for XAMPP).  
5. Open the application in your browser: http://localhost/<project-folder>.  

---

#### *Usage Instructions*  
1. Add products using the dynamic form.  
2. View all products in a dynamic table populated from JSON.  
3. Edit or delete products using the respective options.  
4. Notifications will confirm each action via SweetAlert2.  

---

#### *Future Enhancements*  
- Add user authentication for secure product management.  
- Implement search and filter features.  
- Optimize API performance for larger datasets.  
- Enhance the UI for an improved user experience.  

---

#### Credits
- Developers:
  - Harsh Ramdhani Mishra  
  - Ketan Rajendra Mande  
- *Frameworks and Libraries*: Bootstrap, SweetAlert2  

---

#### *License*  
This project is licensed under the MIT License.
