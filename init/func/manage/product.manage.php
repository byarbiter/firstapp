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
    $query = $db->query("SELECT id_product FROM tbl_product WHERE name = '$name'");

    if ($query->num_rows) {
        return true;
    }
    return false;
}

function productSlugExists($slug)
{
    global $db;
    $query = $db->query("SELECT id_product FROM tbl_product WHERE slug = '$slug'");

    if ($query->num_rows) {
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
            $query = $db->prepare("INSERT INTO tbl_product (name,slug,price,qty,short_des,long_des, image) VALUE ('$name', '$slug', '$price',0,'$short_des','$long_des', '$image_path')");

            if ($query->execute()) {
                $id_product = $query->insert_id;
                foreach ($id_categories as $id_category) {
                    $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE ('$id_category', '$id_product')");
                    $query1->execute();
                }
                $db->commit();
                return true;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $db->rollback();
            return false;
        }
    }

function getProductByID($id)
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_product WHERE id_product = '$id'");
    if ($query->num_rows) {
        return $query->fetch_object();
    }
    return null;
}
function deleteProduct($id)
{
    global $db;
    $db->begin_transaction();
    try {
        // First delete related records in the junction table
        // $db->query("DELETE FROM tbl_product_category WHERE id_product = '$id'");

        // Then delete the product
        $product = getProductByID($id);
        $db->query("DELETE FROM tbl_product WHERE id_product = '$id'");

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
    // If array sizes are different, they must be different
    if (count($array1) !== count($array2)) {
        return true;
    }

    // Sort both arrays for consistent comparison
    sort($array1);
    sort($array2);

    // Compare each element
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
        
        
        // Process image if provided
        if ($image && $image['name'] !== '') {
            $image_path = UploadProductImage($image);
            
            // Update query with image
            $query = $db->query("UPDATE tbl_product SET name = '$name', slug = '$slug', price = '$price', 
                                short_des = '$short_des', long_des = '$long_des', image = '$image_path' 
                                WHERE id_product = '$id'");
        } else {
            // Update query without image
            $query = $db->query("UPDATE tbl_product SET name = '$name', slug = '$slug', price = '$price', 
                                short_des = '$short_des', long_des = '$long_des' 
                                WHERE id_product = '$id'");
        }

        // $productUpdated = $db->affected_rows > 0;
        $productUpdated = ($query !== false);
        $existingCategoriesResult = getProductCategories($id);
        $existingCategories = [];
        if ($existingCategoriesResult) {
            while ($row = $existingCategoriesResult->fetch_assoc()) {
                $existingCategories[] = $row['id_category'];
            }
        }
        $categoryChanged = arraysAreDifferent($existingCategories, $id_categories);

        if ($productUpdated || $categoryChanged) {
            $db->query("DELETE FROM tbl_product_category WHERE id_product = '$id'");
            foreach ($id_categories as $id_category) {
                $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE ('$id_category', '$id')");
                $query1->execute();
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
// function getProductCategories($id_product)
// {
//     global $db;
//     $query = $db->query("SELECT * FROM tbl_product_category WHERE id_product = '$id_product'");
//     if ($query->num_rows) {
//         while($row = $query-> fetch_object()){
//             $categories[] = $row -> id_category;
//         }
//         return $categories;
//     }
//     return [];
// }
function getProductCategories($id)
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_category INNER JOIN tbl_product_category ON tbl_category.id_category = tbl_product_category.id_category WHERE id_product = '$id'");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}
function UploadProductImage($image){
    //tbl_product -> id_product -> tbl_product_category
    $img_name = $image['name'];
    $img_size = $image['size'];
    $tmp_name = $image['tmp_name'];
    $error = $image['error'];

    $dir = './assets/images/';
    $allow_exs = ['jpg', 'jpeg', 'png'];
    if ($error !== 0) {
        throw new Exception('Unknown error occurred');
        // return 'File upload error: ' . $image['error'];
    }
    if ($img_size > 50000000) {
        throw new Exception('File size is large');
        // return 'File size is large';
    }
    $image_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);
    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('File extension  is not allowed!');
        // return 'File extension  is not allowed!';
    }
    if (in_array($image_lowercase_ex, $allow_exs)) {
        $new_img_name = uniqid("PI-") . '.' . $image_lowercase_ex;
        $image_path = $dir . $new_img_name;
        move_uploaded_file($tmp_name, $image_path);
        return $image_path;
    }
}