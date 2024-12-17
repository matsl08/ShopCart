<?php
session_start();
ob_start();
// Set the FPDF font path if you are using custom fonts
define('FPDF_FONTPATH', __DIR__ . '/font/');

// Include necessary files
require_once 'fpdf.php';
require_once __DIR__ . "/../Admin/database_connection.php";
require_once __DIR__ . "/../Classes/Buyer.php";
// session_start();

$buyer = new Buyer($connect);

if (!isset($_SESSION['order_id'])) {
    // Fetch the latest order ID only if session order ID is not set
    $order_id = $buyer->getLatestOrderId($connect);
    if (!$order_id) {
        echo "No orders available.";
        exit;
    }
} else {
    $order_id = intval($_SESSION['order_id']);
}

// Check if order ID is available in the session
if (isset($_SESSION['order_id'])) {
    $order_id = intval($_SESSION['order_id']);
    $receiptDetails = $buyer->getReceiptDetails($connect, $order_id); // Pass the database connection and order_id

    if ($receiptDetails === null) {
        echo "No orders found for Order ID: " . htmlspecialchars($order_id);
        exit;
    }

    // Extract receipt details
    $name = htmlspecialchars($receiptDetails['buyer_name']);
    $payment_method = htmlspecialchars($receiptDetails['payment_method']);
    $total_product = htmlspecialchars($receiptDetails['total_products']);
    $total_price = htmlspecialchars($receiptDetails['total_price']);

    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Order Receipt', 0, 1, 'C');
    $pdf->Ln(10); // Add a line break

    // Receipt Details Section
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'Timestamp:', 0, 0);
    $pdf->Cell(0, 10, date("Y-m-d H:i:s"), 0, 1);

    $pdf->Cell(50, 10, 'Name:', 0, 0);
    $pdf->Cell(0, 10, $name, 0, 1);

    $pdf->Cell(50, 10, 'Payment Method:', 0, 0);
    $pdf->Cell(0, 10, $payment_method, 0, 1);

    $pdf->Cell(50, 10, 'Total Products:', 0, 0);
    $pdf->Cell(0, 10, $total_product, 0, 1);

    $pdf->Cell(50, 10, 'Total Price:', 0, 0);
    $pdf->Cell(0, 10, 'P' . number_format($total_price, 2), 0, 1);

    // Footer Section
    $pdf->SetY(-30); // Position at 3 cm from the bottom
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Thank you for shopping with ShopCart!', 0, 1, 'C');

    // Output the PDF to the browser
// Clear the output buffer before generating the PDF
ob_clean();
$pdf->Output('I', 'receipt.pdf'); // 'I' to display in the browser, 'D' for download
ob_end_flush();
} else {
    // Error message if order ID is not found in the session
    echo "Invalid order details!";
    exit;
}
?>
