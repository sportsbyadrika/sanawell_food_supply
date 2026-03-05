<h3 class="text-xl font-bold mb-4">
    Order #<?= $order['order_no'] ?>
</h3>

<p><strong>Customer:</strong> <?= $order['name'] ?></p>
<p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>

<hr class="my-4">

<form method="post" action="index.php?route=update_delivery_status">

    <input type="hidden" name="id" value="<?= $order['id'] ?>">

    <div class="mb-4">
        <label class="font-semibold">Delivery Status</label><br>

        <label>
            <input type="radio" name="status" value="delivered" required> Delivered
        </label>

        <label class="ml-4">
            <input type="radio" name="status" value="partial"> Partial
        </label>

        <label class="ml-4">
            <input type="radio" name="status" value="not_delivered"> Not Delivered
        </label>
    </div>

    <div class="mb-4">
        <label>Actual Quantity (if Partial)</label>
        <input type="number" step="0.01" name="actual_quantity"
               class="border px-3 py-2 rounded w-full">
    </div>

    <div class="mb-4">
        <label>Reason (if Not Delivered / Partial)</label>
        <select name="reason" class="border px-3 py-2 rounded w-full">
            <option value="">Select Reason</option>
            <option>Customer Absent</option>
            <option>Door Locked</option>
            <option>Customer Refused</option>
            <option>Product Unavailable</option>
            <option>Wrong Address</option>
            <option>Other</option>
        </select>
    </div>

    <div class="mb-4">
        <label>Remarks</label>
        <textarea name="remarks"
                  class="border px-3 py-2 rounded w-full"></textarea>
    </div>

    <button type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
        Save
    </button>

</form>