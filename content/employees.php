<?php
include_once 'functions.php';
//unset($_SESSION['filtersData'][$currentPage]);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Storing filters data in the session, so that it wasn't reset after submission of other forms
    $currentPage = basename($_SERVER['SCRIPT_NAME']);
    $_SESSION['filtersData'][$currentPage] = $_POST;
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);
$filters = isset($_SESSION['filtersData'][$currentPage]) ? $_SESSION['filtersData'][$currentPage] : array();
?>
<?php include 'components/header.php'; ?>
<main class="main-wrap">
    <h1>Employees</h1>

    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
            <input type="hidden" name="table" value="Employee">
            <fieldset class="search-fieldset">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <input type="search" name="search" placeholder="Search for employees by their surname"
                    value="<?= $filters['search'] ? $filters['search'] : '' ?>">
            </fieldset>

            <label for="sort" class="sort-filter">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="empl_surname" <?= !isset ($filters['sort']) || $filters['sort'] == "empl_surname" ? "selected" : "" ?>>Surname</option>
                    <option value="id_employee" <?= isset ($filters['sort']) && $filters['sort'] == "id_employee" ? "selected" : "" ?>>ID</option>
                    
                </select>
            </label>

            <label for="role-filter" class="category-filter">
                <span>Role</span>
                <select name="role" id="role-filter">
                    <option value="" <?= !$filters['role'] ? "selected" : "" ?>>All
                    </option>
                    <?php
                    $stmt = $conn->query("SELECT DISTINCT empl_role FROM Employee ORDER BY empl_role");
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($roles as $d):
                        ?>
                        <option value="<?= $d['empl_role'] ?>" <?= isset ($filters['role']) && $filters['role'] == $d['empl_role'] ? "selected" : "" ?>>
                            <?= $d['empl_role'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            
        </form>
    </section>


   
        <section class="control-panel">


            <details class="add-form-container">
                <summary class="add-form-opener">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>

                    <h3>Add employee</h3>
                </summary>

                <form action="actions.php?action=add" method="post" class="add-form">
                    <input type="hidden" name="table" value="Employee">
                    <input type="hidden" name="id_employee" value="<?= get_new_empl_id()?>">
                    <fieldset>
                    <legend>Full name:</legend>
                    <label for="empl_surname">
                            <span>Surname <span class="red">*</span></span>
                            <input type="text" name="empl_surname" id="empl_surname" placeholder="Enter employee's surname" maxlength="50"
                                required>
                        </label>
                        <label for="empl_name">
                            <span>Name <span class="red">*</span></span>
                            <input type="text" name="empl_name" id="empl_name" placeholder="Enter employee's name" maxlength="50"
                                required>
                        </label>
                        <label for="empl_patronymic">
                            <span>Patronymic</span>
                            <input type="text" name="empl_patronymic" id="empl_patronymic" placeholder="Enter employee's patronymic" maxlength="50">
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend>Job info:</legend>
                        <label for="empl_role">
                            <span>Role<span class="red">*</span></span>
                            <select name="empl_role" id="empl_role">
                                <option value="cashier">Cashier</option>
                                <option value="manager">Manager</option>
                            </select>
                        </label>
                        <label for="salary">
                            <span>Salary (UAH)<span class="red">*</span></span>
                            <input type="number" name="salary" id="salary" placeholder="Enter salary in UAH" min="0" step="0.01" required>
                        </label>
                        <label for="date_of_start">
                            <span>Date of start<span class="red">*</span></span>
                            <input type="date" name="date_of_start" id="date_of_start" placeholder="Enter date of start" required>
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend>Personal info:</legend>
                        <label for="phone_number">
                            <span>Phone number <span class="red">*</span></span>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="+380123456789" maxlength="13"
                                required>
                        </label>
                        <label for="date_of_birth">
                            <span>Date of birth<span class="red">*</span></span>
                            <input type="date" name="date_of_birth" id="date_of_birth" placeholder="Enter date of birth" required max="<?= date('Y-m-d', strtotime('-18 years')) ?>" required>
                        </label>
                    </fieldset>
                    <fieldset>
                    <legend>Address:</legend>
                        <label for="city">
                            <span>City<span class="red">*</span></span>
                            <input type="text" name="city" id="city" placeholder="Enter city" maxlength="50" required>
                        </label>
                        <label for="street">
                            <span>Street<span class="red">*</span></span>
                            <input type="text" name="street" id="street" placeholder="Enter street" maxlength="50" required>
                        </label>
                        <label for="zip_code">
                            <span>Zip-code<span class="red">*</span></span>
                            <input type="text" name="zip_code" id="zip_code" placeholder="Enter zip-code" maxlength="9" required>
                        </label>
                    </fieldset>

                    
                    <span class="notice"><span class="red">*</span> - required fields</span>
                    <span class="notice">Notice: employee must reach the age of 18 to be accepted</span>
                    <button type="submit" class="btn-primary" disabled>Add</button>
                </form>
            </details>
            <?php if (has_role("manager")):?>
            <button class="btn-control print">Print a report</button>
            <?php endif;?>
        </section>
   
    <?php
    //message banner that indicates status of operation
    if (isset ($_SESSION['message'])) {
        echo '<div class="banner alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '<button class="bi close">🗙</button></div>';

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }

    $sort = $filters['sort'] ? $filters['sort'] : 'empl_surname';
    $filter_role = $filters['role'] ? "WHERE empl_role = '" . $filters['role']."'" : '';
    $filter_search = $filters['search'] ? ($filter_role ? "AND" : "WHERE") . " empl_surname LIKE '%" . $filters['search'] . "%'" : '';

    //print_r("SELECT * FROM Employee $filter_role $filter_search ORDER BY $sort");
    $stmt = $conn->prepare("SELECT * FROM Employee $filter_role $filter_search ORDER BY $sort");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($filter_search && count($clients) > 0) {
        
        echo '<div class="banner alert-success">Found ' . count($clients) . ' match' . (count($clients) > 1 ? 'es' : '') . ' for search query "' . $filters['search'] . (!empty($filters['role']) ? '" with role ' . $filters['role'] : '"') . '<button class="bi close">🗙</button></div>';
    }
    $roles = array("cashier"=>"Cashier", "manager"=>"Manager");
    if (count($clients) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                <tr>
                        <th rowspan="2">ID</th>
                        <th colspan="3">Full name</th>
                        
                        <th rowspan="2">Role</th>
                        <th rowspan="2">Salary</th>
                        <th rowspan="2">Date of start</th>
                        <th rowspan="2">Date of birth</th>
                        <th rowspan="2">Phone number</th>
                        <th colspan="3">Address</th>
                        <th rowspan="2"></th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        
                        <th class="shade2">Surname</th>
                        <th class="shade2">Name</th>
                        <th class="shade2">Patronymic</th>
                        
                        
                        <th >City</th>
                        <th >Street</th>
                        <th >Zip-code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($clients as $client): ?>

                        <tr>
                            <td>
                                <?= $client['id_employee'] ?>
                            </td>
                            <td data-key="empl_surname" data-nn="true">
                                <?= $client['empl_surname'] ?>
                            </td>
                            <td data-key="empl_name" data-nn="true">
                                <?= $client['empl_name'] ?>
                            </td>
                            <td data-key="empl_patronymic">
                                <?= $client['empl_patronymic'] ?>
                            </td>

                            <td data-key="empl_role" data-val="<?= $client['empl_role'] ?>" data-options='<?=json_encode($roles)?>' data-nn="true">
                                <?= $roles[$client['empl_role']] ?>
                            </td>
                            <td data-key="salary" data-nn="true" data-type="double">
                                <?= round($client['salary'], 2) ?>
                            </td>
                            <td data-key="date_of_start" data-nn="true" data-type="date">
                                <?= $client['date_of_start'] ?>
                            </td>
                            <td data-key="date_of_birth" data-nn="true" data-type="date" data-max="<?= date('Y-m-d', strtotime('-18 years')) ?>">
                                <?= $client['date_of_birth'] ?>
                            </td>
                            <td data-key="phone_number" data-nn="true" data-maxlength="13">
                                <?= $client['phone_number'] ?>
                            </td>
                            <td data-key="city">
                                <?= $client['city'] ?>
                            </td>
                            <td data-key="street">
                                <?= $client['street'] ?>
                            </td>
                            <td data-key="zip_code" data-maxlength="9">
                                <?= $client['zip_code'] ?>
                            </td>
                            
                                <td>
                                    <button meta-id="<?= $client['id_employee'] ?>" meta-table="Employee" meta-key="id_employee"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                            
                                <td><button meta-id="<?= $client['id_employee'] ?>" meta-table="Employee" meta-key="id_employee"
                                        class="delete table-btn" title="Delete item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                        </svg>
                                    </button></td>
                            
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php endif; ?>

</main>


<?php include 'components/footer.php'; ?>