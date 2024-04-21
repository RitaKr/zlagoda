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
    <h1>All products</h1>

    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
            <input type="hidden" name="table" value="Product">
            <fieldset class="search-fieldset">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <input type="search" name="search" placeholder="Search for products by their name"
                    value="<?= $filters['search'] !=="" ? $filters['search'] : '' ?>">
            </fieldset>

            <label for="sort" class="sort-filter">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="product_name" <?= !isset ($filters['sort']) || $filters['sort'] == "product_name" ? "selected" : "" ?>>Name</option>
                    <option value="id_product" <?= isset ($filters['sort']) && $filters['sort'] == "id_product" ? "selected" : "" ?>>ID</option>
                    
                </select>
            </label>

            <label for="cat-filter" class="category-filter">
                <span>Category</span>
                <select name="cat" id="cat-filter">
                    <option value="" <?= !$filters['cat'] ? "selected" : "" ?>>All
                    </option>
                    <?php
                    $stmt = $conn->query("SELECT * FROM Category ORDER BY category_name");
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($products as $product):
                        ?>
                        <option value="<?php echo $product['category_number'] ?>" <?= isset ($filters['cat']) && $filters['cat'] == $product['category_number'] ? "selected" : "" ?>>
                            <?php echo $product['category_name'] ?>
                        </option>
                    <?php endforeach; ?>
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

                    <h3>Add product</h3>
                </summary>

                <form action="actions.php?action=add" method="post" class="add-form">
                    <input type="hidden" name="table" value="Product">
                    <fieldset>
                        <label for="p_name">
                            <span>Name <span class="red">*</span></span>
                            <input type="text" name="product_name" id="p_name" placeholder="Enter product name" maxlength="50"
                                required>
                        </label>
                        <label for="cat">
                            <span>Category <span class="red">*</span></span>
                            <select name="category_number" id="cat">
                                <?php
                                $stmt = $conn->query("SELECT * FROM Category ORDER BY category_name");
                                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($products as $product):
                                    ?>
                                    <option value="<?php echo $product['category_number'] ?>">
                                        <?php echo $product['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label for="producer">
                            <span>Producer <span class="red">*</span></span>
                            <input type="text" name="producer" id="producer" placeholder="Enter producer" maxlength="50"
                                required>
                        </label>
                    </fieldset>

                    <label for="char">
                        <span>Characteristics <span class="red">*</span></span>
                        <textarea name="characteristics" id="char" placeholder="Enter characteristics" rows="4" maxlength="100"
                            required></textarea>
                    </label>
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
    $filter_search = isset($filters['search']) && $filters['search'] !=="" ? ($filter_cashier ? "AND" : "WHERE") . " product_name LIKE '%" . $filters['search'] . "%'" : '';

    $stmt = $conn->prepare("SELECT * FROM Product $filter_cashier $filter_search ORDER BY $sort");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($products) > 0) {
        if ($filters['cat']) {
            $cat_num = $filters['cat'];
            $stmt = $conn->query("SELECT category_name FROM Category WHERE category_number = $cat_num");
            $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];
        }
        echo '<div class="banner alert-info">
        Found ' . count($products) . 
        ($filter_search ? ' match'.(count($products) > 1 ? 'es' : '').' for search query "' . $filters['search'].'"' :' item'.(count($products) > 1 ? 's' : '')). 
        ($cat_name ? ' in category ' . $cat_name : '') . 
        '<button class="bi close">🗙</button></div>';
    }

    if (count($products) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>

                        <th>Category</th>
                        <th>Producer</th>
                        <th>Characteristics</th>
                        <?php if (has_role('manager')): ?>
                        <th class="empty"></th>
                        <th class="empty"></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($products as $empl):
                        $cat_num = $empl['category_number'];
                        $stmt = $conn->query("SELECT category_number, category_name FROM Category WHERE category_number = $cat_num");
                        $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];

                        $stmt = $conn->query("SELECT category_number AS id, category_name AS item_name FROM Category");
                        
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $key => $row) {
                            foreach ($row as $field => $value) {
                                $result[$key][$field] = str_replace("'", "&#39;", $value);
                            }
                        }
                        $cat_array = json_encode($result, JSON_UNESCAPED_UNICODE);
                        //echo $cat_array.'<br>';
                        ?>

                        <tr>
                            <td>
                                <?= $empl['id_product'] ?>
                            </td>
                            <td data-key="product_name" data-nn="true">
                                <?= $empl['product_name'] ?>
                            </td>

                            <td data-key="category_number" data-val="<?= $cat_num ?>" data-fk='<?= $cat_array ?>'>
                                <?= $cat_name; ?>
                            </td>
                            <td data-key="producer" data-nn="true">
                                <?= $empl['producer'] ?>
                            </td>
                            <td data-key="characteristics" data-nn="true" data-maxlength="100">
                                <?= $empl['characteristics'] ?>
                            </td>
                            <?php if (has_role('manager')): ?>
                                <td>
                                    <button meta-id="<?= $empl['id_product'] ?>" meta-table="Product" meta-key="id_product"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                                <td><button meta-id="<?= $empl['id_product'] ?>" meta-table="Product" meta-key="id_product"
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