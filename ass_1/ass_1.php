<?php
// طريقة التشفير
$method = "AES-256-CBC";
$key = openssl_random_pseudo_bytes(32);
$iv_length = openssl_cipher_iv_length($method);
$iv = openssl_random_pseudo_bytes($iv_length);

// سيشن لحفظ البيانات
session_start();
if (!isset($_SESSION['key'])) {
    $_SESSION['key'] = base64_encode($key);
    $_SESSION['iv'] = base64_encode($iv);
} else {
    $key = base64_decode($_SESSION['key']);
    $iv = base64_decode($_SESSION['iv']);
}

$encrypted_result = "";
$decrypted_result = "";
$plaintext = "";

if (isset($_POST['encrypt'])) {
    $plaintext = $_POST['plaintext'];
    
    if (!empty($plaintext)) {
        $encrypted = openssl_encrypt($plaintext, $method, $key, 0, $iv);
        $encrypted_result = $encrypted;
    }
}

if (isset($_POST['decrypt'])) {
    $plaintext = $_POST['plaintext'];
    
    if (!empty($plaintext)) {
        $decrypted = openssl_decrypt($plaintext, $method, $key, 0, $iv);
        
        if ($decrypted !== false) {
            $decrypted_result = $decrypted;
        } else {
            $decrypted_result = "Error: decoding failed make sure you enter the correct ciphertext.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encryption system</title>
    <link rel="stylesheet" href="ass_1.css">
</head>

<body>
    <div class="container">
        <h1> Encryption system</h1>
        <div class="info">
            <p><strong>method:</strong> AES-256-CBC</p>
            <p><strong>key:</strong> 256 bits</p>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="plaintext">:Enter the text</label>
                <textarea 
                    name="plaintext" 
                    id="plaintext" 
                    rows="6" 
                    placeholder="...Enter the text you want to encrypt"><?php echo htmlspecialchars($plaintext); ?></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit" name="encrypt" class="encrypt-btn"> Encrypt</button>
                <button type="submit" name="decrypt" class="decrypt-btn">Decrypt</button>
            </div>
        </form>
        
        <?php if (!empty($encrypted_result)): ?>
        <div class="result">
            <h3>:result</h3>
            <div class="result-content">
                <?php echo htmlspecialchars($encrypted_result); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($decrypted_result)): ?>
        <div class="result">
            <h3> :result</h3>
            <div class="result-content">
                <?php echo htmlspecialchars($decrypted_result); ?>
            </div>
        </div>
        <?php endif; ?>
        
    </div>
</body>
</html>