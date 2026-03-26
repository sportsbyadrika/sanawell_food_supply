<?php
class Route extends BaseModel
{
    protected $table = "routes";

   public function allByAgency($agencyId)
{
    $stmt = $this->db->prepare("
    SELECT 
        r.*, 
        u.name AS driver_name,
        v.vehicle_no
    FROM routes r
    LEFT JOIN users u ON r.driver_id = u.id
    LEFT JOIN vehicles v ON r.vehicle_id = v.id
    WHERE r.agency_id = ?
    ORDER BY r.id DESC
");

    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO routes (agency_id, name, type, description)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['agency_id'],
            $data['name'],
            $data['type'],
            $data['description']
        ]);
    }

    public function find(int $id): ?array
{
    $stmt = $this->db->prepare("
        SELECT * FROM routes 
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        'id' => $id
    ]);

    $result = $stmt->fetch();

    return $result ?: null;
}
public function update(int $id, array $data): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
SET name = ?, type = ?, description = ?, driver_id = ?,vehicle_id=? 
WHERE id = ?
    ");

    $stmt->execute([
        $data['name'],
        $data['type'],
        $data['description'],
        $data['driver_id'],
         $data['vehicle_id'], 
        $id
    ]);
}

public function updateStatus(int $id, int $status): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
        SET status = ?
        WHERE id = ?
    ");

    $stmt->execute([$status, $id]);
}
public function toggle(int $id): void
{
    $stmt = $this->db->prepare("
        UPDATE routes
        SET status = IF(status = 1, 0, 1)
        WHERE id = ?
    ");

    $stmt->execute([$id]);
}

public function allActive(): array
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM routes
        WHERE status = 'active'
        ORDER BY name ASC
    ");

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAllByAgency($agencyId)
{
    $stmt = $this->db->prepare("
        SELECT * FROM routes
        WHERE agency_id = ?
        AND status = 1
        ORDER BY name ASC
    ");

    $stmt->execute([$agencyId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getRoutesWithCustomerCount($agencyId)
{
  $sql = "
SELECT
    r.*,
    COUNT(DISTINCT c.id) as total_customers
FROM routes r
LEFT JOIN customers c
    ON c.route_id = r.id
    AND c.status = 1
WHERE r.agency_id = ?
GROUP BY r.id
ORDER BY r.name ASC
";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$agencyId]);

    return $stmt->fetchAll();
}

public function findById($id)
{
    $sql = "SELECT * FROM routes WHERE id = ? LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}
public function getCustomersByRoute($routeId)
{

// Auto sync route customers
$sync = $this->db->prepare("
INSERT INTO route_customers (route_id, customer_id, delivery_order, created_at)
SELECT
    c.route_id,
    c.id,
    ROW_NUMBER() OVER (ORDER BY c.id),
    NOW()
FROM customers c
LEFT JOIN route_customers rc
    ON rc.customer_id = c.id
    AND rc.route_id = c.route_id
WHERE c.route_id = ?
AND c.status = 1
AND rc.id IS NULL");

$sync->execute([$routeId]);
    $stmt = $this->db->prepare("
SELECT 
    c.id,
    c.name,
    c.address,
    c.mobile,
    rc.delivery_order
FROM route_customers rc
JOIN customers c 
    ON c.id = rc.customer_id
WHERE rc.route_id = ?
AND c.status = 1
ORDER BY rc.delivery_order ASC
");

    $stmt->execute([$routeId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function updateCustomerOrder($customerId, $newOrder, $routeId)
{
    $db = $this->db;

    // Get current order
    $stmt = $db->prepare("
        SELECT delivery_order 
        FROM route_customers 
        WHERE route_id = ? AND customer_id = ?
    ");
    $stmt->execute([$routeId, $customerId]);
    $currentOrder = $stmt->fetchColumn();

    if (!$currentOrder) return;

    $db->beginTransaction();

    try {

        // Swap the existing order
        $swapStmt = $db->prepare("
            UPDATE route_customers
            SET delivery_order = ?
            WHERE route_id = ?
            AND delivery_order = ?
        ");
        $swapStmt->execute([$currentOrder, $routeId, $newOrder]);

        // Update selected customer
        $updateStmt = $db->prepare("
            UPDATE route_customers
            SET delivery_order = ?
            WHERE route_id = ?
            AND customer_id = ?
        ");
        $updateStmt->execute([$newOrder, $routeId, $customerId]);

        $db->commit();

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
public function getByDriver($driverId)
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM routes
        WHERE driver_id = ?
        AND status = 1
        ORDER BY id DESC
    ");

    $stmt->execute([$driverId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function findByIdAndDriver(int $routeId, int $driverId): ?array
{
    $stmt = $this->db->prepare("
        SELECT * FROM routes
        WHERE id = ? AND driver_id = ?
        LIMIT 1
    ");

    $stmt->execute([$routeId, $driverId]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function get_products_by_customer($customerId)
{
    $sql = "SELECT p.name, p.variant, cp.quantity
            FROM customer_products cp
            JOIN products p ON p.id = cp.product_id
            WHERE cp.customer_id = :customer_id
            AND cp.status = 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['customer_id' => $customerId]);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$products) return '';

    $output = '';

    foreach ($products as $product) {

    $name = $product['name'];

    if (!empty($product['variant'])) {
        $name .= ' (' . $product['variant'] . ')';
    }

    $qty = (int) $product['quantity'];   

    $output .= "
    <div class='bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-2 shadow-sm'>
        <div class='text-sm font-semibold text-indigo-700'>{$name}</div>
        <div class='text-xs text-gray-600'>Qty: {$qty}</div>
    </div>
    ";
}
    return $output;
}
}
