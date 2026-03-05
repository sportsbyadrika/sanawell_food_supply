<div class="max-w-6xl mx-auto py-10 px-6">

    <h2 class="text-xl font-bold text-gray-800 mb-6">
        Customers - <?= htmlspecialchars($route['name']) ?>(<?= htmlspecialchars($route['type']) ?>)
    </h2>

    <?php if (empty($customers)): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            No customers assigned to this route.
        </div>
    <?php else: ?>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class=" bg-gradient-to-br from-blue-500 to-slate-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Order No</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <?= htmlspecialchars($order['order_no']) ?>
                            </td>
                            <td class="px-6 py-3">
                                <?= htmlspecialchars($order['name']) ?>
                            </td>
                          
                             <td>
                <?php if($order['status']=='delivered'): ?>
                    <span class="badge bg-success">Delivered</span>
                <?php elseif($order['status']=='not_delivered'): ?>
                    <span class="badge bg-danger">Not Delivered</span>
                <?php else: ?>
                    <span class="badge bg-warning">Pending</span>
                <?php endif; ?>
            </td>

  <td>
                <a href="index.php?route=driver_order_details&id=<?= $order['id'] ?>">Details</a> |
                <a href="index.php?route=driver_update_delivery&id=<?= $order['id'] ?>&route_id=<?= $_GET['id'] ?>">Update</a>
            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>