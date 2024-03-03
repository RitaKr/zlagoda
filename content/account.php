<?

?>
<?php include 'components/header.php'; ?>
<main>
    <h1>My account</h1>
    <p>Logged in as:
        <?php echo $_SESSION["user_login"] ?> (<?php echo $_SESSION["role"] ?>)
    </p>
    <h2>My information</h2>
    <?php
    $id = $_SESSION["user_id"];
    $stmt = $conn->query("SELECT * FROM Employee WHERE id_employee = $id");
    $empl = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <h3>Full name</h3>
    <p><?php echo get_full_name($empl)?></p>

    <h3>Role</h3>
    <p><?php echo $empl["empl_role"]?></p>

    <h3>Phone number</h3>
    <p><?php echo $empl["phone_number"]?></p>

    <h3>Date of Birth</h3>
    <p><?php echo $empl["date_of_birth"]?></p>

    <h3>Date of start</h3>
    <p><?php echo $empl["date_of_start"]?></p>

    <h3>Address</h3>
    <p><?php echo $empl["city"]?>, <?php echo $empl["street"]?>, <?php echo $empl["zip_code"]?></p>

    <form action="signout.php" method="post">
        <button type="submit" name="signout">Sign Out</button>
    </form>
</main>

<?php include 'components/footer.php'; ?>