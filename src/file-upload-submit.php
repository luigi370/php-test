<?php

require_once __DIR__ . '/../vendor/autoload.php';

use src\core\common\utils\FilesUtils;
use src\VatCodes\services\VatCodeService;

$service = new VatCodeService();

$vatNumbers = [];
if ($_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES['csvFile']['tmp_name'];

    try {
        $fileUtils = new FilesUtils();
        $vatNumbers = $fileUtils->loadVatNumbersFromCSV($tmpFilePath);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "File upload failed with error code: " . $_FILES['csv_file']['error'];
}

$invalidCodes = [];
$validCodes = [];
foreach ($vatNumbers as $vatNumber) {
    try {
        $service->processVatCode($vatNumber);
         $validCodes[] = $vatNumber;
    } catch (InvalidArgumentException $e) {
        $invalidCodes[$vatNumber] = $e->getMessage();
    }
}


$codes = $service->getVatCodes();

header('Content-Type: text/html; charset=UTF-8');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VAT Code Validation Result</title>
    <link rel="stylesheet" href="../public/assets/styles.css">
</head>
<body>
<h2>File Upload VAT Code Validation Results</h2>
<?php if (count($validCodes) > 0): ?>
    <div class="message success">
        <strong>Valid VAT codes</strong> <?php echo implode(', ', $validCodes); ?>
    </div>
<?php endif; ?>

<?php if (count($invalidCodes) > 0): ?>
    <div class="message error">
        <strong>Invalid VAT codes</strong>
        <?php foreach ($invalidCodes as $vatNumber => $errorMessage): ?>
            <div><?php echo $vatNumber . ': ' . $errorMessage; ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="title">Stored Vat Codes</div>
<div class="container">
    <table class="styled-table">
        <tr>
            <th>ID</th>
            <th>Vat Code</th>
        </tr>
        <?php foreach ($codes as $code): ?>
            <tr>
                <td><?php echo $code['id']; ?></td>
                <td><?php echo $code['vat']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<a href="../public/file-upload-validation.html">Go back to the form</a>
</body>
</html>