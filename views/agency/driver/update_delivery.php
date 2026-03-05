<form method="POST">

    <label>Status</label>
    <select name="status" required>
        <option value="delivered">Delivered</option>
        <option value="not_delivered">Not Delivered</option>
    </select>

    <br><br>

    <label>Reason</label>
    <textarea name="reason" placeholder="Enter reason if not delivered"></textarea>

    <br><br>

    <button type="submit">Save</button>

</form>