<?php

require_once __DIR__ . '/../vendor/autoload.php';

use src\services\VatCodeService;

$singleInput = isset($_POST["singleInput"]) ? $_POST["singleInput"] : '';
$service = new VatCodeService();

$isValid = false;
$error = '';
try {
    $isValid = $service->processVatCode($singleInput);
} catch (InvalidArgumentException $e) {
    $error = "Invalid input: " . $e->getMessage();
}

$safeInput = htmlspecialchars($singleInput, ENT_QUOTES, 'UTF-8');
$codes = $service->getVatCodes();

header('Content-Type: text/html; charset=UTF-8');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../public/styles.css">
    <title>VAT Code Validation Result</title>
</head>
<body>
<div class="title">Single VAT Code Validation</div>

<?php if ($isValid): ?>
    <div class="message success">
        <strong>Valid VAT code</strong> <?php echo $safeInput; ?>
    </div>
<?php else: ?>
    <div class="message error">
        <strong>Very bad!</strong>  -> <?php echo $error; ?><br>
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

<a href="../public/single-input-validation.html">Go back to the form</a>

</body>
</html>