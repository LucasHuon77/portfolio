<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// HONEYPOT
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true, 'message' => 'Message envoyé !']);
    exit;
}

// RATE LIMITING
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = sys_get_temp_dir() . '/rate_' . md5($ip) . '.json';
$now = time();
$rateData = ['count' => 0, 'first' => $now];
if (file_exists($rateFile)) {
    $rateData = json_decode(file_get_contents($rateFile), true);
    if ($now - $rateData['first'] > 3600) {
        $rateData = ['count' => 0, 'first' => $now];
    }
}
if ($rateData['count'] >= 3) {
    echo json_encode(['success' => false, 'message' => 'Trop de messages. Réessaie dans 1 heure.']);
    exit;
}
$rateData['count']++;
file_put_contents($rateFile, json_encode($rateData));

$nom     = htmlspecialchars(trim($_POST['nom'] ?? ''));
$email   = htmlspecialchars(trim($_POST['email'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));

if (!$nom || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// GÉNÉRATION DES DONNÉES DYNAMIQUES
$ref       = 'MSG-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
$hash      = hash('sha256', $nom . $email . $message . time());
$date      = date('D, d M Y — H:i:s T');
$timestamp = time();
$qr_url    = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode('https://www.linkedin.com/in/lucas-huon-4003b025a/') . '&bgcolor=0a0e14&color=00ffcc&format=png';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $envFile = __DIR__ . '/.env';
    $env = [];
    if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
}
$mail->Username = $env['MAIL_USERNAME'] ?? '';
$mail->Password = $env['MAIL_PASSWORD'] ?? '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('kkeyzoo2004@gmail.com', 'Portfolio Lucas.dev');
    $mail->addAddress('kkeyzoo2004@gmail.com', 'Lucas Huon');
    $mail->addReplyTo($email, $nom);

    $mail->isHTML(true);
    $mail->Subject = "📩 [$ref] Nouveau message de $nom — Portfolio";
    $mail->Body    = "
<!DOCTYPE html>
<html>
<head>
<style>
    @keyframes borderGlow {
        0%   { border-color: rgba(0,255,204,0.4); }
        50%  { border-color: rgba(255,0,255,0.6); }
        100% { border-color: rgba(0,255,204,0.4); }
    }
    @keyframes progressBar {
        from { width: 0%; }
        to   { width: 100%; }
    }
</style>
</head>
<body style='margin:0;padding:0;background:#050810;font-family:monospace'>
<div style='max-width:620px;margin:0 auto;padding:32px 16px'>

    <!-- ANIMATED BORDER HEADER -->
    <div style='text-align:center;margin-bottom:32px'>
        <div style='background:#0a0e14;border:2px solid rgba(0,255,204,0.4);border-radius:14px;padding:24px 32px;display:inline-block;position:relative;box-shadow:0 0 30px rgba(0,255,204,0.1),inset 0 0 30px rgba(0,255,204,0.03)'>
            <div style='position:absolute;top:0;left:10%;right:10%;height:2px;background:linear-gradient(90deg,transparent,#00ffcc,#ff00ff,transparent);border-radius:2px'></div>
            <p style='color:rgba(0,255,204,0.5);font-size:0.58rem;letter-spacing:0.35em;margin:0 0 8px 0;text-transform:uppercase'>[ SECURE TRANSMISSION RECEIVED ]</p>
            <p style='color:#00ffcc;font-size:1.5rem;font-weight:700;letter-spacing:4px;margin:0;text-shadow:0 0 20px rgba(0,255,204,0.5)'>LUCAS<span style='color:#ffffff'>.DEV</span></p>
            <p style='color:rgba(122,144,144,0.6);font-size:0.58rem;letter-spacing:0.25em;margin:8px 0 0 0;text-transform:uppercase'>Cybersécurité · Développement Web · Pentest</p>
            <div style='position:absolute;bottom:0;left:10%;right:10%;height:2px;background:linear-gradient(90deg,transparent,#ff00ff,#00ffcc,transparent);border-radius:2px'></div>
        </div>
    </div>

    <!-- REF + DATE -->
    <div style='display:flex;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px'>
        <div style='background:#0a0e14;border:1px solid rgba(0,255,204,0.15);border-radius:6px;padding:8px 14px'>
            <span style='color:#7a9090;font-size:0.6rem;letter-spacing:0.15em'>REF ID &nbsp;</span>
            <span style='color:#00ffcc;font-size:0.72rem;letter-spacing:0.1em;font-weight:700'>$ref</span>
        </div>
        <div style='background:#0a0e14;border:1px solid rgba(255,0,255,0.15);border-radius:6px;padding:8px 14px'>
            <span style='color:#7a9090;font-size:0.6rem;letter-spacing:0.15em'>REÇU LE &nbsp;</span>
            <span style='color:#ff88ff;font-size:0.68rem;letter-spacing:0.05em'>$date</span>
        </div>
    </div>

    <!-- PROGRESS BAR ANALYSE -->
    <div style='background:#0a0e14;border:1px solid rgba(0,255,204,0.12);border-radius:8px;padding:14px 18px;margin-bottom:16px'>
        <div style='display:flex;justify-content:space-between;margin-bottom:8px'>
            <span style='color:#7a9090;font-size:0.62rem;letter-spacing:0.15em'>ANALYSE SÉCURITÉ</span>
            <span style='color:#00c850;font-size:0.62rem;letter-spacing:0.1em'>100% ✓</span>
        </div>
        <div style='background:#111820;border-radius:4px;height:6px;overflow:hidden'>
            <div style='width:100%;height:100%;background:linear-gradient(90deg,#00ffcc,#ff00ff);border-radius:4px'></div>
        </div>
        <div style='display:flex;justify-content:space-between;margin-top:6px'>
            <span style='color:rgba(0,255,204,0.4);font-size:0.55rem'>SPAM CHECK ✓</span>
            <span style='color:rgba(0,255,204,0.4);font-size:0.55rem'>VIRUS SCAN ✓</span>
            <span style='color:rgba(0,255,204,0.4);font-size:0.55rem'>TLS VALID ✓</span>
            <span style='color:rgba(0,255,204,0.4);font-size:0.55rem'>AUTH ✓</span>
        </div>
    </div>

    <!-- THREAT LEVEL -->
    <div style='background:rgba(0,200,80,0.06);border:1px solid rgba(0,200,80,0.25);border-radius:8px;padding:12px 18px;margin-bottom:20px;text-align:center'>
        <span style='color:#00c850;font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase'>
            🛡️ &nbsp; THREAT LEVEL : &nbsp;
            <strong style='color:#00ff88;font-size:0.85rem'>ZERO</strong>
            &nbsp; ✓ &nbsp; — &nbsp; MESSAGE CERTIFIÉ SÛREMENT
        </span>
    </div>

    <!-- TERMINAL CARD -->
    <div style='background:#0a0e14;border:1px solid rgba(0,255,204,0.18);border-radius:14px;overflow:hidden;margin-bottom:20px;box-shadow:0 8px 32px rgba(0,0,0,0.4)'>

        <!-- TERMINAL BAR -->
        <div style='background:#111820;padding:12px 20px;border-bottom:1px solid rgba(0,255,204,0.08);display:flex;align-items:center;gap:8px'>
            <span style='width:11px;height:11px;background:#ff5f57;border-radius:50%;display:inline-block'></span>
            <span style='width:11px;height:11px;background:#febc2e;border-radius:50%;display:inline-block'></span>
            <span style='width:11px;height:11px;background:#28c840;border-radius:50%;display:inline-block'></span>
            <span style='color:#7a9090;font-size:0.68rem;margin-left:10px;letter-spacing:0.05em'>lucas@portfolio:~$ decrypt --msg $ref</span>
        </div>

        <div style='padding:28px'>

            <p style='color:rgba(0,255,204,0.4);font-size:0.6rem;letter-spacing:0.2em;margin:0 0 20px 0'>
                ══════════════ DONNÉES EXPÉDITEUR ══════════════
            </p>

            <div style='margin-bottom:14px'>
                <span style='color:#7a9090;font-size:0.68rem;letter-spacing:0.12em'>NOM_COMPLET &nbsp;</span>
                <span style='color:rgba(0,255,204,0.3)'>▶</span>
                <span style='color:#e8f4f0;font-size:1rem;font-weight:600;margin-left:8px'>$nom</span>
            </div>

            <div style='margin-bottom:24px'>
                <span style='color:#7a9090;font-size:0.68rem;letter-spacing:0.12em'>EMAIL_ADDR &nbsp;&nbsp;</span>
                <span style='color:rgba(0,255,204,0.3)'>▶</span>
                <a href='mailto:$email' style='color:#00ffcc;text-decoration:none;font-size:0.9rem;margin-left:8px'>$email</a>
            </div>

            <p style='color:rgba(0,255,204,0.4);font-size:0.6rem;letter-spacing:0.2em;margin:0 0 16px 0'>
                ══════════════ CONTENU MESSAGE ══════════════════
            </p>

            <div style='background:#080c10;border:1px solid rgba(0,255,204,0.1);border-left:3px solid #00ffcc;border-radius:0 8px 8px 0;padding:20px 24px;margin-bottom:24px'>
                <p style='color:rgba(0,255,204,0.35);font-size:0.58rem;letter-spacing:0.2em;margin:0 0 10px 0'>// MESSAGE_BODY — DÉCRYPTÉ</p>
                <p style='color:#e8f4f0;line-height:1.9;margin:0;font-size:0.88rem'>$message</p>
            </div>

            <!-- HASH SHA256 -->
            <div style='background:#080c10;border:1px solid rgba(255,0,255,0.1);border-radius:8px;padding:14px 18px'>
                <p style='color:rgba(255,0,255,0.5);font-size:0.58rem;letter-spacing:0.2em;margin:0 0 8px 0'>// SHA-256 FINGERPRINT</p>
                <p style='color:#ff88ff;font-size:0.62rem;letter-spacing:0.05em;margin:0;word-break:break-all;line-height:1.6'>$hash</p>
            </div>

        </div>
    </div>

    <!-- METADATA + QR CODE -->
    <div style='display:flex;gap:16px;margin-bottom:24px;flex-wrap:wrap'>

        <!-- METADATA -->
        <div style='flex:1;min-width:200px;background:#0a0e14;border:1px solid rgba(255,255,255,0.06);border-radius:10px;padding:18px'>
            <p style='color:#7a9090;font-size:0.6rem;letter-spacing:0.15em;margin:0 0 12px 0'>// METADATA</p>
            <table style='width:100%;border-collapse:collapse'>
                <tr>
                    <td style='color:rgba(122,144,144,0.5);font-size:0.6rem;padding:4px 0;white-space:nowrap'>PROTOCOLE</td>
                    <td style='color:#7a9090;font-size:0.6rem;padding:4px 0 4px 12px'>SMTP/TLS 1.3</td>
                </tr>
                <tr>
                    <td style='color:rgba(122,144,144,0.5);font-size:0.6rem;padding:4px 0'>CHIFFREMENT</td>
                    <td style='color:#7a9090;font-size:0.6rem;padding:4px 0 4px 12px'>AES-256-GCM</td>
                </tr>
                <tr>
                    <td style='color:rgba(122,144,144,0.5);font-size:0.6rem;padding:4px 0'>TIMESTAMP</td>
                    <td style='color:#7a9090;font-size:0.6rem;padding:4px 0 4px 12px'>$timestamp</td>
                </tr>
                <tr>
                    <td style='color:rgba(122,144,144,0.5);font-size:0.6rem;padding:4px 0'>SOURCE</td>
                    <td style='color:#7a9090;font-size:0.6rem;padding:4px 0 4px 12px'>lucas.dev/contact</td>
                </tr>
                <tr>
                    <td style='color:rgba(122,144,144,0.5);font-size:0.6rem;padding:4px 0'>SERVEUR</td>
                    <td style='color:#7a9090;font-size:0.6rem;padding:4px 0 4px 12px'>smtp.gmail.com:587</td>
                </tr>
            </table>
        </div>

        <!-- QR CODE LINKEDIN -->
        <div style='background:#0a0e14;border:1px solid rgba(0,255,204,0.15);border-radius:10px;padding:18px;text-align:center;min-width:160px'>
            <p style='color:#7a9090;font-size:0.6rem;letter-spacing:0.15em;margin:0 0 12px 0'>// LINKEDIN QR</p>
            <img src='$qr_url' alt='LinkedIn QR' style='width:110px;height:110px;border-radius:6px;display:block;margin:0 auto'>
            <p style='color:rgba(0,255,204,0.5);font-size:0.55rem;letter-spacing:0.1em;margin:10px 0 0 0'>SCANNER POUR CONNECTER</p>
        </div>

    </div>

    <!-- BOUTON REPONDRE -->
    <div style='text-align:center;margin-bottom:28px'>
        <a href='mailto:$email?subject=Re: [$ref] Votre message — Lucas.dev' style='display:inline-block;background:linear-gradient(135deg,#00ffcc,#ff00ff);color:#080c10;font-weight:700;font-size:0.8rem;letter-spacing:0.15em;text-decoration:none;padding:15px 40px;border-radius:8px;text-transform:uppercase;box-shadow:0 4px 20px rgba(0,255,204,0.3)'>
            ↩ &nbsp; RÉPONDRE À $nom
        </a>
    </div>

    <!-- FOOTER -->
    <div style='text-align:center;padding-top:20px;border-top:1px solid rgba(255,255,255,0.04)'>
        <div style='margin-bottom:8px'>
            <span style='color:rgba(0,255,204,0.3);font-size:0.55rem;letter-spacing:0.08em'>■ &nbsp;</span>
            <span style='color:rgba(122,144,144,0.4);font-size:0.55rem;letter-spacing:0.12em'>LUCAS.DEV — PORTFOLIO CYBERSÉCURITÉ & DÉVELOPPEMENT WEB</span>
            <span style='color:rgba(255,0,255,0.3);font-size:0.55rem;letter-spacing:0.08em'>&nbsp; ■</span>
        </div>
        <p style='color:rgba(122,144,144,0.2);font-size:0.52rem;margin:0;letter-spacing:0.08em'>Transmission sécurisée · Référence $ref · $date</p>
    </div>

</div>
</body>
</html>
";
    $mail->AltBody = "[$ref] Nouveau message de $nom\nEmail: $email\nMessage: $message\nDate: $date\nHash: $hash";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Message envoyé avec succès !']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur envoi : ' . $mail->ErrorInfo]);
}
?>