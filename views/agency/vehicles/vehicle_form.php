<div class="max-w-xl mx-auto p-6">

<?php
$isEdit = isset($vehicle['id']);
$formAction = $isEdit ? 'vehicle_update' : 'vehicle_store';
?>

<h2 class="text-xl font-semibold mb-4"><?= $isEdit ? 'Edit Vehicle' : 'Add Vehicle' ?></h2>

<form method="POST" action="index.php?route=<?= $formAction ?>">

<?php if ($isEdit): ?>
<input type="hidden" name="id" value="<?= (int)$vehicle['id'] ?>">
<?php endif; ?>

<div class="mb-3">
<label>Vehicle No</label>
<input type="text" name="vehicle_no" value="<?= htmlspecialchars($vehicle['vehicle_no'] ?? '') ?>" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Vehicle Company</label>
<input type="text" name="vehicle_company" value="<?= htmlspecialchars($vehicle['vehicle_company'] ?? '') ?>" class="border w-full p-2">
</div>
<div class="mb-3">
<label>Vehicle Model</label>
<input type="text" name="vehicle_model" value="<?= htmlspecialchars($vehicle['vehicle_model'] ?? '') ?>" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Vehicle Type</label>
<select name="vehicle_type" class="border w-full p-2">
<option value="commercial" <?= (($vehicle['vehicle_type'] ?? '') === 'commercial') ? 'selected' : '' ?>>Commercial</option>
<option value="private" <?= (($vehicle['vehicle_type'] ?? '') === 'private') ? 'selected' : '' ?>>Private</option>
</select>
</div>

<div class="mb-3">
<label>Fuel Type</label>
<select name="fuel_type" class="border w-full p-2">
<option value="petrol" <?= (($vehicle['fuel_type'] ?? '') === 'petrol') ? 'selected' : '' ?>>Petrol</option>
<option value="diesel" <?= (($vehicle['fuel_type'] ?? '') === 'diesel') ? 'selected' : '' ?>>Diesel</option>
<option value="electric" <?= (($vehicle['fuel_type'] ?? '') === 'electric') ? 'selected' : '' ?>>Electric</option>
<option value="cng" <?= (($vehicle['fuel_type'] ?? '') === 'cng') ? 'selected' : '' ?>>CNG</option>
<option value="gas" <?= (($vehicle['fuel_type'] ?? '') === 'gas') ? 'selected' : '' ?>>Gas</option>
</select>
</div>

<div class="mb-3">
<label>Registration Date</label>
<input type="date" name="registration_date" value="<?= htmlspecialchars($vehicle['registration_date'] ?? '') ?>" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Insurance Valid Upto</label>
<input type="date" name="insurance_valid_upto" value="<?= htmlspecialchars($vehicle['insurance_valid_upto'] ?? '') ?>" class="border w-full p-2">
</div>

<button class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-4 py-2 rounded">
<?= $isEdit ? 'Update Vehicle' : 'Save Vehicle' ?>
</button>

</form>

</div>
