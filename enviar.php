<?php
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';
$assunto = 'Contato do site';

if ($email === '' || $mensagem === '') {
    http_response_code(400);
    echo 'Preencha e-mail e mensagem.';
    exit;
}

$cfg = @include __DIR__ . '/config.php';
if (!is_array($cfg)) { $cfg = []; }
$to = !empty($cfg['SMTP_TO']) ? $cfg['SMTP_TO'] : (getenv('SMTP_TO') ?: 'fsgranjagaucho@gmail.com');

if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    $host = !empty($cfg['SMTP_HOST']) ? $cfg['SMTP_HOST'] : (getenv('SMTP_HOST') ?: 'smtp.gmail.com');
    $user = !empty($cfg['SMTP_USER']) ? $cfg['SMTP_USER'] : (getenv('SMTP_USER') ?: '');
    $pass = !empty($cfg['SMTP_PASS']) ? $cfg['SMTP_PASS'] : (getenv('SMTP_PASS') ?: '');
    $port = (int)(!empty($cfg['SMTP_PORT']) ? $cfg['SMTP_PORT'] : (getenv('SMTP_PORT') ?: 587));
    $secure = !empty($cfg['SMTP_SECURE']) ? $cfg['SMTP_SECURE'] : (getenv('SMTP_SECURE') ?: 'tls');

    $mailer = new PHPMailer\\PHPMailer\\PHPMailer(true);
    try {
        $mailer->isSMTP();
        $mailer->Host = $host;
        $mailer->SMTPAuth = true;
        $mailer->Username = $user;
        $mailer->Password = $pass;
        $mailer->Port = $port;
        $mailer->SMTPSecure = $secure;
        $mailer->setFrom($email !== '' ? $email : $user);
        $mailer->addAddress($to);
        $mailer->isHTML(true);
        $mailer->Subject = $assunto;
        $mailer->Body = '<strong>Nome:</strong> ' . ($nome !== '' ? htmlspecialchars($nome) : 'Não informado') . '<br>' .
                        '<strong>Email:</strong> ' . htmlspecialchars($email) . '<br>' .
                        '<strong>Mensagem:</strong><br>' . nl2br(htmlspecialchars($mensagem));
        $mailer->send();
        echo 'Email enviado com sucesso.';
        exit;
    } catch (Exception $e) {
    }
}

$headers = "MIME-Version: 1.0\r\n" .
           "Content-type: text/html; charset=UTF-8\r\n" .
           "From: {$email}\r\n" .
           "Reply-To: {$email}\r\n";
$body = "<strong>Nome:</strong> " . ($nome !== '' ? htmlspecialchars($nome) : 'Não informado') . "<br>" .
        "<strong>Email:</strong> " . htmlspecialchars($email) . "<br>" .
        "<strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($mensagem));
$sent = @mail($to, $assunto, $body, $headers);
if ($sent) {
    echo 'Email enviado com sucesso.';
} else {
    http_response_code(500);
    echo 'Email não enviado.';
}


