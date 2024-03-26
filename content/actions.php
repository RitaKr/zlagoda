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

        $keys = array();
        $values = array();
        foreach ($_POST as $key => $val) {
            if ($key != "table") {
                array_push($keys, $key);
                array_push($values, "'".str_replace("'", "&#39;", $val)."'");

            }
        }

        $keysStr = join($keys, ', ');
        $valuesStr = join($values, ', ');
        //var_dump($_POST);
        // echo '<br><br>';
        // var_dump($keysStr);
        // echo '<br>';
        // var_dump($values);

        if ($table_name == "Store_Product") {
            $id_product = $_POST['id_product'];
            $stmt = $conn->query("SELECT * FROM Store_Product WHERE id_product = $id_product AND promotional_product = '0'");
            $new_prom = $stmt->fetch(PDO::FETCH_ASSOC);
            // echo '<br>This product but non-promotional: ';
            //print_r($new_prom);
            if (!$new_prom) {
                //echo '<br>Its a new item in the store, adding it as non-promotional!<br>';

                $stmt = $conn->prepare("INSERT INTO $table_name ($keysStr) VALUES ($valuesStr)");


                if ($stmt->execute()) {
                    $_SESSION['message'] = $table_name . ' was added successfully';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'There was an error inserting the ' . $table_name;
                    $_SESSION['message_type'] = 'danger';
                }
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            } else {
                if ($new_prom["selling_price"] != $_POST["selling_price"]) {
                    //echo '<br>Older one becomes promotional! This is older one with new values:<br>';
                    //first adding products with new price, they will be non-promotional
                    $stmt = $conn->prepare("INSERT INTO $table_name ($keysStr) VALUES ($valuesStr)");
                    if ($stmt->execute()) {
                        $_SESSION['message'] = $table_name . ' was added successfully';
                        $_SESSION['message_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'There was an error inserting the ' . $table_name;
                        $_SESSION['message_type'] = 'danger';
                    }

                    //then editing older product, making it cheaper and promotional, referring to $_POST["UPC"] (new upc) as its UPC_prom
                    $new_prom["promotional_product"] = '1';
                    $new_prom["selling_price"] = doubleval($new_prom["selling_price"]) * 0.8;
                    $new_prom["UPC_prom"] = $_POST["UPC"];

                    foreach ($new_prom as $key => $val) {
                        $_POST[$key] = $val;
                    }
                    //array_push($_POST, array('table'=>'Store_Product', 'id'=>$new_prom['UPC'], 'key'=>'UPC'));

                } else {
                    //echo '<br>Same price - so we just update quantity of older product<br>';

                    foreach ($new_prom as $key => $val) {
                        if ($key == "products_number") {
                            $_POST[$key] = intval($val) + intval($_POST["products_number"]);

                        } else {
                            $_POST[$key] = $val;
                        }
                    }

                    unset($_POST['UPC_prom']);

                }
                //both cases require editing of older table, so we prepare it for edit function 
                unset($_POST['UPC']);
                $_POST['table'] = 'Store_Product';
                $_POST['id'] = $new_prom['UPC'];
                $_POST['key'] = 'UPC';
                //  echo '<br>$_POST after, ready to call edit: ';
                //  print_r($_POST);
                edit_item($conn);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO $table_name ($keysStr) VALUES ($valuesStr)");

            if ($stmt->execute()) {
                $_SESSION['message'] = $table_name . ' was added successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'There was an error inserting the ' . $table_name;
                $_SESSION['message_type'] = 'danger';
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        // try {
        //     $stmt = $conn->prepare("INSERT INTO Product (category_number, product_name,  producer, characteristics) VALUES (?, ?, ?, ?)");
        //     $stmt->execute([$cat, $name, $producer, $char]);
        //     $inserted = $stmt->rowCount() > 0;
        // } catch (PDOException $e) {
        //     $inserted = false;
        // }

        // if ($inserted) {
        //     $_SESSION['message'] = $table_name.' was added successfully';
        //     $_SESSION['message_type'] = 'success';
        // } else {
        //     $_SESSION['message'] = 'There was an error inserting the '.$table_name;
        //     $_SESSION['message_type'] = 'danger';
        // }
        // header('Location: ' . $_SERVER['HTTP_REFERER']);
        // exit;
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
        // unset($_POST['id']);
        // unset($_POST['key']);
        // unset($_POST['table']);

        $set = '';
        foreach ($_POST as $key => $value) {
            if ($key != "id" && $key != "key" && $key != "table")
                $set .= $key . " = '" . $value . "', ";
        }
        $set = rtrim($set, ', ');
        if (isset ($_POST["products_number"]) && $_POST["products_number"] == '0') {
            delete_item($conn);
        } else {
            $stmt = $conn->prepare("UPDATE $table_name SET $set WHERE $key_name = :id");
            $stmt->bindParam(':id', $id);


            if ($stmt->execute()) {
                $_SESSION['message'] = $table_name . ' was updated successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'There was an error updating the ' . $table_name;
                $_SESSION['message_type'] = 'danger';
            }

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