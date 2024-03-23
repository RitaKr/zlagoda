<?php
/*
    this file is responsible for executing queries (deletions, insertions and additions) to the database. in front-end we refer to this file in action attribute of forms with action defined after the name of file (e.g. actions.php?action=add)
*/
include_once 'functions.php';
include_once 'db-connection.php';


$action = !empty ($_REQUEST['action']) ? $_REQUEST['action'] : '';
switch ($action) {
    case 'add':
        insert_item($conn);
        break;
    case 'edit':
        edit_item($conn);
        break;
    case 'delete':
        delete_item($conn);
        break;
    default:
        // include 'list.php';
        break;
}

function insert_item($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // $name = $_POST['p_name'];
        // $cat = $_POST['cat'];
        // $producer = $_POST['producer'];
        // $char = $_POST['char'];
        $table_name = $_POST['table'];
        unset($_POST['table']);

        $keys = array();
        $values = array();
        foreach ($_POST as $key => $val) {
            array_push($keys, $key);
            array_push($values, "'".$val."'");
        }
        $keysStr = join($keys, ', ');
        $valuesStr = join($values, ', ');
        // var_dump($values);
        // var_dump($keysStr);
        // var_dump($valuesStr);
        // try {
        //     $stmt = $conn->prepare("INSERT INTO Product (category_number, product_name,  producer, characteristics) VALUES (?, ?, ?, ?)");
        //     $stmt->execute([$cat, $name, $producer, $char]);
        //     $inserted = $stmt->rowCount() > 0;
        // } catch (PDOException $e) {
        //     $inserted = false;
        // }
        $stmt = $conn->prepare("INSERT INTO $table_name ($keysStr) VALUES ($valuesStr)");
            ;

        if ($stmt->execute()) {
            $_SESSION['message'] = $table_name.' was added successfully';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'There was an error inserting the '.$table_name;
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

function edit_item($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $key_name = $_POST['key'];
        $table_name = $_POST['table'];
        if ($table_name == 'Product' || $table_name == 'Category') {
            $id = intval($id);
        }
        unset($_POST['id']);
        unset($_POST['key']);
        unset($_POST['table']);

        $set = '';
        foreach ($_POST as $key => $value) {
            $set .= $key . " = '" . $value . "', ";
        }
        $set = rtrim($set, ', ');


        $stmt = $conn->prepare("UPDATE $table_name SET $set WHERE $key_name = :id");
        $stmt->bindParam(':id', $id);


        if ($stmt->execute()) {
            $_SESSION['message'] = $table_name.' was updated successfully';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'There was an error updating the '.$table_name;
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

}
function delete_item($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $key_name = $_POST['key'];
        $table_name = $_POST['table'];
        if ($table_name == 'Product' || $table_name == 'Category') {
            $id = intval($id);
        }

        $stmt = $conn->prepare("DELETE FROM $table_name WHERE $key_name = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Deleted successfully!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'There was an error while deletion';
            $_SESSION['message_type'] = 'danger';
        }

    }

}