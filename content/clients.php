<?php
include_once 'functions.php';
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
    <h1>Clients</h1>

    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
            <input type="hidden" name="table" value="Customer_Card">
            <fieldset class="search-fieldset">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <input type="search" name="search" placeholder="Search for clients by their surname"
                    value="<?= $filters['search'] ? $filters['search'] : '' ?>">
            </fieldset>

            <label for="sort" class="sort-filter">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="cust_surname" <?= !isset ($filters['sort']) || $filters['sort'] == "cust_surname" ? "selected" : "" ?>>Surname</option>
                    <option value="card_number" <?= isset ($filters['sort']) && $filters['sort'] == "card_number" ? "selected" : "" ?>>Card number</option>
                    
                </select>
            </label>

            <label for="discount-filter" class="category-filter">
                <span>Discount</span>
                <select name="discount" id="discount-filter">
                    <option value="" <?= !$filters['discount'] ? "selected" : "" ?>>All
                    </option>
                    <?php
                    $stmt = $conn->query("SELECT DISTINCT percent FROM Customer_Card ORDER BY percent");
                    $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($discounts as $d):
                        ?>
                        <option value="<?= $d['percent'] ?>" <?= isset ($filters['discount']) && $filters['discount'] == $d['percent'] ? "selected" : "" ?>>
                            <?= $d['percent'] ?>
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

                    <h3>Add client</h3>
                </summary>

                <form action="actions.php?action=add" method="post" class="add-form">
                    <input type="hidden" name="table" value="Customer_Card">
                    <input type="hidden" name="card_number" value="<?= get_new_card_num()?>">
                    <fieldset>
                    <legend>Full name:</legend>
                    <label for="cust_surname">
                            <span>Surname <span class="red">*</span></span>
                            <input type="text" name="cust_surname" id="cust_surname" placeholder="Enter client's surname" maxlength="50"
                                required>
                        </label>
                        <label for="cust_name">
                            <span>Name <span class="red">*</span></span>
                            <input type="text" name="cust_name" id="cust_name" placeholder="Enter client's name" maxlength="50"
                                required>
                        </label>
                        <label for="cust_patronymic">
                            <span>Patronymic</span>
                            <input type="text" name="cust_patronymic" id="cust_patronymic" placeholder="Enter client's patronymic" maxlength="50">
                        </label>
                    </fieldset>
                    <fieldset>
                    <legend>Address:</legend>
                        <label for="city">
                            <span>City</span>
                            <input type="text" name="city" id="city" placeholder="Enter city" maxlength="50" >
                        </label>
                        <label for="street">
                            <span>Street</span>
                            <input type="text" name="street" id="street" placeholder="Enter street" maxlength="50">
                        </label>
                        <label for="zip_code">
                            <span>Zip-code</span>
                            <input type="text" name="zip_code" id="zip_code" placeholder="Enter zip-code" maxlength="9" >
                        </label>
                    </fieldset>

                    <fieldset>
                        <label for="percent">
                            <span>Discount, % <span class="red">*</span></span>
                            <input type="number" name="percent" id="percent" placeholder="Enter discount (from 1 to 99)" min="1" max="99"
                                required>
                                
                        </label>
                        <label for="phone_number">
                            <span>Phone number <span class="red">*</span></span>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="+380123456789" maxlength="13"
                                required>
                        </label>
                    </fieldset>
                    <span class="notice"><span class="red">*</span> - required fields</span>
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

    $sort = $filters['sort'] ? $filters['sort'] : 'cust_surname';
    $filter_role = $filters['discount'] ? "WHERE percent = " . $filters['discount'] : '';
    $filter_search = $filters['search'] ? ($filter_role ? "AND" : "WHERE") . " cust_surname LIKE '%" . $filters['search'] . "%'" : '';

    $stmt = $conn->prepare("SELECT * FROM Customer_Card $filter_role $filter_search ORDER BY $sort");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($filter_search && count($clients) > 0) {
        
        echo '<div class="banner alert-success">Found ' . count($clients) . ' match' . (count($clients) > 1 ? 'es' : '') . ' for search query "' . $filters['search'] . (!empty($filters['discount']) ? '" with discount ' . $filters['discount'] : '"') . '<button class="bi close">🗙</button></div>';
    }

    if (count($clients) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                <tr>
                        <th rowspan="2">Card number</th>
                        <th colspan="3">Full name</th>
                        
                        <th rowspan="2">Discount, %</th>
                        <th rowspan="2">Phone number</th>
                        <th colspan="3">Address</th>
                        <th rowspan="2"></th>
                        <?php if (has_role('manager')): ?><th rowspan="2"></th><?php endif; ?>
                    </tr>
                    <tr>
                        
                        <th class="shade2">Surname</th>
                        <th class="shade2">Name</th>
                        <th class="shade2">Patronymic</th>
                        
                        
                        <th class="shade5">City</th>
                        <th class="shade5">Street</th>
                        <th class="shade5">Zip-code</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($clients as $client): ?>

                        <tr>
                            <td>
                                <?= $client['card_number'] ?>
                            </td>
                            <td data-key="cust_surname" data-nn="true">
                                <?= $client['cust_surname'] ?>
                            </td>
                            <td data-key="cust_name" data-nn="true">
                                <?= $client['cust_name'] ?>
                            </td>
                            <td data-key="cust_patronymic">
                                <?= $client['cust_patronymic'] ?>
                            </td>

                            <td data-key="percent" data-type='int' data-min="1" data-max="99"  data-nn="true">
                                <?= $client['percent'] ?>
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
                                    <button meta-id="<?= $client['card_number'] ?>" meta-table="Customer_Card" meta-key="card_number"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                            <?php if (has_role('manager')): ?>
                                <td><button meta-id="<?= $client['card_number'] ?>" meta-table="Customer_Card" meta-key="card_number"
                                        class="delete table-btn" title="Delete item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                        </svg>
                                    </button></td>
                            <?php endif; ?>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php endif; ?>

</main>


<?php include 'components/footer.php'; ?>