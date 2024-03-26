<footer class="footer">
    <div class="footer-links">
        <a href="<? ROOT_PATH ?>/index.php" target="" class="logo">
            <img src="<? ROOT_PATH ?>/assets/img/logo.svg" alt="Zlagoda AIS logo">
            <h2>ZLAGODA</h2>
        </a>
        <nav class="mainmenu">
            <ul>
                <?php if (has_role('manager')): ?>
                    <li>
                        <a href="<? ROOT_PATH ?>/employees.php" target="">Employees</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<? ROOT_PATH ?>/bills.php" target="">Bills</a>
                </li>
                <li>
                    <a href="<? ROOT_PATH ?>/clients.php" target="">Clients</a>
                </li>
                <li>
                    <a href="<? ROOT_PATH ?>/products.php" target="">Products</a>
                </li>
                <li>
                    <a href="<? ROOT_PATH ?>/categories.php" target="">Categories</a>
                </li>

            </ul>
        </nav>
    </div>

    <p>&copy;
        <?php echo date("Y"); ?> Zlagoda AIS by Kryzhanivska Marharyta, Bohun Yelyzaveta and Kviatkovskyi Andrii
    </p>
</footer>
<script src="<? ROOT_PATH ?>/build/js/scripts.min.js"></script>
</body>

</html>