<?php 
if (!headers_sent()) {
    session_start();
}

define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);



// Check if the user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Check if the user has a specific role
function has_role($role)
{
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Authenticate user
function authenticate($un, $pass)
{
    include ROOT_PATH . '/db-connection.php';

    $hashedPassword = hash('sha256', $pass);
    
    $stmt = $conn->prepare("SELECT id_employee, pass FROM User WHERE username = :un");
    $stmt->bindParam(':un', $un);
    $stmt->execute(); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if ($user && $user["pass"] == $hashedPassword) {
        $id = $user["id_employee"];
        $stmt = $conn->prepare("SELECT empl_role FROM Employee WHERE id_employee = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        $empl_role = $stmt->fetch(PDO::FETCH_ASSOC)["empl_role"];

        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $empl_role;
        $_SESSION['user_login'] = $un;
        unset($_SESSION['wrong_pass']);
        
        header("Location: /index.php");
    } else {
        
        $_SESSION['wrong_pass'] = 1;
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}

function signout()
{
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_login']);
    unset($_SESSION['role']);
}

// Register user
function register($un, $id, $password)
{
    include ROOT_PATH . '/db-connection.php';
    $hashedPassword = hash('sha256', $password);


    $sql = "INSERT INTO User (username, id_employee, pass) VALUES (:username, :id_employee, :pass)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $un);
    $stmt->bindParam(':id_employee', $id);
    $stmt->bindParam(':pass', $hashedPassword);

    try {
        // Execute the query
        $stmt->execute();
        $stmt = $conn->query("SELECT empl_role FROM Employee WHERE id_employee = $id");
        $empl_role = $stmt->fetch(PDO::FETCH_ASSOC)["empl_role"];

        // Check if the query was successful
        if ($stmt->rowCount() > 0) {


            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $empl_role;
            $_SESSION['user_login'] = $un;

        } else {
            // Query did not affect any rows (no records inserted, updated, or deleted)
            echo "<div class='banner alert-warning'>Query executed successfully, but no rows were affected</div>";
        }
        header("Location: /index.php");
    } catch (PDOException $e) {
        // Query execution failed
        echo "<div class='banner alert-danger'>Error executing query: " . $e->getMessage() . "</div>";
    }
}

// Returns string with employees full name
function get_full_name($employee)
{
    return $employee['empl_surname'] . ' ' . $employee['empl_name'] . ($employee['empl_patronymic'] != null ? (' ' . $employee['empl_patronymic']) : '');
}

function home_url($page) {
    return ROOT_PATH .'/'. $page;
}

function get_new_UPC(){
    include ROOT_PATH . '/db-connection.php';
    $stmt = $conn->query("SELECT MAX(CAST(UPC AS UNSIGNED)) as max FROM Store_Product");
    $max = intval($stmt->fetch(PDO::FETCH_ASSOC)["max"])+1;

    return str_pad($max, 12, '0', STR_PAD_LEFT);
}

function get_new_card_num(){
    include ROOT_PATH . '/db-connection.php';
    $stmt = $conn->query("SELECT MAX(CAST(card_number AS UNSIGNED)) as max FROM Customer_Card");
    $max = intval($stmt->fetch(PDO::FETCH_ASSOC)["max"])+1;

    return str_pad($max, 13, '0', STR_PAD_LEFT);
    
}

function get_new_empl_id(){
    include ROOT_PATH . '/db-connection.php';
    $stmt = $conn->query("SELECT MAX(CAST(id_employee AS UNSIGNED)) as max FROM Employee");
    $max = intval($stmt->fetch(PDO::FETCH_ASSOC)["max"])+1;

    return str_pad($max, 10, '0', STR_PAD_LEFT);
    
}
function get_new_bill_id(){
    include ROOT_PATH . '/db-connection.php';
    $stmt = $conn->query("SELECT MAX(CAST(bill_number AS UNSIGNED)) as max FROM Bill");
    $max = intval($stmt->fetch(PDO::FETCH_ASSOC)["max"])+1;

    return str_pad($max, 10, '0', STR_PAD_LEFT);
    
}

function create_table($table_name) {
    include ROOT_PATH . '/db-connection.php';
    $stmt = $conn->prepare("SHOW TABLES LIKE :table_name");
    $stmt->bindParam(':table_name', $table_name);
    $stmt->execute();
    if (empty($stmt->fetchAll())) {
        if ($table_name == 'User') {
            signout();
        }
        $sql = file_get_contents(ROOT_PATH . '/empty-tables/' . $table_name . '.sql');
        $conn->exec($sql);
    }
}

function create_all_tables() {
    include ROOT_PATH . '/db-connection.php';
    
    create_table('Employee');
    create_table('User');
    create_table('Category');
    create_table('Product');
    create_table('Store_Product');
    create_table('Customer_Card');
    create_table('Bill');
    create_table('Sale');
    
}

