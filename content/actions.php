<?php
/*
    this file is responsible for executing queries (deletions, insertions and additions) to the database. in front-end we refer to this file in action attribute of forms with action defined after the name of file (e.g. actions.php?action=add)
*/
include_once 'functions.php';
include_once 'db-connection.php';

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

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
    case 'get_product_info':
        get_product_info_by_UPC($conn);
        break;
    case 'get_all_products':
        $stmt = $conn->prepare("SELECT UPC, product_name, producer, promotional_product FROM Store_Product LEFT JOIN Product ON Store_Product.id_product = Product.id_product ORDER BY product_name");
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($items);
        break;  
    case 'add-bill':
        
    add_bill($conn);
    break;
    case 'get_discount_percent':
        $stmt = $conn->prepare("SELECT percent FROM Customer_Card WHERE card_number = ?");
        $stmt->execute([$_POST['card_number']]);
        $discount = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($discount) {
            echo json_encode($discount["percent"]);
        } else {
            echo json_encode(["error" => "No results found"]);
        }
        break;
    case 'update_dialog_open' :
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update $_SESSION["detailsOpen"]
        
        $currentPage = $_POST['currentPage'];
        $_SESSION['detailsOpen'][$currentPage] = $_POST['detailsOpen'];
    }
    break;
    case 'update_scroll_position' :
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $currentPage = $_POST['currentPage'];
        $_SESSION['scrollPosition'][$currentPage] = $_POST['scrollPosition'];
        echo $_SESSION['scrollPosition'][$currentPage];
    }
    break;
    case 'signup':
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        $empl_id = $_POST["id"];
    
        register($username, $empl_id, $password);

        exit; 
    }
    break;
    case 'signin':
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //var_dump($_POST);
        $username = $_POST["username"];
        $password = $_POST["password"];
    
        authenticate($username, $password);
    
        exit; 
    }
    break;
    case 'change_password' :
    change_password($conn);
    break;
    default:

        break;
}

function add_bill($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $bill_num = $_POST['bill_number'];
        $id_empl = $_POST['id_employee_bill'];
        $card_number = $_POST['card_number'] ? $_POST['card_number'] : null;
        $total_price = $_POST['sum_total'];
        $vat = $_POST['vat'];
        $date = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO Bill (bill_number, id_employee_bill, card_number, print_date, sum_total, vat) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$bill_num, $id_empl, $card_number, $date, $total_price,  $vat]);
        $inserted = $stmt->rowCount() > 0;

        

        $UPCs = $_POST['UPC'];
        $product_numbers = $_POST['product_number'];
        $selling_prices = $_POST['selling_price'];
        $items_count=0;

        for ($i = 0; $i < count($UPCs); $i++) {
            $stmt = $conn->prepare("INSERT INTO Sale (UPC, product_number, selling_price, bill_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([$UPCs[$i], $product_numbers[$i], $selling_prices[$i], $bill_num]);
            $items_count += $stmt->rowCount();

            // Update products_number in Store_Product
            $stmt = $conn->prepare("UPDATE Store_Product SET products_number = products_number - ? WHERE UPC = ?");
            $stmt->execute([$product_numbers[$i], $UPCs[$i]]);

            //Delete product if products_number is 0
            // $stmt = $conn->prepare("DELETE FROM Store_Product WHERE products_number = 0");
            // $stmt->execute();
        }
        if ($inserted && $items_count==count($UPCs)) {
            $_SESSION['message'] = 'Bill was added successfully';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'There was an error inserting the bill';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
function insert_item($conn)
{
    $table_names =array("Store_Product" => "store product", "Product"=>"product", "Bill"=>"bill", "Employee"=>"employee", "Customer_Card"=>"client", "Category"=>"category", "Sale"=>"sale");

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
                array_push($values, "'" . str_replace("'", "&#39;", $val) . "'");

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
            //var_dump($_POST);
            $stmt = $conn->query("SELECT * FROM Store_Product WHERE id_product = $id_product AND promotional_product = '0'");
            $new_prom = $stmt->fetch(PDO::FETCH_ASSOC);
            // echo '<br>This product but non-promotional: ';
            //print_r($new_prom);
            if (!$new_prom) {
                //echo '<br>Its a new item in the store, adding it as non-promotional!<br>';

                $stmt = $conn->prepare("INSERT INTO $table_name ($keysStr) VALUES ($valuesStr)");


                if ($stmt->execute()) {
                    $_SESSION['message'] = "The ".$table_names[$table_name] . ' was added successfully';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'There was an error inserting the ' . $table_names[$table_name];
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
                        $_SESSION['message'] = 'The '.$table_names[$table_name] . ' was added successfully';
                        $_SESSION['message_type'] = 'success';
                    } else {
                        $_SESSION['message'] = 'There was an error inserting the ' . $table_names[$table_name] ;
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
                $_SESSION['message'] = 'The '. $table_names[$table_name] . ' was added successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'There was an error inserting the ' . $table_names[$table_name];
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
    $table_names =array("Store_Product" => "store product", "Product"=>"product", "Bill"=>"bill", "Employee"=>"employee", "Customer_Card"=>"client", "Category"=>"category", "Sale"=>"sale");

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
                $set .= $key . " = '" . str_replace("'", "&#39;", $value) . "', ";
        }
        
        $set = rtrim($set, ', ');
        //var_dump($set);
        if (isset ($_POST["products_number"]) && $_POST["products_number"] == '0') {
            //delete_item($conn);
        } else {
            $stmt = $conn->prepare("UPDATE $table_name SET $set WHERE $key_name = :id");
            $stmt->bindParam(':id', $id);


            if ($stmt->execute()) {
                $_SESSION['message'] = 'The '. $table_names[$table_name] . ' was updated successfully';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'There was an error updating the ' . $table_names[$table_name];
                $_SESSION['message_type'] = 'danger';
            }

        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;

    }

}
function delete_item($conn)
{
    $table_names =array("Store_Product" => "store product", "Product"=>"product", "Bill"=>"bill", "Employee"=>"employee", "Customer_Card"=>"client", "Category"=>"category", "Sale"=>"sale");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $key_name = $_POST['key'];
        $table_name = $_POST['table'];
        if ($table_name == 'Product' || $table_name == 'Category') {
            $id = intval($id);
        }

        $stmt = $conn->prepare("DELETE FROM $table_name WHERE $key_name = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->rowCount() == 0) {
            $_SESSION['message'] = 'The '. $table_names[$table_name] . ' was not deleted for integrity reasons';
            $_SESSION['message_type'] = 'warning';
        }
            if ($stmt->execute()) {                  
                    $_SESSION['message'] = 'The '. $table_names[$table_name] .' was deleted successfully!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'There was an error while deleting '. $table_names[$table_name];
                    $_SESSION['message_type'] = 'danger';                    
            }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

}

function get_product_info_by_UPC($conn)
{
    $selected_upc = $_POST['UPC'];
    $stmt = $conn->prepare("SELECT products_number, selling_price FROM Store_Product WHERE UPC = ?");
    $stmt->execute([$selected_upc]);

    // Fetch the data
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "No results found"]);
    }
}

function change_password($conn) {
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = hash('sha256', $_POST['old_password']);
    $new_password = hash('sha256', $_POST['new_password']);
    if (!$old_password) {
        $_SESSION['wrong_pass'] = 1;
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare('SELECT pass FROM User WHERE id_employee = :id');
    $stmt->bindParam(':id', $_SESSION['user_id']);

    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the old password is correct
        if ($row['pass'] == $old_password) {
            // Prepare the SQL statement to update the password
            $stmt = $conn->prepare('UPDATE User SET pass = :new_password WHERE id_employee = :id');
            $stmt->bindParam(':id', $_SESSION['user_id']);
            $stmt->bindParam(':new_password', $new_password);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Password changed successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'There was an error changing your password.';
                $_SESSION['message_type'] = 'danger';
            }
        } else {
            $_SESSION['message'] = 'The old password is incorrect.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        
        $_SESSION['message'] = 'There was an error retrieving your account.';
        $_SESSION['message_type'] = 'danger';
        
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

}