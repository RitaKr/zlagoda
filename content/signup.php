
<?php include 'components/header.php'; ?>

<main>
    <section class="login-wrapper">
        <h1>Sign up</h1>

        <form action="<?ROOT_PATH?>/signup-handle.php" method="POST" class="login-form " id="signup-form">
            <div class="alert username-alert">
                <p>User with this username already exists!</p>
            </div>
            <div class="alert pass-alert">
                <p>Password is too weak!</p>
            </div>

            <select name="id" id="">
                <optgroup label="Managers">
                    <?php
                    $stmt = $conn->query("SELECT id_employee, empl_surname, empl_name, empl_patronymic, empl_role FROM Employee WHERE empl_role = 'manager' 
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM User 
                        WHERE User.id_employee = Employee.id_employee
                    ) ORDER BY empl_surname");
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    

                    foreach ($employees as $empl):
                        $empl_id = $empl['id_employee'];
                        $empl_name = get_full_name($empl);
                        $empl_role = 'manager';
                        ?>

                        <option value="<?php echo $empl_id ?>">
                            <?php echo $empl_name ?>
                        </option>

                    <?php endforeach; ?>
                </optgroup>

                <optgroup label="Cashiers">
                    <?php
                    $stmt = $conn->query("SELECT id_employee, empl_surname, empl_name, empl_patronymic, empl_role FROM Employee WHERE empl_role = 'cashier' 
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM User 
                        WHERE User.id_employee = Employee.id_employee
                    ) ORDER BY empl_surname");
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    
                    
                    foreach ($employees as $empl):
                        $empl_id = $empl['id_employee'];
                        $empl_name = get_full_name($empl);
                        $empl_role = 'cahier';
                        ?>

                        <option value="<?php echo $empl_id ?>">
                            <?php echo $empl_name ?>
                        </option>

                    <?php endforeach; ?>
                </optgroup>
            </select>
            <label for="username">
                <span>Username</span>
                <input type="text" name="username" id="username" placeholder="Create unique username">
            </label>

            <label for="password">
                <span>Password</span>
                <input type="password" name="password" id="password" placeholder="Create password">
                <p class="notice">Password must be at least 8 characters long and contain at least one letter and one
                    number</p>
            </label>
            <input type="submit" value="Sign up" id="signup-btn" disabled>

        </form>
        <p><a href="<?ROOT_PATH?>/signin.php">Sign in</a></p>
    </section>

</main>
<script>
    const passInput = document.getElementById("password");
    const usernameInput = document.getElementById("username");
    const form = document.getElementById("signup-form");
    const submitBtn = document.getElementById("signup-btn");
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
        } else if (allUsernames.includes(usernameInput.value)) {
            document.querySelector('.username-alert p').innerHTML = 'User with this username already exists!'
            return false
        }
        return true;
    }
    function validPassword() {
        // Check if password length is at least 8 characters
        if (passInput.value.length < 8) {
            return false;
        }

        // Check if password contains at least 1 letter
        if (!/[a-zA-Z]/.test(passInput.value)) {
            return false;
        }

        // Check if password contains at least 1 number
        if (!/\d/.test(passInput.value)) {
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
        validatePassword();
        validateForm();

    });
    function validateForm() {
        if (validPassword() && validUsername()) {
            console.log("all good");
            form.classList.remove('invalid-pass');
            form.classList.remove('invalid-username');
            submitBtn.disabled = false;
            return true;
        } else {
            submitBtn.disabled = true;
            return false;
        }
    }
</script>
<?php include 'components/footer.php'; ?>