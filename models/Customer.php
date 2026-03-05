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
            r.name AS route_name,
            r.type AS route_type
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

public function find(int $id): ?array
{
    $stmt = $this->db->prepare("
        SELECT 
            c.*,
            cc.name AS customer_type_name,
            r.name AS route_name

        FROM customers c
        LEFT JOIN customer_categories cc 
            ON cc.id = c.category_id
        LEFT JOIN routes r 
            ON r.id = c.route_id
        WHERE c.id = ?
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
            r.name AS route_name,
            r.type AS route_type
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

public function addCustomerProduct($customerId, $productId, $quantity, $routeId, $rate, $total)
{
    // Insert product
    $stmt = $this->db->prepare("
        INSERT INTO customer_products
        (customer_id, product_id, quantity, route_id, rate, total_amount, created_at, status)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)
    ");

    $stmt->execute([
        $customerId,
        $productId,
        $quantity,
        $routeId,
        $rate,
        $total
    ]);

    // Always ensure customer exists in route
    $check = $this->db->prepare("
        SELECT id 
        FROM route_customers
        WHERE route_id = ? AND customer_id = ?
    ");

    $check->execute([$routeId,$customerId]);

    if(!$check->fetch()){

        // Get next delivery order
        $orderStmt = $this->db->prepare("
            SELECT IFNULL(MAX(delivery_order),0)+1
            FROM route_customers
            WHERE route_id=?
        ");

        $orderStmt->execute([$routeId]);
        $nextOrder = $orderStmt->fetchColumn();

        // Insert route entry
        $insert = $this->db->prepare("
            INSERT INTO route_customers
            (route_id,customer_id,delivery_order,created_at)
            VALUES (?,?,?,NOW())
        ");

        $insert->execute([$routeId,$customerId,$nextOrder]);
    }

    return true;
}
public function getCustomerProducts($customerId)
{
    $stmt = $this->db->prepare("
    SELECT
        cp.id,
        cp.product_id,
        cp.route_id,
        cp.quantity,
        cp.rate,
        cp.total_amount,
        cp.status,
        p.name AS product_name,
        p.variant AS product_variant,
        r.name AS route_name,
        r.type AS route_type
    FROM customer_products cp
    JOIN products p ON p.id = cp.product_id
    JOIN routes r ON r.id = cp.route_id
    WHERE cp.customer_id = ?
");
    $stmt->execute([$customerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function toggleCustomerProduct($id)
{
    $stmt = $this->db->prepare("
        UPDATE customer_products
        SET status = IF(status = 1, 0, 1)
        WHERE id = ?
    ");

    return $stmt->execute([$id]);
}
public function getCustomerProductById($id)
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM customer_products
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateCustomerProduct($id, $quantity, $routeId, $total)
{
    $stmt = $this->db->prepare("
        UPDATE customer_products
        SET quantity = ?,
            route_id = ?,
            total_amount = ?
        WHERE id = ?
    ");

    return $stmt->execute([
        $quantity,
        $routeId,
        $total,
        $id
    ]);
}

public function getByRoute(int $routeId): array
{
    $stmt = $this->db->prepare("
        SELECT * 
        FROM customers 
        WHERE route_id = ?
        ORDER BY route_order ASC
    ");
    
    $stmt->execute([$routeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function importProcess()
{
    if(isset($_FILES['csv_file']))
    {

        $file = $_FILES['csv_file']['tmp_name'];

        $handle = fopen($file,"r");

        fgetcsv($handle); // skip header

        while(($row = fgetcsv($handle,1000,",")) !== FALSE)
        {

            $name = $row[0];
            $mobile = $row[1];
            $address = $row[2];
            $whatsapp = $row[3];
            $category = $row[4];
            $route = $row[5];

            $category_id = $this->customerModel->getCategoryId($category);
            $route_id = $this->customerModel->getRouteId($route);

            if($category_id && $route_id)
            {
                $data = [
                    'name'=>$name,
                    'mobile'=>$mobile,
                    'address'=>$address,
                    'whatsapp'=>$whatsapp,
                    'category_id'=>$category_id,
                    'route_id'=>$route_id
                ];

                $this->customerModel->insertCustomer($data);
            }

        }

        fclose($handle);

        header("Location:index.php?route=customers");

    }
}

public function getCategoryId($name)
{
    $sql = "SELECT id FROM customer_categories WHERE TRIM(name)=?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$name]);

    return $stmt->fetchColumn();
}

public function getRouteId($name)
{
    $sql = "SELECT id FROM routes WHERE TRIM(name)=?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$name]);

    return $stmt->fetchColumn();
}

public function insertCustomer($data)
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
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
";

$stmt = $this->db->prepare($sql);

$stmt->execute([
$data['agency_id'],
$data['name'],
$data['address'],
0,
0,
$data['mobile'],
$data['whatsapp'],
$data['category_id'],
$data['route_id'],
1
]);

}
}
