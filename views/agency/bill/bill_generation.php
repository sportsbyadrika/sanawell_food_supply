<form method="POST" action="index.php?route=generate_bill">

<select name="route_id">

<?php foreach($routes as $route){ ?>

<option value="<?=$route['id']?>">

<?=$route['route_name']?>

</option>

<?php } ?>

</select>

<input type="date" name="from_date">

<input type="date" name="to_date">

<button class="btn btn-primary">

Generate Bill

</button>

</form>