<?

?>
<?php include 'components/header.php'; ?>
<main>
    <h1>All products</h1>
    <p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>

                <th>Characteristics</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM Product ORDER BY product_name");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //print_r($products);
            
            foreach ($products as $product) :
                $cat_num = $product['category_number'];
                $stmt = $conn->query("SELECT category_name FROM Category WHERE category_number = $cat_num");

                $cat_name = $stmt->fetch(PDO::FETCH_ASSOC)['category_name'];
                ?>
                
                <tr>
                    <td>
                        <?php echo $product['id_product'] ?>
                    </td>
                    <td>
                        <?php echo $cat_name ?>
                    </td>
                    <td>
                        <?php echo $product['product_name'] ?>
                    </td>
                    <td>
                        <?php echo $product['characteristics'] ?>
                    </td>
                    <?php if (has_role('manager') ):?>
                    <td>
                        <button meta-id="Product_<?php echo $product['id_product'] ?>" class="edit">Edit</button>
                    </td>
                    <td><button meta-id="Product_<?php echo $product['id_product'] ?>" class="delete">Delete</button></td>
                    <?php endif;?>
                </tr>

            <?php endforeach;?>
        </tbody>
    </table>

    </p>
</main>

<?php include 'components/footer.php'; ?>