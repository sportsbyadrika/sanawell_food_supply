<h2>Set Product Rates</h2>

<form method="POST" action="index.php?route=product_rate_store">
    <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">

    <table class="table">
        <thead>
            <tr>
                <th>Customer Category</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td>
                    <input type="number" step="0.01"
                           name="rates[<?= $cat['id'] ?>]"
                           placeholder="Enter rate">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn-primary">Save Rates</button>
</form>
