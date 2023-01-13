<?php

require_once "../config.php";

$db = new mysqli($database_host, $database_user, $database_password, $database_schema);

$mode = $_GET['mode'];
switch ($mode) {
    case "daily":
        $txndate = $_GET['txndate'];
        $base_group = "WHERE DATE(od.transaction_date) = '$txndate'
        GROUP BY i.item_id";
        break;
}


$base_query = "SELECT 
    od.transaction_date,
    i.item_id,
    i.item_name,
    i.item_desc,
    od.price,
    od.discount,
    od.tax_rate,
    SUM(od.order_qty) items_sold,
    SUM(od.order_qty * od.price) gross_sales_total,
    SUM((od.price * (od.discount / 100)) * od.order_qty) total_discount,
    SUM(od.price * (od.tax_rate / 100) * od.order_qty) total_tax
FROM items i
LEFT JOIN items_list il ON i.item_id = il.items_id
LEFT JOIN orders_detail od ON od.item_id = i.item_id
LEFT JOIN orders o ON o.order_id = od.order_id
LEFT JOIN employee e ON e.emp_id = o.emp_id
$base_group";


$query = "SELECT *, gross_sales_total - total_tax - total_discount net_sales FROM (
	$base_query
) base
ORDER BY net_sales DESC";

$stmt = $db->query($query);
$result = array();

while ($v = $stmt->fetch_assoc()) {
    $result[] = $v;
}

echo json_encode($result);
