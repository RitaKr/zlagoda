<?php
include_once 'functions.php';
//unset($_SESSION['filtersData']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Storing filters data in the session, so that it wasn't reset after submission of other forms
    $currentPage = basename($_SERVER['SCRIPT_NAME']);

    $_SESSION['filtersData'][$currentPage] = $_POST;
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);
$filters = isset ($_SESSION['filtersData'][$currentPage]) ? $_SESSION['filtersData'][$currentPage] : array();
?>
<?php include 'components/header.php'; ?>
<main class="main-wrap">
    <h1>Products in store</h1>

    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
            <input type="hidden" name="table" value="Store_Product">
            <fieldset class="search-fieldset">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <input type="search" name="search" placeholder="Search for store products by their UPC or name"
                    value="<?= $filters['search'] ? $filters['search'] : '' ?>">
            </fieldset>

            <label for="sort" class="sort-filter">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="product_name" <?= !isset ($filters['sort']) || $filters['sort'] == "product_name" ? "selected" : "" ?>>Name</option>
                    <option value="products_number" <?= isset ($filters['sort']) && $filters['sort'] == "products_number" ? "selected" : "" ?>>Number</option>
                    <option value="UPC" <?= isset ($filters['sort']) && $filters['sort'] == "UPC" ? "selected" : "" ?>>UPC
                    </option>
                    <!-- <option value="quantity">Quantity</option> -->
                </select>
            </label>

            <label for="cat-filter" class="category-filter">
                <span>Category</span>
                <select name="cat" id="cat-filter">
                    <option value="" <?= !$filters['cat'] ? "selected" : "" ?>>All
                    </option>
                    <?php
                    $stmt = $conn->query("SELECT * FROM Category");
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($products as $product):
                        ?>
                        <option value="<?= $product['category_number'] ?>" <?= isset ($filters['cat']) && $filters['cat'] == $product['category_number'] ? "selected" : ""
                              ?>>
                            <?= $product['category_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label for="promo" class="promo-filter">
                <span>Promotional</span>
                <select name="promo" id="promo">
                    <option value="" <?= !$filters['promo'] ? "selected" : "" ?>>All</option>
                    <option value="1" <?= isset ($filters['promo']) && $filters['promo'] == "1" ? "selected" : "" ?>>
                        Promotional</option>
                    <option value="0" <?= isset ($filters['promo']) && $filters['promo'] == "0" ? "selected" : "" ?>>
                        Non-promotional</option>
                </select>
            </label>
        </form>
    </section>


    <?php if (has_role('manager')): ?>
        <section class="control-panel">


            <details class="add-form-container" <?= $_SESSION['detailsOpen'][$currentPage] ?>>
                <summary class="add-form-opener">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>

                    <h3>Add store product</h3>
                </summary>

                <form action="actions.php?action=add" method="post" class="add-form">
                    <input type="hidden" name="table" value="Store_Product">
                    <input type="hidden" name="UPC" value="<?= get_new_UPC() ?>">
                        <!-- <p>UPC: <?//=get_new_UPC()?> </p> -->
                    <fieldset>
                        <label for="p_id">
                            <span>Product <span class="red">*</span></span>
                            <select name="id_product" id="p_id">
                                <?php
                                $stmt = $conn->query("SELECT product_name, producer FROM Product WHERE Product.id_product NOT IN (SELECT Store_Product.id_product FROM Store_Product WHERE promotional_product = 1) ORDER BY product_name");
                                $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($all_products as $pr):
                                    ?>
                                    <option value="<?= $pr['id_product'] ?>">
                                        <?= $pr['product_name'] . ' by ' . $pr['producer'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>

                        <label for="selling_price">
                            <span>Selling price <span class="red">*</span></span>
                            <input type="number" name="selling_price" id="selling_price" placeholder="Enter selling price"
                                min="0" max="9999999999999" step="0.0001" required>
                        </label>
                        <label for="products_number">
                            <span>Number of products <span class="red">*</span></span>
                            <input type="number" name="products_number" id="products_number"
                                placeholder="Enter number of products" min="1" max="9999999999999" required>
                        </label>
                    </fieldset>
                    
                        <!-- <label for="promotional_product" class="inline">
                            <span>Promotional?</span>
                            <input type="checkbox" name="promotional_product" id="promotional_product">
                        </label>
                        <label for="UPC_prom">
                            <span>Promotional of</span>
                            <select name="UPC_prom" id="UPC_prom">
                                <?php
                                // $stmt = $conn->query("SELECT UPC, product_name, producer FROM Store_Product LEFT JOIN Product ON Store_Product.id_product = Product.id_product  WHERE promotional_product = 0");
                                // $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                // print_r($all_products);
                                // foreach ($all_products as $pr):
                                    ?>
                                    <option value="<?//= $pr['UPC'] ?>">
                                        <?//= $pr['product_name'] . ' by ' . $pr['producer'] ?>
                                    </option>
                                <?php //endforeach; ?>
                            </select>
                        </label> -->
                    
                        <span class="notice"><span class="red">*</span> - required fields</span>
                    <button type="submit" class="btn-primary" disabled>Add</button>
                </form>
            </details>
            <button class="btn-control print">Print a report</button>
        </section>
    <?php endif; ?>
    <?php
    //message banner that indicates status of operation
    if (isset ($_SESSION['message'])) {
        echo '<div class="banner alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '<button class="bi close">🗙</button></div>';

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }

    $sort = $filters['sort'] ? $filters['sort'] : 'product_name';
    $filter_cashier = $filters['cat'] ? "WHERE category_number = " . $filters['cat'] : '';
    $filter_search = $filters['search'] ? ($filter_cashier ? "AND" : "WHERE") . " product_name LIKE '%" . $filters['search'] . "%' OR UPC LIKE '%" . $filters['search'] . "%'" : '';
    $promo_filter = $filters['promo'] == "0" || $filters['promo'] == "1" ? ($filter_search || $filter_cashier ? "AND" : "WHERE") . " promotional_product = " . $filters['promo'] : '';

    $stmt = $conn->prepare("SELECT * FROM Store_Product LEFT JOIN Product ON Store_Product.id_product = Product.id_product $filter_cashier $filter_search $promo_filter ORDER BY $sort");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($filter_search && count($employees) > 0) {
        if ($filters['cat']) {
            $cat_num = $filters['cat'];
            $stmt = $conn->query("SELECT category_name FROM Category WHERE category_number = $cat_num");
            $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];
        }
        echo '<div class="banner alert-success">Found ' . count($employees) . ' match' . (count($employees) > 1 ? 'es' : '') . ' for search query "' . $filters['search'] . ($cat_name ? '" in category ' . $cat_name : '"') . '<button class="bi close">🗙</button></div>';
    }

    if (count($employees) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>UPC</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Producer</th>
                        <th>Characteristics</th>
                        <th>Selling price, UAH</th>
                        <th>Number of items</th>
                        <th>Promotional</th>
                        <?php if (has_role('manager')): ?>
                        <th class="empty"></th>
                        <th class="empty"></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($employees as $empl):
                        $p_id = $empl['id_product'];

                        $stmt = $conn->query("SELECT id_product AS id, product_name AS item_name, (SELECT category_name FROM Category WHERE Category.category_number = Product.category_number) AS category_name, producer, characteristics FROM Product");
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $key => $row) {
                            foreach ($row as $field => $value) {
                                $result[$key][$field] = str_replace("'", "&#39;", $value);
                            }
                        }
                        $p_array = json_encode($result, JSON_UNESCAPED_UNICODE);

                        // echo '<code>';
                        // var_dump($p_array);
                        // echo '</code><br>';
                

                        $cat_num = $empl['category_number'];
                        $stmt = $conn->query("SELECT category_number, category_name FROM Category WHERE category_number = $cat_num");
                        $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];

                        $boolValues = array('0' => 'No', '1' => 'Yes');
                        //var_dump($boolValues);
                        $stmt = $conn->query("SELECT id_product AS id, product_name AS item_name FROM Product");

                        $promo_array = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

                        ?>

                        <tr>
                            <td>
                                <?= $empl['UPC'] ?>
                            </td>
                            <!-- <td data-key="id_product" data-val="<?//= $p_id ?>" data-fk='<?//= $p_array ?>'> -->
                            <td>
                                <?= $empl['product_name']; ?>
                            </td>

                            <td data-info>
                                <?= $cat_name; ?>
                            </td>
                            <td data-info>
                                <?= $empl['producer'] ?>
                            </td>
                            <td data-info>
                                <?= $empl['characteristics']; ?>
                            </td>
                            <td data-key="selling_price" data-nn="true" data-type="double">
                            <span class="decimal"><?= $empl['selling_price']?></span>
                            </td>
                            <td data-key="products_number" data-nn="true" data-type="int">
                                <?= $empl['products_number'] ?>
                            </td>
                            <!-- <td data-key="promotional_product" data-nn="true" data-options='<?//= json_encode($boolValues)  ?>'>
                                <?//=$boolValues[$product['promotional_product']]  ?>
                            </td> -->

                            <td>
                                <?= $boolValues[$empl['promotional_product']] ?>
                            </td>

                            <?php if (has_role('manager')): ?>
                                <td>
                                    <button meta-id="<?= $empl['UPC'] ?>" meta-table="Store_Product" meta-key="UPC"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                                <td><button meta-id="<?= $empl['UPC'] ?>" meta-table="Store_Product" meta-key="UPC"
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