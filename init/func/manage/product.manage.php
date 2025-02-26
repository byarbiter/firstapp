<?php

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
function createProduct($name, $slug, $price, $short_des, $long_des, $id_categories)
{
    //tbl_product -> id_product -> tbl_product_category
    global $db;
    $db->begin_transaction();
    try {
        $query = $db->prepare("INSERT INTO tbl_product (name,slug,price,qty,short_des,long_des) VALUE ('$name', '$slug', '$price',0,'$short_des','$long_des')");
        if ($query->execute()) {
            $id_product = $query->insert_id;
            foreach ($id_categories as $id_category) {
                $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE ('$id_category', '$id_product')");
                $query1->execute();
            }
            $db->commit();
            return true;
        }
        return false;
    } catch (Exception $e) {
        error_log($e->getMessage());
        $db->rollback();
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
        $db->query("DELETE FROM tbl_product_category WHERE id_product = '$id'");

        // Then delete the product
        $db->query("DELETE FROM tbl_product WHERE id_product = '$id'");

        if ($db->affected_rows) {
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
function updateProduct($id, $name, $slug, $price, $short_des, $long_des, $id_categories)
{
    global $db;
    $db->begin_transaction();
    try {
        $query = $db->query("UPDATE tbl_product SET name = '$name', slug = '$slug', price = '$price', short_des = '$short_des', long_des = '$long_des' WHERE id_product = '$id'");

        $productUpdated = $db->affected_rows > 0;
        $existingCategoriesResult = getProductCategories($id);
        $existingCategories = [];
        if ($existingCategoriesResult) {
            while ($row = $existingCategoriesResult->fetch_assoc()) {
                $existingCategories[] = $row['id_category'];
            }
        }
        $categoryChanged = !empty(array_diff($existingCategories, $id_categories)) || !empty(array_diff($id_categories, $existingCategories));

        if ($productUpdated || $categoryChanged) {
            $db->query("DELETE FROM tbl_product_category WHERE id_product = '$id'");
            foreach ($id_categories as $id_category) {
                $query1 = $db->prepare("INSERT INTO tbl_product_category (id_category,id_product) VALUE ('$id_category', '$id')");
                $query1->execute();
            }
            $db->commit();
            return true;
        }
        return false;
    } catch (Exception $e) {
        error_log($e->getMessage());
        $db->rollback();
        return false;
    }
}
function getProductCategories($id_product)
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_product_category WHERE id_product = '$id_product'");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}
