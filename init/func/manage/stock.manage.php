<?php

function getStocks()
{
    global $db;
    $query = $db->query("SELECT * FROM tbl_stock");
    if ($query->num_rows) {
        return $query;
    }
    return null;
}

function createStock($id_product, $qty, $date)
{
    global $db;
    $query = $db->prepare("INSERT INTO tbl_stock (id_product, qty, date) VALUES (?, ?, ?)");
    $query->bind_param("iis", $id_product, $qty, $date);
    if ($query->execute()) {
        return true;
    }
    return false;
}

function getStockByID($id)
{
    global $db;
    $query = $db->prepare("SELECT * FROM tbl_stock WHERE id_stock = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows) {
        return $result->fetch_object();
    }
    return null;
}

function updateStock($id, $id_product, $qty, $date)
{
    global $db;
    $query = $db->prepare("UPDATE tbl_stock SET id_product = ?, qty = ?, date = ? WHERE id_stock = ?");
    $query->bind_param("iisi", $id_product, $qty, $date, $id);
    
    if ($query->execute()) {
        return getStockByID($id);
    }
    return false;
}

function deleteStock($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM tbl_stock WHERE id_stock = ?");
    $query->bind_param("i", $id);
    $query->execute();
    
    if ($db->affected_rows) {
        return true;
    }
    return false;
}