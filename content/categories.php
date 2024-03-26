<?php

?>
<?php include 'components/header.php'; ?>
<main class="main-wrap">
    <h1>All product categories</h1>


    <?php if (has_role('manager')): ?>
        <section class="control-panel">


            <details class="add-form-container">
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

    $stmt = $conn->prepare("SELECT * FROM Category ORDER BY category_name");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($items) == 0): ?>
        <div class="banner alert-danger">Nothing is found</div>
    <?php else: ?>

        <form action="actions.php?action=edit" method="post" id="edit-form">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($items as $d):
                        
                        ?>

                        <tr>
                            <td>
                                <?= $d['category_number'] ?>
                            </td>
                            <td data-key="category_name" data-nn="true">
                                <?= $d['category_name'] ?>
                            </td>

            
                            <?php if (has_role('manager')): ?>
                                <td>
                                    <button meta-id="<?= $d['category_number'] ?>" meta-table="Category" meta-key="category_number"
                                        class="edit table-btn" aria-roledescription="edit" title="Edit item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                        </svg>
                                    </button>
                                </td>
                                <td><button meta-id="<?= $d['category_number'] ?>" meta-table="Category" meta-key="category_number"
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