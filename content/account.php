<?php include 'components/header.php'; ?>
<?
?>
<main class="main-wrap">

    <h1>MY ACCOUNT</h1>
    <section class="two-cols">
        <div class="container info">
            <?php
            $id = $_SESSION["user_id"];
            $stmt = $conn->prepare("SELECT * FROM Employee WHERE id_employee = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $empl = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <div>
                <h3>Full name</h3>
                <p><?php echo get_full_name($empl) ?></p>
            </div>

            <div>
                <h3>Role</h3>
                <p><?php echo ucwords($empl["empl_role"]) ?></p>
            </div>

            <div>
                <h3>Address</h3>
                <p><?php echo $empl["city"] ?>, <?php echo $empl["street"] ?>, <?php echo $empl["zip_code"] ?></p>
            </div>

            <div>
                <h3>Phone number</h3>
                <p><?php echo $empl["phone_number"] ?></p>
            </div>

            <div>
                <h3>Date of Birth</h3>
                <p><?php echo $empl["date_of_birth"] ?></p>
            </div>

            <div>
                <h3>Date of start</h3>
                <p><?php echo $empl["date_of_start"] ?></p>
            </div>

        </div>
        <div class="container change-pass">
            <h3>Change Password</h3>
            <form action="actions.php?action=change_password" method="post" id="change-pass-form"
                class="login-form <?php echo isset($_SESSION['wrong_pass']) ? 'invalid-pass' : '' ?>">
                <label for="old_password">
                    <span>Confirm your current password:</span>
                    <input type="password" id="old_password" name="old_password" required
                        placeholder="Enter your current password">

                </label>
                <label for="new_password">
                    <span>New password:</span>
                    <input type="password" name="new_password" class="password" id="new_password"
                        placeholder="Create new password">
                    <div class="alert pass-alert">
                        <p>Password is too weak!</p>
                    </div>
                    <p class="notice">
                        Password must contain at least:
                        <br>
                        8 characters
                        <br>
                        One latin letter
                        <br>
                        One number
                    </p>
                </label>
                <div>
                    <button type="submit" name="change_password" id="change-password" class="btn-primary"
                        disabled>Change
                        Password</button>
                </div>
            </form>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="banner alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '<button class="bi close">🗙</button></div>';

                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
        </div>
    </section>

    <form action="signout.php" method="post">
        <button type="submit" name="signout" class="btn-primary">Sign Out</button>
    </form>
</main>
<script>
    //console.log("hi")
    const oldPassInput = document.getElementById("old_password");
    const newPassInput = document.getElementById("new_password");
    const form = document.getElementById("change-pass-form");
    const submitBtn = document.getElementById("change-password");



    function validPassword() {
        // Check if password length is at least 8 characters
        if (newPassInput.value.length < 8) {
            return false;
        }

        // Check if password contains at least 1 letter
        if (!/[a-zA-Z]/.test(newPassInput.value)) {
            return false;
        }

        // Check if password contains at least 1 number
        if (!/\d/.test(newPassInput.value)) {
            return false;
        }

        // Password meets all criteria
        return true;
    }
    function validatePassword() {
        if (validPassword()) {
            form.classList.remove('invalid-pass');
        } else {
            form.classList.add('invalid-pass');

        }

    }
    newPassInput.addEventListener("input", () => {
        validatePassword();
        validateForm();

    });
    oldPassInput.addEventListener("input", () => {
        form.classList.remove('invalid-pass');
        <?php unset($_SESSION["wrong_pass"]) ?>
    });

    function validateForm() {
        if (validPassword()) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    submitBtn.addEventListener("click", (e) => {
        e.preventDefault();
        if (confirm("Are you sure you wan't to change your password to "+newPassInput.value+"?")){
            form.submit();
        
        };
    });


</script>
<?php include 'components/footer.php'; ?>