<?php

header("Content-Type: application/json");

$data = file_get_contents("products.json");

$products = json_decode($data, true);

$id = $_GET["id"] ?? "";

$name = $_GET["name"] ?? null;
$price = $_GET["price"] ?? null;
$quantity = $_GET["quantity"] ?? null;
$description = $_GET["description"] ?? null;
if ($price != null && $price <= 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Price must be negative"
    ]);

    exit;
}
if ($id != "" && $name == null && $price == null && $quantity == null && $description == null) {

    foreach ($products as $product) {

        if ($product["id"] == $id) {

            echo json_encode([
                "status" => "success",
                "product" => $product
            ]);

            exit;
        }
    }

    echo json_encode([
        "status" => "error",
        "message" => "Product not found"
    ]);

    exit;
}

$productFound = false;
if ($id != "") {

foreach ($products as &$product) {

    if ($product["id"] == $id) {

        if ($name !== null) {
            $product["name"] = $name;
            if ($description !== null) {
                $product["description"] = $description;
}
        }

        if ($price !== null) {

            if ($price <= 0) {

                echo json_encode([
                    "status" => "error",
                    "message" => "Price must be positive"
                ]);

                exit;
            }

            $product["price"] = $price;
        }

        if ($quantity !== null) {

            if ($quantity < 0) {

                echo json_encode([
                    "status" => "error",
                    "message" => "Quantity must be non-negative"
                ]);

                exit;
            }

            $product["quantity"] = $quantity;
        }

        $productFound = true;

        break;
    }
}

if (!$productFound) {

    echo json_encode([
        "status" => "error",
        "message" => "Product not found"
    ]);

    exit;
}

file_put_contents("products.json", json_encode($products, JSON_PRETTY_PRINT));


echo json_encode([
    "status" => "success",
    "message" => "Product updated",
    "product" => $product
]);

}
else {

    $name = $_GET["name"] ?? "";
    $price = $_GET["price"] ?? "";
    $quantity = $_GET["quantity"] ?? "";
    $description = $_GET["description"] ?? "";

    $newProduct = [
        "id" => count($products) + 1,
        "name" => $name,
        "description" => $description,
        "price" => $price,
        "quantity" => $quantity
    ];

    $products[] = $newProduct;

    file_put_contents("products.json", json_encode($products));
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "product" => $newProduct
    ]);
}

?>