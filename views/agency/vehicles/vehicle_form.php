<div class="max-w-xl mx-auto p-6">

<h2 class="text-xl font-semibold mb-4">Add Vehicle</h2>

<form method="POST" action="index.php?route=vehicle_store">

<div class="mb-3">
<label>Vehicle No</label>
<input type="text" name="vehicle_no" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Vehicle Company</label>
<input type="text" name="vehicle_company" class="border w-full p-2">
</div>
<div class="mb-3">
<label>Vehicle Model</label>
<input type="text" name="vehicle_model" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Vehicle Type</label>
<select name="vehicle_type" class="border w-full p-2">
<option value="commercial">Commercial</option>
<option value="private">Private</option>
</select>
</div>

<div class="mb-3">
<label>Fuel Type</label>
<select name="fuel_type" class="border w-full p-2">
<option value="petrol">Petrol</option>
<option value="diesel">Diesel</option>
<option value="electric">Electric</option>
<option value="cng">CNG</option>
<option value="gas">Gas</option>
</select>
</div>

<div class="mb-3">
<label>Registration Date</label>
<input type="date" name="registration_date" class="border w-full p-2">
</div>

<div class="mb-3">
<label>Insurance Valid Upto</label>
<input type="date" name="insurance_valid_upto" class="border w-full p-2">
</div>

<button class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-4 py-2 rounded">
Save Vehicle
</button>

</form>

</div>