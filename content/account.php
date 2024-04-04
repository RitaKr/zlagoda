<?php include 'components/header.php'; ?>
<?
?>
<main class="main-wrap">

        <h1>MY ACCOUNT</h1>
        <div class="container">
        <?php
        $id = $_SESSION["user_id"];
        $stmt = $conn->prepare("SELECT * FROM Employee WHERE id_employee = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute(); 
        $empl = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div>
            <h3>Full name</h3>
            <p><?php echo get_full_name($empl)?></p>
        </div>

        <div>
            <h3>Role</h3>
            <p><?php echo ucwords($empl["empl_role"])?></p>
        </div>
    
        <div>
            <h3>Address</h3>
            <p><?php echo $empl["city"]?>, <?php echo $empl["street"]?>, <?php echo $empl["zip_code"]?></p>
        </div>
    
        <div>
            <h3>Phone number</h3>
            <p><?php echo $empl["phone_number"]?></p>
        </div>
    
        <div>
            <h3>Date of Birth</h3>
            <p><?php echo $empl["date_of_birth"]?></p>
        </div>
    
        <div>
            <h3>Date of start</h3>
            <p><?php echo $empl["date_of_start"]?></p>
        </div>
    
    </div>
    <form action="signout.php" method="post">
        <button type="submit" name="signout" class="btn-primary">Sign Out</button>
    </form>
</main>

<?php include 'components/footer.php'; ?>