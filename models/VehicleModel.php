<?php

class VehicleModel
{
     private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

  public function getVehicles()
{
    $stmt = $this->db->prepare("
        SELECT 
            vehicle_no,
            vehicle_company,
            vehicle_model,
            vehicle_type,
            fuel_type,
            registration_date,
insurance_valid_upto
        FROM vehicles
        WHERE status = 1
        ORDER BY vehicle_no
    ");

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function createVehicle($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO vehicles
        (vehicle_no, vehicle_company, vehicle_type, fuel_type, registration_date, insurance_valid_upto)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['vehicle_no'],
        $data['vehicle_company'],
        $data['vehicle_type'],
        $data['fuel_type'],
        $data['registration_date'],
        $data['insurance_valid_upto']
    ]);
}

}