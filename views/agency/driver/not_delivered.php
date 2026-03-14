<form method="post" action="index.php?route=driver_save_not_delivered">

<input type="hidden" name="delivery_id" value="<?= $id ?>">
<input type="hidden" name="route_id" value="<?= $_GET['route_id'] ?>">

<label>Reason</label>

<select name="reason">

<option value="Customer Absent">Customer Absent</option>
<option value="Door Locked">Door Locked</option>
<option value="Customer Refused">Customer Refused</option>
<option value="Product Unavailable">Product Unavailable</option>
<option value="Wrong Address">Wrong Address</option>
<option value="Other">Other</option>

</select>

<label>Remarks</label>
<textarea name="remarks"></textarea>

<button type="submit">Save</button>

</form>