<?php

    /**
     * @OA\Info(title="REST-API", version="0.1")
     */

    // this handel the request and response.
    use Psr\Http\Message\ResponseInterface as Response; 
    use Psr\Http\Message\ServerRequestInterface as Request;

    // This allows to Using Slim and build our application.
    use Slim\Factory\AppFactory;

    // To create a JWT Token for authentication
    use ReallySimpleJWT\Token;

    // all the libraries we need.
    require __DIR__ . "/../vendor/autoload.php";
    // self made functions
    require_once "Controler/validation.php";
    require_once "Model/SQL.php";

    // all response data will be in the Json Fromat
    header("Content-Type: application/json");

    $app = AppFactory::create();

    // products

    /**
    * @OA\Post(
    *   path="/Login",
    *   summary="You can authenticate yourself",
    *   tags={"Login"},
    *   requestBody=@OA\RequestBody(
    *       request="Localhost/Login",
    *       required=true,
    *       description="Password and username",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(property="Password", type="string", example="wu3Ao82832#sd1U+asc9"),
    *               @OA\Property(property="username", type="string", example="Steven")
    *           )
    *       )
    *   ),
    *   @OA\Response(response="201", description="Succesfully authenticated"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->post("/Login", function (Request $request, Response $response, $args) {
        // the root and passwd string
        require_once "Controler/Secret.php";

        // reads the requested JSON body
        $body_content = file_get_contents("php://input");
        $JSON_data = json_decode($body_content, true);

        // if JSON data doesn't have these then there is an error
        if (isset($JSON_data["username"]) && isset($JSON_data["password"])) { } else {
            error_function(400, "Empty request");
        }

        // Prepares the data to prevent bad data, SQL injection andCross site scripting
        $username = validate_string($JSON_data["username"]);
        $password = validate_string($JSON_data["password"]);

        if (!$password) {
            error_function(400, "password is invalid, must contain at least 5 characters");
        }
        if (!$username) {
            error_function(400, "username is invalid, must contain at least 5 characters");
        }

        // validates if the right passwd and username are set.
        if ($username === $_root && $password === $_passwd) {
            //creates the JWT token and puts it in the cookies.
            setcookie("token", Token::create($_root, $_passwd, time() + 3600, "localhost"));
            message_function(200, "Succesfuly authenticated");
        } else {
            error_function(406, "Wrong");
        }

        return $response; 
    });

    // The API part for Products

    /**
    * @OA\Get(
    *   path="/Products",
    *   summary="to list all products",
    *   tags={"Product"},
    *   @OA\Response(response="200", description="data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->get("/Products", function (Request $request, Response $response, $args) {
        validate_token(); // unotherized pepole will get rejected

        echo json_encode(get_all_products());

        return $response;
    });

    /**
    * @OA\Get(
    *   path="/Product/{sku}",
    *   summary="to list all products",
    *   tags={"Product"},
    *   @OA\Parameter(
    *       name="sku",
    *       in="path",
    *       required=true,
    *       description="",
    *       @OA\Schema(
    *           type="string",
    *           example="f8f6fzfuj"
    *       )
    *   ),
    *   @OA\Response(response="200", description="data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->get("/Product/{sku}", function (Request $request, Response $response, $args) {
        validate_token();

        $sku = validate_string($args["sku"]);

        echo json_encode(get_one_product($sku));

        return $response; 
    });

    /**
    * @OA\Post(
    *   path="/Product",
    *   summary="you make a new product",
    *   tags={"Product"},
    *   requestBody=@OA\RequestBody(
    *       request="Product",
    *       required=true,
    *       description="sku, active, id_category, name, image, description, price und stock",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(property="sku", type="string", example="kh3khfvk"),
    *               @OA\Property(property="active", type="boolean", example="true"),
    *               @OA\Property(property="id_category", type="integer", example="1"),
    *               @OA\Property(property="name", type="string", example="Jev"),
    *               @OA\Property(property="image", type="string", example="3wferge"),
    *               @OA\Property(property="description", type="string", example="das ist ein ding"),
    *               @OA\Property(property="price", type="float", example="12.45"),
    *               @OA\Property(property="stock", type="integer", example="234")
    *           )
    *       )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->post("/Product", function (Request $request, Response $response, $args) {
        validate_token();
        // the root and passwd string
        require_once "Controler/Secret.php";

       // reads the requested JSON body
       $body_content = file_get_contents("php://input");
       $JSON_data = json_decode($body_content, true);

       // if the requested JSON data doesn't have these then there is an error
       if (isset($JSON_data["sku"]) && isset($JSON_data["active"]) && isset($JSON_data["id_category"]) && isset($JSON_data["name"]) && isset($JSON_data["image"]) && isset($JSON_data["description"]) && isset($JSON_data["price"]) && isset($JSON_data["stock"])) { } else {
           error_function(400, "Empty request");
       }

       // Prepares the data to prevent bad data, SQL injection andCross site scripting
       $sku = validate_string($JSON_data["sku"]);
       $active = validate_boolean($JSON_data["active"]);
       $id_category = validate_number($JSON_data["id_category"]);
       $name = validate_string($JSON_data["name"]);
       $image = validate_string($JSON_data["image"]);
       $description = validate_string($JSON_data["description"]);
       $price = validate_float($JSON_data["price"]);
       $stock = validate_number($JSON_data["stock"]);

       if (!$sku) {
           error_function(400, "sku is invalid, must contain at least 1 characters");
       }
       if (!(isset($active))) {
           error_function(400, "active is not set");
       }
       if (!$id_category) {
        error_function(400, "id_category is invalid");
        }
        if (!$name) {
            error_function(400, "name is invalid, must contain at least 1 characters");
        }
        if (!$image) {
            error_function(400, "image is invalid, must contain at least 1 characters");
        }
        if (!$description) {
            error_function(400, "description is invalid, must contain at least 1 characters");
        }
        if (!$price) {
            error_function(400, "price is invalid");
        }
        if (!$stock) {
            error_function(400, "stock is invalid");
        }

        add_new_product($sku, $active, $id_category, $name, $image, $description, $price, $stock);

        return $response; 
    });

    /**
    * @OA\Delete(
    *   path="/Product/{sku}",
    *   summary="deletes one product",
    *   tags={"Product"},
    *   @OA\Parameter(
    *       name="sku",
    *       in="path",
    *       required=true,
    *       description="to identify a uneque product",
    *       @OA\Schema(
    *       type="string",
    *       example="er6ztdh"
    *      )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->delete("/Product/{sku}", function (Request $request, Response $response, $args) {
        validate_token();

        $sku = validate_string($args["sku"]);

        delete_one_product($sku);
        return $response; 
    });

    /**
    * @OA\Put(
    *   path="/Product/{sku}",
    *   summary="products values get changed",
    *   tags={"Product"},
    *   @OA\Parameter(
    *       name="sku",
    *       in="path",
    *       required=true,
    *       description="identifies",
    *       @OA\Schema(
    *           type="string",
    *           example="923gw3k3"
    *       )
    *   ),
    *   requestBody=@OA\RequestBody(
    *       request="/Product/{sku}",
    *       required=true,
    *       description="changes product data",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(property="sku", type="string", example="kh3khfvk"),
    *               @OA\Property(property="active", type="boolean", example="true"),
    *               @OA\Property(property="id_category", type="integer", example="1"),
    *               @OA\Property(property="name", type="string", example="Jev"),
    *               @OA\Property(property="image", type="string", example="3wferge"),
    *               @OA\Property(property="description", type="string", example="das ist ein ding"),
    *               @OA\Property(property="price", type="float", example="12.45"),
    *               @OA\Property(property="stock", type="integer", example="234")
    *           )
    *       )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->put("/Product/{sku}", function (Request $request, Response $response, $args) {
        validate_token();

        $sku = validate_string($args["sku"]);

        // reads the requested JSON body
        $body_content = file_get_contents("php://input");
        $JSON_data = json_decode($body_content, true);

        // if JSON data doesn't have these then there is an error
        if (isset($JSON_data["active"]) && isset($JSON_data["id_category"]) && isset($JSON_data["name"]) && isset($JSON_data["image"]) && isset($JSON_data["description"]) && isset($JSON_data["price"]) && isset($JSON_data["stock"])) { } else {
            error_function(400, "Empty request");
        }

        // Prepares the data to prevent bad data, SQL injection andCross site scripting
        $active = validate_boolean($JSON_data["active"]);
        $id_category = validate_number($JSON_data["id_category"]);
        $name = validate_string($JSON_data["name"]);
        $image = validate_string($JSON_data["image"]);
        $description = validate_string($JSON_data["description"]);
        $price = validate_float($JSON_data["price"]);
        $stock = validate_number($JSON_data["stock"]);

        set_product_data($sku, $active, $id_category, $name, $image, $description, $price, $stock);
        return $response; 
    });

    // category

    /**
    * @OA\Get(
    *   path="/Categorys",
    *   summary="get data from category",
    *   tags={"category"},
    *   @OA\Response(response="200", description="data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->get("/Categorys", function (Request $request, Response $response, $args) {
        validate_token();

        echo json_encode(get_all_categorys());

        return $response; 
    });

    /**
    * @OA\Get(
    *   path="/Category/{category_id}",
    *   summary="to list all category",
    *   tags={"category"},
    *   @OA\Parameter(
    *       name="category_id",
    *       in="path",
    *       required=true,
    *       description="idetify what category",
    *       @OA\Schema(
    *           type="integer",
    *           example="Kochschinken"
    *       )
    *   ),
    *   @OA\Response(response="200", description="data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->get("/Category/{category_id}", function (Request $request, Response $response, $args) {
        validate_token();

        $category_id = validate_number($args["category_id"]);

        echo json_encode(get_one_category($category_id));

        return $response; 
    });

    /**
    * @OA\Post(
    *   path="/Category",
    *   summary="new category",
    *   tags={"category"},
    *   requestBody=@OA\RequestBody(
    *       request="Category",
    *       required=true,
    *       description="values for category",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(property="name", type="string", example="Beispiel :-)"),
    *               @OA\Property(property="active", type="boolean", example="true")
    *           )
    *       )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->post("/Category", function (Request $request, Response $response, $args) {
        validate_token();
        // the root and passwd string
        require_once "Controler/Secret.php";

       // reads the requested JSON body
       $body_content = file_get_contents("php://input");
       $JSON_data = json_decode($body_content, true);

       // if JSON data doesn't have these then there is an error
       if (isset($JSON_data["active"]) && isset($JSON_data["name"])) { } else {
           error_function(400, "Empty request");
       }

       // Prepares the data to prevent bad data, SQL injection andCross site scripting
       $name = validate_string($JSON_data["name"]);
       $active = validate_boolean($JSON_data["active"]);

       if (!$name) {
           error_function(400, "name is invalid, must contain at least 1 characters");
       }
       if (!(isset($active))) {
           error_function(400, "active is not set");
       }

       add_new_category($active, $name);

        return $response; 
    });

    /**
    * @OA\Delete(
    *   path="/Category/{category_id}",
    *   summary="deletes one category",
    *   tags={"category"},
    *   @OA\Parameter(
    *       name="sku",
    *       in="path",
    *       required=true,
    *       description="Beschreibung des Parameters",
    *       @OA\Schema(
    *       type="string",
    *       example="5tdgc7"
    *      )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->delete("/Category/{category_id}", function (Request $request, Response $response, $args) {
        validate_token();

        $category_id = validate_number($args["category_id"]);

        delete_one_category($category_id);
        return $response; 
    });

    /**
    * @OA\Put(
    *   path="/Category/{category_id}",
    *   summary="change category data",
    *   tags={"category"},
    *   @OA\Parameter(
    *       name="category_id",
    *       in="path",
    *       required=true,
    *       description="Beschreibung des Parameters",
    *       @OA\Schema(
    *           type="string",
    *           example="65du6rtzjk"
    *       )
    *   ),
    *   requestBody=@OA\RequestBody(
    *       request="/Category/{category_id}",
    *       required=true,
    *       description="to change category data",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(property="name", type="string", example="Beispiel :-)"),
    *               @OA\Property(property="active", type="boolean", example="true")
    *           )
    *       )
    *   ),
    *   @OA\Response(response="201", description="added data"),
    *   @OA\Response(response="401", description="unotharised"),
    *   @OA\Response(response="400", description="Invalid input"),
    *   @OA\Response(response="404", description="Invalid Input"),
    *   @OA\Response(response="500", description="Server Error")
    * )
    */
    $app->put("/Category/{category_id}", function (Request $request, Response $response, $args) {
        validate_token();

        $category_id = validate_number($args["category_id"]);

        // reads the requested JSON body
        $body_content = file_get_contents("php://input");
        $JSON_data = json_decode($body_content, true);

        // if the requested JSON data doesn't have these then there is an error
        if (isset($JSON_data["active"]) && isset($JSON_data["name"])) { } else {
            error_function(400, "Empty request");
        }

        // Prepares the data to prevent bad data, SQL injection and Cross site scripting
        $active = validate_boolean($JSON_data["active"]);
        $name = validate_string($JSON_data["name"]);

        set_category_data($category_id, $active, $name);
        return $response; 
    });

    $app->run();
?>