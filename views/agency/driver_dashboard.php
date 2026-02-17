<pre><?php print_r($deliveries); ?></pre>

<div class="container mt-4">

    <h4 class="mb-2">Driver Overview</h4>
    <p class="text-muted">
        Review assigned deliveries and update delivery status.
    </p>

    <?php if (empty($deliveries)): ?>
        <div class="alert alert-info">
            No deliveries assigned yet.
        </div>
    <?php else: ?>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveries as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['order_number']) ?></td>
                                <td><?= htmlspecialchars($d['customer_name']) ?></td>
                                <td><?= htmlspecialchars($d['address']) ?></td>
                                <td><?= htmlspecialchars($d['delivery_date']) ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($d['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($d['status'] !== 'Delivered'): ?>
                                        <a href="index.php?route=driver/updateStatus&id=<?= $d['id'] ?>"
                                           class="btn btn-sm btn-primary">
                                            Update Status
                                        </a>
                                    <?php else: ?>
                                        <span class="text-success">Completed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>

</div>

