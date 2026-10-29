<?php
require_once "PHPMailer-master/src/PHPMailer.php";
require_once "PHPMailer-master/src/SMTP.php";
require_once 'PHPMailer-master/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet = "utf-8";

        $host = !empty($_POST['smtp_host']) ? trim($_POST['smtp_host']) : 'smtp.gmail.com';
        $port = !empty($_POST['smtp_port']) ? (int)$_POST['smtp_port'] : 465;
        $secure = isset($_POST['smtp_secure']) ? trim($_POST['smtp_secure']) : 'ssl';

        $nguoigui = 'ngolequanvh@gmail.com';
        $matkhau = 'asdfsgd';
        $tennguoigui = 'le quan';

        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $nguoigui;
        $mail->Password = $matkhau;
        $mail->SMTPSecure = $secure;
        $mail->Port = $port;
        $mail->setFrom($nguoigui, $tennguoigui);

        $to = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$to) {
            $to = isset($_POST['email']) ? trim($_POST['email']) : 'ngolequanit@gmail.com';
        }
        $to_name = "bạn";
        $tieude = isset($_POST['tieude']) ? trim($_POST['tieude']) : 'Không có tiêu đề';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $tieude;

        $noidungthu = '
        <div class="card" style="width: 22rem; font-family: Arial, sans-serif; border: 1px solid #ced4da; padding: 20px; border-radius: 8px;">
            <div class="card-body">
                <h4 class="card-title" style="color: #007bff;"><b>Xin chào ' . htmlspecialchars($to_name) . '</b></h4>
                <h6 class="card-subtitle mb-2" style="color: #6c757d;">Tiêu đề: ' . htmlspecialchars($tieude) . '</h6>
                <hr>
                <p class="card-text" style="font-size: 15px; line-height: 1.6; color: #212529;">' . nl2br(htmlspecialchars($content)) . '</p>
            </div>
        </div>';
        $mail->Body = $noidungthu;

        if (isset($_FILES['file']) && isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $uploadfile = tempnam(sys_get_temp_dir(), sha1($_FILES['file']['name']));
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $mail->addAttachment($uploadfile, $_FILES['file']['name']);
            }
        }

        $mail->smtpConnect(array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));

        if ($mail->send()) {
            header("Location: index.php?status=success");
            exit;
        }
    } catch (Exception $e) {
        $errorMsg = $mail->ErrorInfo ?: $e->getMessage();
        header("Location: index.php?error=" . urlencode($errorMsg));
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
