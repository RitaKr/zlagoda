<?

?>
<?php include 'components/header.php'; ?>

    <section class="login-wrap">
        <div class="login-container">
        <form action="<?ROOT_PATH?>/actions.php?action=signin" method="POST" class="login-form <?php echo isset($_SESSION['wrong_pass']) ? 'invalid-pass':''?>" id="signin-form">
        <h1>Sign in</h1>
            
            

            
            <label for="username">
                <span>Username</span>
                <input type="text" name="username" id="username" placeholder="Enter your username">
                <div class="alert username-alert">
                <p></p>
            </div>
            </label>

            <label for="password">
                <span>Password</span>
                <input type="password" name="password" id="password" placeholder="Enter your password">
                <div class="alert pass-alert">
                <p>Incorrect password!</p>
            </div>
                
            </label>
            <button type="submit" id="signin-btn" class="btn-primary" disabled>Sign in</button>

        </form>
        <p class="other-signin">Don't have an account yet? <a href="<?ROOT_PATH?>/signup.php">Sign up</a></p>
        </div>

        
    </section>


<script>
    //console.log("hi")
    const passInput = document.getElementById("password");
    const usernameInput = document.getElementById("username");
    const form = document.getElementById("signin-form");
    const submitBtn = document.getElementById("signin-btn");
    <?php
    $stmt = $conn->query("SELECT username FROM User");
    $all_usernames = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    let allUsernames = <?php echo json_encode($all_usernames); ?>.map(u => u.username);

    function validUsername() {

        console.log(allUsernames);

        if (!usernameInput.value.trim()) {
            document.querySelector('.username-alert p').innerHTML = "Username can't be empty";
            return false
        } else if (!allUsernames.includes(usernameInput.value)) {
            document.querySelector('.username-alert p').innerHTML = "Username not found in system. Haven't signed up yet? <a href='signup.php'>Sign up now!</a>";
            return false
        }
        return true;
    }

   
    function validateUsername() {
        if (validUsername()) {
            form.classList.remove('invalid-username');
        } else {
            form.classList.add('invalid-username');

        }

    }
    usernameInput.addEventListener("input", () => {
        validateUsername();
        validateForm();

    });
    passInput.addEventListener("input", () => {
        form.classList.remove('invalid-pass');
        <?php unset($_SESSION["wrong_pass"])?>
    });
    function validateForm() {
        if (validUsername()) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
          
        }
    }


</script>
</body>
</html>