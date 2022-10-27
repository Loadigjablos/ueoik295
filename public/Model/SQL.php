<?php
    // Database conection string
    $database = new mysqli("localhost", "root", "", "online_shop_db");

    // product

    /**
     * changes values from a user with a SQL statement.
     * @param sku the primary key to identify what user
     * @param active a value in products
     * @param id_category a value in products
     * @param name a value in products
     * @param image a value in products
     * @param description a value in products
     * @param price a value in products
     * @param stock a value in products
     */
    function set_product_data($sku, $active, $id_category, $name, $image, $description, $price, $stock) {
        global $database;

        // examines if the category is there
        $result = $database->query("SELECT * FROM category WHERE category_id = \"$id_category\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if (!$result->num_rows > 0) {
                error_function(404, "id_category Not Found");
            }
		}

        // examines if the product exists
        $result = $database->query("SELECT * FROM product WHERE sku = \"$sku\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if (!($result->num_rows > 0)) {
                error_function(404, "product Not Found");
            }
		} else {
            error_function(404, "product Not Found");
        }

        $properties = array("active" => $active, "id_category" => $id_category, "name" => $name, "image" => $image, "description" => $description, "price" => $price, "stock" => $stock);

        foreach ($properties as $key => $value) {
            if (!($value === null) || !(empty($value))) {
                $result = $database->query("UPDATE product SET $key = '$value' WHERE sku = '$sku'");
            }
        }
        message_function(201, "Succesfuly updated a product");
    }
    /**
     * makes a sql statment to create a new line with the parameters.
     * @param sku a uneque key to identify what product
     * @param active a value in products
     * @param id_category a value in products
     * @param name a value in products
     * @param image a value in products
     * @param description a value in products
     * @param price a value in products
     * @param stock a value in products
     */
    function add_new_product($sku, $active, $id_category, $name, $image, $description, $price, $stock) {
        global $database;

        $result = $database->query("SELECT * FROM product WHERE sku = \"$sku\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if ($result->num_rows > 0) {
				error_function(400, "Already exists");
			}
		}

        $result = $database->query("SELECT * FROM category WHERE category_id = \"$id_category\"");

        if ($result == false) { } else if ($result !== true) {
			if ($result->num_rows > 0) { } else {
                error_function(400, "category_id does not exist");
            }
		} else {
            error_function(400, "category_id does not exist");
        }

        $result = $database->query("INSERT INTO product (product_id, sku, active, id_category, name, image, description, price, stock) VALUES (NULL, '$sku', '$active', '$id_category', '$name', '$image', '$description', '$price', '$stock')");

        if (!$result) {
            error_function(500, "Wrong SQL Statment");
        } else {
            message_function(201, "Succesfuly created a product");
        }
    }
    /**
     * deletes the user with a SQL statement.
     * @param sku a uneque key to identify what product
     */
    function delete_one_product($sku) {
        global $database;

        // cheks if the product exists
        $result = $database->query("SELECT * FROM product WHERE sku = \"$sku\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
            // gets data from the list
			if (!($result->num_rows > 0)) {
                error_function(404, "product Not Found");
            }
		} else {
            error_function(404, "product Not Found");
        }

        $result = $database->query("DELETE FROM product WHERE sku = '$sku'");

        if ($result === true) {
            message_function(201, "Succesfuly Deleted a user");
        } else {
            error_function(404, "product not found");
        }
    }
    /**
     * returns all products as an array.
     * @return returns all product data in the JSON fromat
     */
    function get_all_products() {
        global $database;

        //Get all user.
		$result = $database->query("SELECT * FROM product");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if ($result->num_rows > 0) {
                $result_array = array();
				while ($user = $result->fetch_assoc()) {
                    $result_array[] = $user;
                }
                return $result_array;
			} else {
                error_function(404, "no products Found");
            }
		} else {
            error_function(404, "no products Found");
        }
    }
    /**
    * returns a product infromation
    * @param sku a uneque key to identify what product
    * @return returns all product data
    */
    function get_one_product($sku) {
        global $database;

        // selects the user by user_id
        $result = $database->query("SELECT * FROM product WHERE sku = \"$sku\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			} else {
                error_function(404, "product Not Found");
            }
		} else {
            error_function(404, "product Not Found");
        }
    }

    // category

    /**
     * changes values from a category with a SQL statement.
     * @param category_id the primary key to identify what category
     * @param name the name of a category
     * @param active a value in category
     */
    function set_category_data($category_id, $active, $name) {
        global $database;

        // cheks if the product exists
        $result = $database->query("SELECT * FROM category WHERE category_id = \"$category_id\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if (!($result->num_rows > 0)) {
                error_function(404, "category Not Found");
            }
		} else {
            error_function(404, "category Not Found");
        }

        $properties = array("active" => $active, "name" => $name);

        foreach ($properties as $key => $value) {
            if (!($value === null) || !(empty($value))) {
                $database->query("UPDATE category SET $key = '$value' WHERE category_id = $category_id");
            }
        }
        message_function(201, "Succesfuly updated a Category");
    }
    /**
     * makes a sql statment to create a new line with the parameters.
     * @param name the new name for category
     * @param active a value in category
     */
    function add_new_category($active, $name) {
        global $database;

        $result = $database->query("INSERT INTO category (category_id, active, name) VALUES (NULL, '$active', '$name')");

        if (!$result) {
            error_function(500, "Wrong SQL Statment");
        } else {
            message_function(201, "Succesfuly created a Category");
        }
    }
    /**
     * deletes the category with a SQL statement.
     * @param category_id the primary key to identify what ca
     */
    function delete_one_category($category_id) {
        global $database;

        // examines if the product exists
        $result = $database->query("SELECT * FROM category WHERE category_id = \"$category_id\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if (!($result->num_rows > 0)) {
                error_function(404, "product Not Found");
            }
		} else {
            error_function(404, "product Not Found");
        }

        $result = $database->query("DELETE FROM category WHERE category_id = '$category_id'");

        if (!$result) {
            error_function(404, "category does not exist");
        } else {
            message_function(201, "Succesfuly Deleted a category");
        }
    }
    /**
     * returns all category as an array list.
     * @return returns all user data in the JSON fromat
     */
    function get_all_categorys() {
        global $database;

        // Get all user.
		$result = $database->query("SELECT * FROM category");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if ($result->num_rows > 0) {
                $result_array = array();
				while ($user = $result->fetch_assoc()) {
                    $result_array[] = $user;
                }
                return $result_array;
			} else {
                error_function(404, "no Category Found");
            }
		} else {
            error_function(404, "no Category Found");
        }
    }
    /**
     * returns a category infromation
     * @param category_id the primary key to identify what category
     * @return returns category data
     */
    function get_one_category($category_id) {
        global $database;

        // selects the category by category_id
        $result = $database->query("SELECT * FROM category WHERE category_id = \"$category_id\"");

        if ($result == false) {
            error_function(500, "Wrong SQL Statment");
		} else if ($result !== true) {
			if ($result->num_rows > 0) {
				return $result->fetch_assoc();
			} else {
                error_function(404, "Category Not Found");
            }
		} else {
            error_function(404, "Category Not Found");
        }
    }
?>