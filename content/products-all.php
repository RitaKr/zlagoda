<?php
include_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    insert_product();
}
?>
<?php include 'components/header.php'; ?>
<main class="main-wrap">
    <h1>All products</h1>

    <section class="control-forms">

        <form action="<? ROOT_PATH ?>/search.php" method="get" class="search-form">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                </svg>
            </button>
            <input type="search" name="search" placeholder="Search for products">

        </form>
        <form action="" method="get" >
            <label for="sort" class="sort-form">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="product_name" <?= !isset ($_GET['sort']) || $_GET['sort'] == "product_name" ? "selected" : "" ?>>Name</option>
                    <option value="id_product" <?= isset ($_GET['sort']) && $_GET['sort'] == "id_product" ? "selected" : "" ?>>ID</option>
                    <!-- <option value="quantity">Quantity</option> -->
                </select>
            </label>
        
            <label for="cat-filter" class="category-form">
                <span>Category</span>
                <select name="cat" id="cat-filter">
                    <option value="all" <?= !isset ($_GET['cat']) || $_GET['cat'] == "all" ? "selected" : "" ?>>All</option>
                    <?php
                    $stmt = $conn->query("SELECT * FROM Category");
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($categories as $category):
                        ?>
                        <option value="<?php echo $category['category_number'] ?>" <?= isset ($_GET['cat']) && $_GET['cat'] == $category['category_number'] ? "selected" : "" ?>>
                            <?php echo $category['category_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        
            <label for="prom" class="prom-form">
                <span>Promotional</span>
                <select name="prom" id="prom">
                    <option value="all">All</option>
                    <option value="prom">Promotional</option>
                    <option value="prom">Non-promotional</option>
                </select>
            </label>
        </form>
    </section>


    <?php if (has_role('manager')): ?>
        <section class="control-panel">


            <details class="add-form-container">
                <summary class="add-form-opener">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>

                    <h3>Add product</h3>
                </summary>

                <!-- Your form code here -->
                <form action="" method="post" class="add-form">

                    <fieldset>
                        <label for="p_name">
                            <span>Name <strong>*</strong></span>
                            <input type="text" name="p_name" id="p_name" placeholder="Enter product name" maxlength="50"
                                required>
                        </label>
                        <label for="cat">
                            <span>Category</span>
                            <select name="cat" id="cat">
                                <?php
                                $stmt = $conn->query("SELECT * FROM Category");
                                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($categories as $category):
                                    ?>
                                    <option value="<?php echo $category['category_number'] ?>">
                                        <?php echo $category['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label for="producer">
                            <span>Producer <strong>*</strong></span>
                            <input type="text" name="producer" id="producer" placeholder="Enter producer" maxlength="50"
                                required>
                        </label>
                    </fieldset>

                    <label for="char">
                        <span>Characteristics <strong>*</strong></span>
                        <textarea name="char" id="char" placeholder="Enter characteristics" rows="4" maxlength="100"
                            required></textarea>
                    </label>
                    <button type="submit" class="btn-primary" disabled>Add</button>
                </form>
            </details>
            <button class="btn-control">Print a report</button>
        </section>
    <?php endif; ?>
    <?php
    // At the top of products-all.php
    if (isset ($_SESSION['message'])) {
        echo '<div class="banner alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '<button class="bi close">🗙</button></div>';

        // Unset the message
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <?php
    $sort = isset ($_GET['sort']) ? $_GET['sort'] : 'product_name';
    $filter_cat = isset ($_GET['cat']) && $_GET['cat'] != "all" ? "WHERE category_number = " . $_GET['cat'] : '';
    $stmt = $conn->prepare("SELECT * FROM Product $filter_cat ORDER BY $sort");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //
//print_r($products);
    if (count($products) == 0): ?>
        <h3>Nothing is found</h3>
    <?php else: ?>


        <table class="content-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>

                    <th>Category</th>
                    <th>Producer</th>
                    <th>Characteristics</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($products as $product):
                    $cat_num = $product['category_number'];
                    $stmt = $conn->query("SELECT category_name FROM Category WHERE category_number = $cat_num");

                    $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];
                    ?>

                    <tr>
                        <td>
                            <?php echo $product['id_product'] ?>
                        </td>
                        <td>
                            <?php echo $product['product_name'] ?>
                        </td>

                        <td>
                            <?php echo $cat_name ?>
                        </td>
                        <td>
                            <?php echo $product['producer'] ?>
                        </td>
                        <td>
                            <?php echo $product['characteristics'] ?>
                        </td>
                        <?php if (has_role('manager')): ?>
                            <td>
                                <button meta-id="Product_<?php echo $product['id_product'] ?>" class="edit table-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                    </svg>
                                </button>
                            </td>
                            <td><button meta-id="<?= $product['id_product'] ?>" meta-table="Product" meta-key="id_product"
                                    class="delete table-btn">
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
    <?php endif; ?>

</main>

<script>
    $(document).ready(function () {
        $('#sort, #cat-filter').change(function () {
            $(this).closest('form').submit();
        });
    });
</script>
<?php include 'components/footer.php'; ?>