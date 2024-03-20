<?

?>
<?php include 'components/header.php'; ?>
<main class="main-wrap">
    <h1>All products</h1>
    <section class="control-panel">

        <form action="<? ROOT_PATH ?>/search.php" method="get" class="search-form">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                </svg>
            </button>
            <input type="search" name="search" placeholder="Search for products">

        </form>
        <form action="<? ROOT_PATH ?>/products-all.php" method="get" class="sort-form">
            <label for="sort">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="name">Name</option>
                    <option value="quantity">Quantity</option>
                </select>
            </label>
        </form>
        <form action="<? ROOT_PATH ?>/products-all.php" method="get" class="category-form">
            <label for="cat">
                <span>Category</span>
                <select name="cat" id="cat">
                    <option value="all">All</option>
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
        </form>
        <form action="<? ROOT_PATH ?>/products-all.php" method="get" class="prom-form">
            <label for="prom">
                <span>Promotional</span>
                <select name="prom" id="prom">
                    <option value="all">All</option>
                    <option value="prom">Promotional</option>
                    <option value="prom">Non-promotional</option>
                </select>
            </label>
        </form>
    </section>
    <p>
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
            $stmt = $conn->query("SELECT * FROM Product ORDER BY product_name");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //print_r($products);
            
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
                        <td><button meta-id="Product_<?php echo $product['id_product'] ?>" class="delete table-btn">
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

    </p>
</main>

<?php include 'components/footer.php'; ?>