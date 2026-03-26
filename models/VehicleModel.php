<?php

class VehicleModel
{
     private $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

  public function getVehicles($agencyId = null)
{
   $sql = "SELECT
            id,
            vehicle_no,
            vehicle_company,
             vehicle_model,
            vehicle_type,
            fuel_type,
            registration_date,
            insurance_valid_upto
        FROM vehicles
        WHERE status = 1";

    if ($agencyId) {
        $sql .= " AND agency_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$agencyId]);
    } else {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function createVehicle($data)
{
    $stmt = $this->db->prepare("
        INSERT INTO vehicles
        (agency_id,vehicle_no, vehicle_model, vehicle_company, vehicle_type, fuel_type, registration_date, insurance_valid_upto)
        VALUES (?, ?, ?, ?, ?, ?,?,?)
    ");

    $stmt->execute([
        $data['agency_id'],
        $data['vehicle_no'],
        $data['vehicle_company'],
        $data['vehicle_model'],
        $data['vehicle_type'],
        $data['fuel_type'],
        $data['registration_date'],
        $data['insurance_valid_upto']
    ]);
}

}