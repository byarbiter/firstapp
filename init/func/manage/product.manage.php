<?php
// Ensure the array functions are available
if (!function_exists('array_diff')) {
    require_once 'path/to/array_functions.php';
}

function getProduct()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_product");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function productNameExists($name)
{
    global $db;
    $query = $db->prepare("SELECT id_product FROM tbl_product WHERE name = ?");
    $query->bind_param("s", $name);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return true;
    }
    return false;
}

function productSlugExists($slug)
{
    global $db;
    $query = $db->prepare("SELECT id_product FROM tbl_product WHERE slug = ?");
    $query->bind_param("s", $slug);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows) {
        return true;
    }
    return false;
}

function createProduct($name, $slug, $price, $short_des, $long_des, $image, $id_categories)
{
    global $db;
    $db->begin_transaction();
    try {
        $image_path = UploadProductImage($image);
        $query = $db->prepare("INSERT INTO tbl_product (name,slug,price,qty,short_des,long_des, image) VALUE (?, ?, ?, 0, ?, ?, ?)");
        $query->bind_param("ssdsss", $name, $slug, $price, $short_des, $long_des, $image_path);

        if (!$query->execute()) {
            throw new Exception('Database error: ' . $query->error);
        }

        $id_product = $db->insert_id;
        foreach ($id_categories as $id_category) {
            $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE (?, ?)");
            $query1->bind_param("ii", $id_category, $id_product);
            if (!$query1->execute()) {
                throw new Exception('Database error: ' . $query1->error);
            }
        }

        $db->commit();
        return true;
    } catch (Exception $e) {
        error_log($e->getMessage());
        $db->rollback();
        throw $e; // Rethrow the exception
    }
}

function getProductByID($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_product WHERE id_product = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function deleteProduct($id)
{
    global $db;
    $db->begin_transaction();
    try {
        $product = getProductByID($id);
        $query = $db->prepare("DELETE FROM tbl_product WHERE id_product = ?");
        $query->bind_param("i", $id);
        $query->execute();

        if ($db->affected_rows) {
            unlink($product->image);
            $db->commit();
            return true;
        } else {
            $db->rollback();
            return false;
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $db->rollback();
        return false;
    }
}

function arraysAreDifferent($array1, $array2)
{
    if (count($array1) !== count($array2)) {
        return true;
    }

    sort($array1);
    sort($array2);

    for ($i = 0; $i < count($array1); $i++) {
        if ($array1[$i] !== $array2[$i]) {
            return true;
        }
    }

    return false;
}

function updateProduct($id, $name, $slug, $price, $short_des, $long_des, $id_categories, $image = null)
{
    global $db;
    $db->begin_transaction();
    try {
        $image_path = null;
        
        if ($image && $image['name'] !== '') {
            $image_path = UploadProductImage($image);
            $query = $db->prepare("UPDATE tbl_product SET name = ?, slug = ?, price = ?, 
                                  short_des = ?, long_des = ?, image = ? 
                                  WHERE id_product = ?");
            $query->bind_param("ssdsssi", $name, $slug, $price, $short_des, $long_des, $image_path, $id);
        } else {
            $query = $db->prepare("UPDATE tbl_product SET name = ?, slug = ?, price = ?, 
                                  short_des = ?, long_des = ? 
                                  WHERE id_product = ?");
            $query->bind_param("ssdssi", $name, $slug, $price, $short_des, $long_des, $id);
        }

        $query->execute();
        $productUpdated = ($db->affected_rows > 0);
        
        $existingCategoriesResult = getProductCategories($id);
        $existingCategories = [];
        if ($existingCategoriesResult) {
            while ($row = $existingCategoriesResult->fetch_assoc()) {
                $existingCategories[] = $row['id_category'];
            }
        }
        $categoryChanged = arraysAreDifferent($existingCategories, $id_categories);

        if ($productUpdated || $categoryChanged) {
            $deleteQuery = $db->prepare("DELETE FROM tbl_product_category WHERE id_product = ?");
            $deleteQuery->bind_param("i", $id);
            $deleteQuery->execute();
            
            foreach ($id_categories as $id_category) {
                $insertQuery = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE (?, ?)");
                $insertQuery->bind_param("ii", $id_category, $id);
                $insertQuery->execute();
            }
            $db->commit();
            return true;
        }

        $db->commit();
        return false;
    } catch (Exception $e) {
        error_log($e->getMessage());
        $db->rollback();
        return false;
    }
}

function getProductCategories($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_category INNER JOIN tbl_product_category ON tbl_category.id_category = tbl_product_category.id_category WHERE id_product = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows) {
        return $result;
    }
    return null;
}

function UploadProductImage($image)
{
    $img_name = $image['name'];
    $img_size = $image['size'];
    $tmp_name = $image['tmp_name'];
    $error = $image['error'];

    $dir = './assets/images/';
    $allow_exs = ['jpg', 'jpeg', 'png'];
    if ($error !== 0) {
        throw new Exception('Unknown error occurred');
    }
    if ($img_size > 50000000) {
        throw new Exception('File size is large');
    }
    $image_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);
    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('File extension is not allowed!');
    }
    if (in_array($image_lowercase_ex, $allow_exs)) {
        $new_img_name = uniqid("PI-") . '.' . $image_lowercase_ex;
        $image_path = $dir . $new_img_name;
        move_uploaded_file($tmp_name, $image_path);
        return $image_path;
    }
}