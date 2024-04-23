<?php
include_once 'functions.php';
//unset($_SESSION['filtersData']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //storing filters data in the session, so that it wasn't reset after submission of other forms
    $currentPage = basename($_SERVER['SCRIPT_NAME']);

    if ($_REQUEST["form"] == "totals") {
        $_SESSION['filtersData'][$currentPage]["id_product"] = $_POST["id_product"];
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_POST["id_product"] = $_SESSION['filtersData'][$currentPage]["id_product"];
        $_SESSION['filtersData'][$currentPage] = $_POST;
    }
}

?>
<?php include 'components/header.php'; ?>

<main class="main-wrap">
    <h1>Bills</h1>


    <section class="control-panel">

        <form action="" method="post" class="filters-form ">
            <input type="hidden" name="table" value="Bill">
            <fieldset class="search-fieldset">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-search"
                        viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
                <input type="search" name="search" placeholder="Search for bill by its number"
                    value="<?= $filters['search'] !== "" ? $filters['search'] : '' ?>">
            </fieldset>

            <label for="sort" class="sort-filter">
                <span>Sort by</span>
                <select name="sort" id="sort">
                    <option value="print_date" <?= !isset($filters['sort']) || $filters['sort'] == "print_date" ? "selected" : "" ?>>Print date</option>
                    <option value="sum_total" <?= isset($filters['sort']) && $filters['sort'] == "sum_total" ? "selected" : "" ?>>Total sum</option>
                    <option value="bill_number" <?= isset($filters['sort']) && $filters['sort'] == "bill_number" ? "selected" : "" ?>>ID
                    </option>
                    <!-- <option value="quantity">Quantity</option> -->
                </select>
            </label>
            <?php if (has_role("manager")): ?>
                <label for="cashier-filter" class="category-filter">
                    <span>Cashier</span>
                    <select name="cashier" id="cashier-filter">
                        <option value="" <?= !$filters['cashier'] ? "selected" : "" ?>>All
                        </option>
                        <?php
                        $stmt = $conn->query("SELECT * FROM Employee WHERE empl_role = 'cashier' ORDER BY empl_surname");
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($products as $product):
                            ?>
                            <option value="<?= $product['id_employee'] ?>" <?= isset($filters['cashier']) && $filters['cashier'] == $product['id_employee'] ? "selected" : ""
                                  ?>>
                                <?= $product['empl_surname'] ?>
                                <?= $product['empl_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php else: ?>
                <input type="hidden" name="cashier" value="<?= $_SESSION['user_id'] ?>">
            <?php endif; ?>
            <label for="date-filter" class="date-filter">
                <span>Printed from</span>
                <input type="date" name="date-from" id="date-filter"
                    value="<?= isset($filters['date-from']) ? $filters['date-from'] : "2020-01-01" ?>">
                <span>to</span>
                <input type="date" name="date-to" id="date-filter"
                    value="<?= isset($filters['date-to']) ? $filters['date-to'] : date('Y-m-d') ?>">
            </label>
        </form>
    </section>
    <?php
    $sort = $filters['sort'] ? $filters['sort'] : 'print_date';
    $date_from = $filters['date-from'] ? date('Y-m-d', strtotime($filters['date-from'])) : date('Y-m-d', strtotime('2020-01-01'));
    $date_to = $filters['date-to'] ? date('Y-m-d', strtotime($filters['date-to'])) : date('Y-m-d');

    $date_from_filter = $filters['date-from'] ? "WHERE print_date >= '" . $date_from . "'" : '';
    $date_to_filter = $filters['date-to'] ? ($date_from_filter ? "AND" : "WHERE") . " print_date <= '" . date('Y-m-d', strtotime($date_to . ' +1 day')) . "'" : '';
    $filter_cashier = $filters['cashier'] ? ($date_from_filter || $date_to_filter ? "AND" : "WHERE") . " id_employee_bill = " . $filters['cashier'] : (has_role("cashier") ? 'WHERE id_employee_bill = ' . $_SESSION["user_id"] : '');

    $filter_search = isset($filters['search']) && $filters['search'] !== "" ? ($filter_cashier || $date_from_filter || $date_to_filter ? "AND" : "WHERE") . " Bill.bill_number LIKE '%" . $filters['search'] . "%'" : '';

    ?>

    <section class="control-panel">
        <?php if (has_role("cashier")): ?>
            <details class="add-form-container" <?= $_SESSION['detailsOpen'][$currentPage] ?>>
                <summary class="add-form-opener">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>

                    <h3>Create new bill</h3>
                </summary>
                <form action="actions.php?action=add-bill" method="post" class="add-form" id="create-bill-form">
                    <input type="hidden" name="id_employee_bill" value="<?= $_SESSION['user_id'] ?>">
                    <input type="hidden" name="bill_number" value="<?= get_new_bill_id() ?>">
                    <div class="items-container">
                        <fieldset class="bill-item-fieldset">
                            <label for="product">
                                <span>Product</span>
                                <select name="UPC[]" id="product" class="UPC">
                                    <option value="">Select product</option>
                                    <?php
                                    $stmt = $conn->query("SELECT UPC, product_name, producer FROM Store_Product LEFT JOIN Product ON Store_Product.id_product = Product.id_product ORDER BY product_name");
                                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($products as $product): ?>
                                        <option value="<?= $product['UPC'] ?>">
                                            <?= $product['product_name'] ?> by
                                            <?= $product['producer'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label for="selling_price">
                                <span>Price</span>
                                <span><output type="number" id="selling_price-output" class="selling_price-output"
                                        readonly>0.00</output>UAH</span>
                                <input type="hidden" name="selling_price[]" id="selling_price" value="0">
                            </label>
                            <label for="product_number">

                                <span>Quantity</span>
                                <input type="number" name="product_number[]" id="product_number" class="product_number"
                                    value="1" min="1" required>
                            </label>
                            <button class="delete table-btn" type="button" title="Delete item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                </svg>
                            </button>
                        </fieldset>

                    </div>

                    <button id="add-item-button" class="btn btn-primary">Add item</button>

                    <fieldset>
                        <label for="card_number">
                            <span>Discount card</span>
                            <select name="card_number" id="card_number">
                                <option value="">No discount</option>
                                <?php
                                $stmt = $conn->query("SELECT card_number, phone_number, cust_surname, cust_name, percent FROM Customer_Card ORDER BY phone_number, cust_surname, cust_name");
                                $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($cards as $card): ?>
                                    <option value="<?= $card['card_number'] ?>">
                                        <?= $card['phone_number'] ?>         <?= $card['cust_surname'] ?>         <?= $card['cust_name'] ?>
                                        (<?= $card['percent'] ?>%)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </fieldset>
                    <label for="sum_total" class="inline">
                        <span>Total:</span>
                        <span><output id="sum_total-output">0</output>UAH</span>
                        <input type="hidden" name="sum_total" id="sum_total" value="0">
                    </label>
                    <label for="discount" class="inline">
                        <span>Discount:</span>
                        <span><output id="discount">0</output>UAH</span>
                    </label>

                    <label for="vat" class="inline">
                        <span>Vat:</span>
                        <span><output name="vat" id="vat-output">0</output>UAH</span>
                        <input type="hidden" name="vat" id="vat" value="0">
                    </label>



                    <button type="submit" class="btn btn-primary ">Create a new bill</button>
                </form>
            </details>
        <?php elseif (has_role("manager")): ?>
            <details class="add-form-container" <?= $_SESSION['detailsOpen'][$currentPage] ?>>
                <summary class="add-form-opener">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>

                    <h2>STATISTICS</h2>
                </summary>
                <div class="totals-panel">
                    <div class="grand-totals">
                        <div class="totals-header">
                            <h2>Grand totals</h2>
                            <form action="bills.php?form=totals" method="post" class="totals-form ">
                                <input type="hidden" name="card_number" value="<?= $filters['card_number'] ?>">
                                <label for="product-filter" class="">For</label>
                                <select name="id_product" id="product-filter">
                                    <option value="" <?= !$filters['id_product'] ? "selected" : "" ?>>All store products
                                    </option>
                                    <?php

                                    $stmt = $conn->query("SELECT DISTINCT Product.id_product, product_name, producer FROM Store_Product LEFT JOIN Product ON Store_Product.id_product = Product.id_product WHERE UPC IN (SELECT DISTINCT Sale.UPC FROM Sale) ORDER BY product_name");
                                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($products as $product): ?>
                                        <option value="<?= $product['id_product'] ?>" <?= isset($filters['id_product']) && $filters['id_product'] == $product['id_product'] ? "selected" : ""
                                              ?>>
                                            <?= $product['product_name'] ?> by
                                            <?= $product['producer'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </form>
                        </div>
                        <div class="totals-container">

                            <?php
                            if ($filters['id_product']):
                                $id_product = $filters['id_product'];


                                $stmt = $conn->prepare("SELECT (SUM(S.product_number * S.selling_price) - SUM(S.selling_price * S.product_number * COALESCE((SELECT percent FROM Customer_Card WHERE card_number = B.card_number), 0) * 0.01)) AS total_income, SUM(S.product_number) AS total_quantity FROM  (Sale S LEFT JOIN  Bill B ON B.bill_number = S.bill_number)  LEFT JOIN  Store_Product SP ON SP.UPC = S.UPC WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param AND SP.id_product =  :id_product_param GROUP BY S.UPC");

                                $stmt->bindParam(':date_from_param', $date_from);
                                $stmt->bindParam(':date_to_param', $date_to);
                                $stmt->bindParam(':id_product_param', $id_product);
                                $stmt->execute();

                                $product_totals = $stmt->fetch(PDO::FETCH_ASSOC);


                                ?>


                                <h3><span>Total number of items sold:</span><span class='value'>
                                        <?= intval($product_totals["total_quantity"]) ?>
                                    </span></h3>
                                <h3><span>Total income:</span><span class='value'>
                                        <span class="decimal"><?= doubleval($product_totals["total_income"]) ?></span> UAH
                                    </span></h3>
                                <h3><span>Excluding VAT:</span><span class='value'>
                                        <span class="decimal"><?= $product_totals["total_income"] * 0.8 ?></span> UAH
                                    </span></h3>

                            <?php else:
                                if ($filters['cashier']) {
                                    $stmt = $conn->prepare("SELECT SUM(product_number) as total_quantity, SUM(sum_total) as total_income,  SUM(vat) as total_vat FROM (Sale AS S LEFT JOIN Bill AS B ON S.bill_number = B.bill_number) LEFT JOIN Employee ON Employee.id_employee = B.id_employee_bill WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param AND B.id_employee_bill = :id_employee_param GROUP BY B.id_employee_bill");
                                    $stmt->bindParam(':id_employee_param', $filters['cashier']);

                                } else {
                                    $stmt = $conn->prepare("SELECT SUM(product_number) as total_quantity, SUM(sum_total) as total_income,  SUM(vat) as total_vat FROM Sale AS S LEFT JOIN Bill AS B ON S.bill_number = B.bill_number WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param");
                                }
                                $stmt->bindParam(':date_from_param', $date_from);
                                $stmt->bindParam(':date_to_param', $date_to);
                                $stmt->execute();
                                $totals = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>

                                <h3><span>Total number of items sold:</span><span class='value'>
                                        <?= intval($totals["total_quantity"]) ?>
                                    </span></h3>
                                <h3><span>Total income:</span><span class='value'>
                                        <span class="decimal"><?= doubleval($totals["total_income"]) ?></span> UAH
                                    </span></h3>

                                <h3><span>Excluding VAT:</span><span class='value'>
                                        <span
                                            class="decimal"><?= doubleval($totals["total_income"]) - doubleval($totals["total_vat"]) ?></span>
                                        UAH
                                    </span></h3>


                            <?php endif; ?>

                        </div>
                    </div>
                    <div>
                        <div class="totals-header">
                            <h2>Top 5 most profitable products</h2>
                        </div>
                        <div class="totals-container">

                            <?php
                            $stmt = $conn->prepare("SELECT P.product_name,  P.producer, (SUM(S.product_number * S.selling_price) - SUM(S.selling_price * S.product_number * COALESCE((SELECT percent FROM Customer_Card WHERE card_number = B.card_number), 0) * 0.01)) AS total_profit FROM  Product P JOIN Store_Product SP ON P.id_product = SP.id_product JOIN Sale S ON SP.UPC = S.UPC JOIN Bill B ON S.bill_number = B.bill_number WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param GROUP BY P.product_name, P.producer ORDER BY total_profit DESC LIMIT 5");

                            $stmt->bindParam(':date_from_param', $date_from);
                            $stmt->bindParam(':date_to_param', $date_to);
                            $stmt->execute();

                            $top_profit = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo '<ol>';
                            foreach ($top_profit as $item) {
                                echo "<li><h3><span>" . $item['product_name'] . " by " . $item['producer'] . ":</span><span class='value'><span class='decimal'>" . $item['total_profit'] . "</span> UAH</span></h3></li>";
                            }
                            echo '</ol>'
                                ?>

                        </div>
                    </div>
                    <div>
                        <div class="totals-header">
                            <h2>Top 5 most popular products</h2>
                        </div>
                        <div class="totals-container">

                            <?php

                            $stmt = $conn->prepare("SELECT P.product_name, P.producer, SUM(S.product_number) AS total_units_sold
                                FROM Product P
                                JOIN Store_Product SP ON P.id_product = SP.id_product
                                JOIN Sale S ON SP.UPC = S.UPC
                                JOIN Bill B ON S.bill_number = B.bill_number
                                WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param
                                GROUP BY P.product_name, P.producer
                                ORDER BY total_units_sold DESC LIMIT 5");



                            $stmt->bindParam(':date_from_param', $date_from);
                            $stmt->bindParam(':date_to_param', $date_to);
                            $stmt->execute();

                            $top_popular = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo '<ol>';
                            foreach ($top_popular as $item) {
                                echo "<li><h3><span>" . $item['product_name'] . " by " . $item['producer'] . ":</span><span class='value'>" . $item['total_units_sold'] . " items sold</span></h3></li>";
                            }
                            echo '</ol>'
                                ?>


                        </div>
                    </div>
                    <div>
                        <div class="totals-header">
                            <h2>Top 4 most frequent customers</h2>
                        </div>

                        <div class="totals-container">
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT CONCAT(CC.cust_surname, ' ', CC.cust_name) AS customer, CC.card_number, COUNT(B.bill_number) AS visit_count, (SELECT CONCAT(E.empl_surname, ' ', E.empl_name) FROM Employee E WHERE E.id_employee = (SELECT B2.id_employee_bill FROM Bill B2 WHERE B2.card_number = CC.card_number GROUP BY B2.id_employee_bill ORDER BY COUNT(B2.bill_number) DESC LIMIT 1)) AS most_frequent_employee FROM Customer_Card CC INNER JOIN Bill B ON CC.card_number = B.card_number WHERE B.print_date >= :date_from_param AND B.print_date <= :date_to_param GROUP BY CC.cust_surname, CC.cust_name, CC.card_number ORDER BY visit_count DESC, customer ASC LIMIT 4");
                                $stmt->bindParam(':date_from_param', $date_from);
                                $stmt->bindParam(':date_to_param', $date_to);
                                $stmt->execute();

                                $top_customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {
                                echo "<div class='banner alert-danger'>There was an error executing the query</div>";
                                
                            }
                            if ($top_customers): ?>
                            <ol>
                                <?php foreach ($top_customers as $cust): ?>
                                    <li>
                                        <h3>
                                            <span><?= $cust['customer'] ?>
                                                <br>
                                                <small>mostly served by <?= $cust['most_frequent_employee'] ?></small>
                                            </span>
                                            <span class='value'><?= $cust['visit_count'] ?> visits</span>
                                        </h3>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </details>

            <button class="btn-control print-bills">Print a report</button>
        <?php endif; ?>
    </section>


    <?php
    //message banner that indicates status of operation
    if (isset($_SESSION['message'])) {
        echo '<div class="banner alert-' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '<button class="bi close">🗙</button></div>';

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }


    $stmt = $conn->prepare("SELECT Bill.bill_number, Bill.card_number, print_date, vat, sum_total, empl_surname, empl_name, percent FROM (Bill LEFT JOIN Employee ON Employee.id_employee = Bill.id_employee_bill)  LEFT JOIN Customer_Card ON Customer_Card.card_number = Bill.card_number  $date_from_filter $date_to_filter $filter_cashier $filter_search ORDER BY $sort");
    $stmt->execute();
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($bills) > 0) {
        if ($filters['cashier']) {
            //var_dump($filters['cashier']);
            $empl_id = $filters['cashier'];
            $stmt = $conn->query("SELECT empl_surname, empl_name FROM Employee WHERE id_employee = $empl_id");
            $empl = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        echo '<div class="banner alert-info">
        Found ' . count($bills) .
            ($filter_search ? ' match' . (count($bills) > 1 ? 'es' : '') . ' for search query "' . $filters['search'] . '"' : ' bill' . (count($bills) > 1 ? 's' : '')) .
            ($empl ? ' by cashier ' . $empl["empl_surname"] . ' ' . $empl["empl_name"] : '') .
            ($date_from_filter ? ' from ' . $filters['date-from'] : '') .
            ($date_to_filter ? ' to ' . $filters['date-to'] : '') .
            '<button class="bi close">🗙</button></div>';
    }

    if (count($bills) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <section class="bills-container">
            <?php
            foreach ($bills as $bill): ?>
                <?php
                $bill_number = $bill['bill_number'];
                $card_number = $bill['card_number'];
                $stmt = $conn->prepare("SELECT  product_number, Sale.selling_price, product_name, producer FROM (Sale LEFT JOIN Store_Product ON Store_Product.UPC = Sale.UPC) LEFT JOIN Product ON Store_Product.id_product = Product.id_product WHERE Sale.bill_number = $bill_number");
                $stmt->execute();

                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);




                $bill['items'] = $items;
                $bills[$bill_number] = $bill;
                ?>


                <div class="bill">
                    <?php if (has_role('manager')): ?>
                        <td><button meta-id="<?= $bill['bill_number'] ?>" meta-table="Bill" meta-key="bill_number"
                                class="delete table-btn" title="Delete item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                </svg>
                            </button></td>
                    <?php endif; ?>
                    <div class="bill-header">
                        <h2>ZLAGODA</h2>
                        <div class="bill-number">Bill #
                            <?= $bill['bill_number'] ?>
                        </div>
                        <div class="bill-date">
                            <?= $bill['print_date'] ?>
                        </div>
                        <div class="bill-cashier">Cashier:
                            <?= $bill['empl_surname'] ?>
                            <?= $bill['empl_name'] ?>
                        </div>

                    </div>
                    <div class="bill-items">
                        <!-- <h3>Items:</h3> -->
                        <?php foreach ($items as $item): ?>
                            <div class="bill-item">

                                <div>
                                    <p class="bill-item-name">
                                        <?= $item['product_name'] ?>
                                    </p>
                                    <p class="bill-item-producer">
                                        <?= $item['producer'] ?>
                                    </p>
                                </div>
                                <p class="bill-item-price">
                                    <?= $item['product_number'] ?> x
                                    <span class="decimal"><?= $item['selling_price'] ?></span>UAH
                                </p>

                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="bill-footer">
                        <h3 class="bill-total">
                            <span>TOTAL</span>
                            <span>
                                =
                                <span class="decimal"><?= $bill['sum_total'] ?></span>UAH
                            </span>
                        </h3>
                        <?php if ($bill['card_number']): ?>
                            <div class="bill-card">
                                <span>Applied discount:</span>
                                <span>-
                                    <span class="decimal">
                                        <?php
                                        $discount = doubleval($bill['sum_total']) / (100 - intval($bill['percent'])) * intval($bill['percent']);
                                        echo $discount;
                                        ?></span>UAH
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="bill-vat">
                            <span>VAT:</span>
                            <span>
                                <span class="decimal"><?= $bill['vat'] ?></span>UAH
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>



        </section>

    <?php endif; ?>
</main>

<?php include 'components/footer.php'; ?>