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
    <h1>All product categories</h1>

    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
           
        <label for="cat_filter">
                <span>Category filters</span>
                <select name="cat_filter" id="cat_filter">
                    <option value="" <?= !$filters['cat_filter'] ? "selected" : "" ?>>No filters
                    </option>
                    <option value="all_in_store" <?= isset ($filters['cat_filter']) && $filters['cat_filter'] == "all_in_store" ? "selected" : ""?>>
                            Categories, with all of their products in store
                     </option>
                     <option value="all_in_store_promo" <?= isset ($filters['cat_filter']) && $filters['cat_filter'] == "all_in_store_promo" ? "selected" : ""?>>
                            Categories, with all of their store products having non-promotional items
                     </option>
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

                    <h3>Add category</h3>
                </summary>

                <form action="actions.php?action=add" method="post" class="add-form">
                    <input type="hidden" name="table" value="Category">

                    <label for="name">
                        <span>Name <span class="red">*</span></span>
                        <input type="text" name="category_name" id="name" placeholder="Enter category name" maxlength="50"
                            required>
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

    switch ($filters['cat_filter']) {
        case 'all_in_store':
            $stmt = $conn->prepare("SELECT * FROM Category C WHERE NOT EXISTS (SELECT  * FROM Product P WHERE P.category_number = C.category_number  AND P.id_product NOT IN (SELECT SP.id_product FROM Store_Product SP))");
            break;
        case 'all_in_store_promo':
            $stmt = $conn->prepare("SELECT * FROM Category C WHERE NOT EXISTS (SELECT * FROM Product P WHERE P. category_number = C.category_number AND P.id_product IN (SELECT SP.id_product FROM Store_Product SP) AND P.id_product NOT IN (SELECT SP.id_product FROM Store_Product SP WHERE SP.promotional_product = 1))");
            break;
        default:
            $stmt = $conn->prepare("SELECT * FROM Category ORDER BY category_name");
            break;
    }
    //$stmt = $conn->prepare("SELECT * FROM Category ORDER BY category_name");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($items) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>
        <div class="banner alert-info"> Found <?=count($items)?> categories <button class="bi close">🗙</button></div>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <?php if (has_role('manager')): ?>
                        <th class="empty"></th>
                        <th class="empty"></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($items as $product):
                        
                        ?>

                        <tr>
                            <td>
                                <?= $product['category_number'] ?>
                            </td>
                            <td data-key="category_name" data-nn="true">
                                <?= $product['category_name'] ?>
                            </td>

            
                            <?php if (has_role('manager')): ?>
                                <td>
                                    <button meta-id="<?= $product['category_number'] ?>" meta-table="Category" meta-key="category_number"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                                <td><button meta-id="<?= $product['category_number'] ?>" meta-table="Category" meta-key="category_number"
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