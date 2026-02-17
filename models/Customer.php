<?php

require_once __DIR__ . '/BaseModel.php';

class Customer extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create(array $data): bool
    {
       $sql = "
    INSERT INTO customers (
        agency_id,
        name,
        address,
        latitude,
        longitude,
        mobile,
        whatsapp,
        category_id,
        route_id,
        status,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
";

        $stmt = $this->db->prepare($sql);
return $stmt->execute([
    $data['agency_id'],
    $data['name'],
    $data['address'],
    $data['latitude'],
    $data['longitude'],
    $data['mobile'],
    $data['whatsapp'],
    $data['category_id'],
    $data['route_id'],   
    $data['status']
]);
    }

    public function allByAgency(int $agencyId): array
{
   
    $stmt = $this->db->prepare("
        SELECT 
            c.*,
            cc.name AS category_name,
            r.name AS route_name
        FROM customers c
        LEFT JOIN customer_categories cc 
            ON c.category_id = cc.id
        LEFT JOIN routes r 
            ON c.route_id = r.id
        WHERE c.agency_id = ?
        ORDER BY c.id DESC
    ");

    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function find(int $id)
{
    $stmt = $this->db->prepare("
        SELECT * FROM customers WHERE id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function update(int $id, array $data): bool
{
    $sql = "
        UPDATE customers SET
            name = ?,
            address = ?,
            latitude = ?,
            longitude = ?,
            mobile = ?,
            whatsapp = ?,
            category_id = ?,
            route_id = ?
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        $data['name'],
        $data['address'],
        $data['latitude'],
        $data['longitude'],
        $data['mobile'],
        $data['whatsapp'],
        $data['category_id'],
        $data['route_id'],
        $id
    ]);
}

public function getById(int $id)
{
    $stmt = $this->db->prepare("
        SELECT id, status
        FROM customers
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function filterByAgency($agencyId, $name = null, $typeId = null, $routeId = null)
{
    $sql = "
        SELECT 
            c.*,
            cc.name AS category_name,
            r.name AS route_name
        FROM customers c
        LEFT JOIN customer_categories cc 
            ON c.category_id = cc.id
        LEFT JOIN routes r 
            ON c.route_id = r.id
        WHERE c.agency_id = ?
    ";

    $params = [$agencyId];

    if (!empty($name)) {
        $sql .= " AND c.name LIKE ?";
        $params[] = "%$name%";
    }

    if (!empty($typeId)) {
        $sql .= " AND c.category_id = ?";
        $params[] = $typeId;
    }

    if (!empty($routeId)) {
        $sql .= " AND c.route_id = ?";
        $params[] = $routeId;
    }

    $sql .= " ORDER BY c.id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function filterByAgencyPaginated(
    $agencyId,
    $name = null,
    $typeId = null,
    $routeId = null,
    $limit = 10,
    $offset = 0
) {
    $sql = "
        SELECT 
            c.*,
            cc.name AS category_name,
            r.name AS route_name
        FROM customers c
        LEFT JOIN customer_categories cc 
            ON c.category_id = cc.id
        LEFT JOIN routes r 
            ON c.route_id = r.id
        WHERE c.agency_id = ?
    ";

    $params = [$agencyId];

    if ($name) {
        $sql .= " AND c.name LIKE ? ";
        $params[] = "%$name%";
    }

    if ($typeId) {
        $sql .= " AND c.category_id = ? ";
        $params[] = $typeId;
    }

    if ($routeId) {
        $sql .= " AND c.route_id = ? ";
        $params[] = $routeId;
    }

    $limit = (int)$limit;
    $offset = (int)$offset;

    $sql .= " ORDER BY c.id DESC LIMIT $limit OFFSET $offset ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function countFiltered(
    $agencyId,
    $name = null,
    $typeId = null,
    $routeId = null
) {
    $sql = "
        SELECT COUNT(*) 
        FROM customers c
        WHERE c.agency_id = ?
    ";

    $params = [$agencyId];

    if ($name) {
        $sql .= " AND c.name LIKE ? ";
        $params[] = "%$name%";
    }

    if ($typeId) {
        $sql .= " AND c.category_id = ? ";
        $params[] = $typeId;
    }

    if ($routeId) {
        $sql .= " AND c.route_id = ? ";
        $params[] = $routeId;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return (int)$stmt->fetchColumn();
}

public function toggle(int $id): bool
{
    // Get current status
    $stmt = $this->db->prepare("
        SELECT status 
        FROM customers 
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        return false;
    }

    // Toggle status
    $newStatus = ($customer['status'] === 'active') ? 'inactive' : 'active';

    $update = $this->db->prepare("
        UPDATE customers 
        SET status = ? 
        WHERE id = ?
    ");

    return $update->execute([$newStatus, $id]);
}

public function updateStatus(int $id, int $status): bool
{
    $stmt = $this->db->prepare("
        UPDATE customers
        SET status = ?
        WHERE id = ?
    ");

    return $stmt->execute([$status, $id]);
}

}
