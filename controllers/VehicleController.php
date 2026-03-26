<?php

class VehicleController extends BaseController
{
    public function index()
    {
        Auth::requireAgencyAdmin();

        $vehicleModel = new VehicleModel();
        $vehicles = $vehicleModel->getVehicles();

        $this->render('agency/vehicles/vehicle_list', [
            'title' => 'Vehicles',
            'vehicles' => $vehicles
        ]);
    }

    public function create()
    {
        Auth::requireAgencyAdmin();
         $this->render('agency/vehicles/vehicle_form', [
            'title' => 'Add Vehicle'
        ]);
    }

    public function store() 
    {
    Auth::requireAgencyAdmin();

   $agencyId = $_SESSION['user']['agency_id'] ?? null;

    if (!$agencyId) {
        die("Agency not found. Please login again.");
    }

    $vehicleModel = new VehicleModel();

    $vehicleModel->createVehicle([
        'agency_id' => $agencyId,
        'vehicle_no' => $_POST['vehicle_no'],
        'vehicle_company' => $_POST['vehicle_company'],
        'vehicle_model' => $_POST['vehicle_model'],
        'vehicle_type' => $_POST['vehicle_type'],
        'fuel_type' => $_POST['fuel_type'],
        'registration_date' => $_POST['registration_date'],
        'insurance_valid_upto' => $_POST['insurance_valid_upto']
    ]);

    header("Location: index.php?route=vehicles");
}
}
