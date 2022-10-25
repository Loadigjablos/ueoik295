<?php
    // Database conection string
    $database = new mysqli("localhost", "root", "", "online_shop_db");

    /**
     * changes values from a user with a SQL statement.
     * @param $user_id the primary key to identify what user
     * @param $name the new name of a new user
     * @param $email the new email of a new user
     * @return procces information
     */
    function set_user_data($user_id, $name, $email) {
        global $database;

        $result = $database->query("UPDATE users_2 SET name = '$name', email = '$email' WHERE user_id = $user_id");

        if (!$result) {
            http_response_code(500); // server error
            return "{ \"error\": \"Wrong SQL Statment\" }";
        } else if ($result === true) {
            error_function(404, "user Not Found");
        } else {
            http_response_code(201); // OK
            return "{ \"infromation\": \"Succesfuly updated a user\" }";
        }
    }
    /**
     * makes a sql statment to create a new line with the parameters.
     * @param $name the name of a new user
     * @param $email the email of a new user
     * @return procces information
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

        $result = $database->query("INSERT INTO product (product_id, sku, active, id_category, name, image, description, price, stock) VALUES (NULL, '$sku', '$active', '$id_category', '$name', '$image', '$description', '$price', '$stock')");

        if (!$result) {
            error_function(500, "Wrong SQL Statment");
        } else {
            message_function(201, "Succesfuly created a product");
        }
    }
    /**
     * deletes the user with a SQL statement.
     * @param $user_id the primary key to identify what user
     * @return procces information
     */
    function delete_user($user_id) {
        global $database;

        $result = $database->query("DELETE FROM users_2 WHERE user_id = '$user_id'");

        if (!$result) {
            http_response_code(500); // server error
            return "{ \"error\": \"Wrong SQL Statment\" }";
        } else if ($result === true) {
            error_function(404, "user Not Found");
        } else {
            http_response_code(201); // OK
            return "{ \"infromation\": \"Succesfuly Deleted a user\" }";
        }
    }
    /**
     * returns all products as an array list.
     * @return returns all user data in the JSON fromat
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
     * returns a users infromation
     * @param $user_id the primary key to identify what user
     * @return returns user data in the JSON fromat
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
                error_function(404, "user Not Found");
            }
		} else {
            error_function(404, "user Not Found");
        }
    }
?>