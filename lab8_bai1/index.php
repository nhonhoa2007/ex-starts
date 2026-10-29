<?php
require_once "PHPMailer-master/src/PHPMailer.php";
require_once "PHPMailer-master/src/SMTP.php";
require_once 'PHPMailer-master/src/Exception.php';

$message = '';
$status = '';
$debugLog = '';

$current_host = $_POST['host'] ?? 'smtp.gmail.com';
$current_port = $_POST['port'] ?? 465;
$current_secure = $_POST['secure'] ?? 'ssl';
$current_user = $_POST['username'] ?? '';
$current_pass = $_POST['password'] ?? '';
$current_sender_name = $_POST['sender_name'] ?? 'Ngô Lê Quân';
$current_to = $_POST['receiver_email'] ?? '';
$current_to_name = $_POST['receiver_name'] ?? 'lequan';

if (isset($_POST['send']) || (isset($_GET['action']) && in_array($_GET['action'], ['send', 'test_send']))) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet = "utf-8";

        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) use (&$debugLog) {
            $debugLog .= htmlspecialchars($str) . "\n";
        };

        $isTest = (isset($_GET['action']) && $_GET['action'] === 'test_send');
        $host = $isTest ? '127.0.0.1' : trim($current_host);
        $port = $isTest ? 1025 : (int)$current_port;
        $secure = $isTest ? '' : trim($current_secure);
        $nguoigui = $isTest ? 'test@example.com' : trim($current_user);
        $matkhau = $isTest ? 'password' : str_replace(' ', '', trim($current_pass));
        $tennguoigui = trim($current_sender_name);
        $to = $isTest ? 'test@example.com' : trim($current_to);
        $to_name = trim($current_to_name);

        if (empty($nguoigui) || empty($matkhau)) {
            throw new Exception("Vui lòng điền đầy đủ Email và Mật khẩu ứng dụng của bạn!");
        }

        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $nguoigui;
        $mail->Password = $matkhau;
        $mail->SMTPSecure = $secure;
        $mail->Port = $port;
        $mail->setFrom($nguoigui, $tennguoigui);

        $mail->addAddress($to, $to_name);
        $mail->isHTML(true);

        $mail->Subject = 'Gửi thư từ php';
        $noidungthu = "<b>Chào bạn!</b><br>Chúc an lành!";
        $mail->Body = $noidungthu;

        if (file_exists("4.jpg")) {
            $mail->addAttachment("4.jpg", "picture.jpg");
        }

        $mail->smtpConnect(array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));

        $mail->send();
        $message = "Đã gửi mail xong thành công đến: " . htmlspecialchars($to);
        $status = 'success';
    } catch (Exception $e) {
        $errorDetail = $mail->ErrorInfo ?: $e->getMessage();
        $advice = '';
        if (str_contains($errorDetail, 'Could not authenticate')) {
            $advice = '<br><div class="mt-2 text-dark"><strong>Nguyên nhân thường gặp:</strong><br>'
                . '1. Bạn nhập mật khẩu đăng nhập Gmail thông thường (Google đã chặn tính năng này từ 2022). Bắt buộc phải dùng <strong>Mật khẩu ứng dụng (App Password) 16 chữ cái</strong>.<br>'
                . '2. Tài khoản Google của bạn chưa bật <strong>Xác minh 2 bước (2-Step Verification)</strong> (bắt buộc bật thì mới tạo được Mật khẩu ứng dụng).<br>'
                . '3. Nhập nhầm tài khoản hoặc mật khẩu ứng dụng vừa tạo.</div>';
        } else if (str_contains($errorDetail, 'Could not connect to SMTP host') || str_contains($errorDetail, 'timed out')) {
            $advice = '<br><div class="mt-2 text-dark"><strong>Nguyên nhân kết nối:</strong> Cổng 465 đang bị chặn hoặc nghẽn. Hãy thử đổi sang <strong>Port: 587</strong> và <strong>Mã hóa: TLS</strong>.</div>';
        }

        $message = 'Mail không gửi được. Lỗi: <strong>' . htmlspecialchars($errorDetail) . '</strong>' . $advice;
        $status = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài 1: Gửi email cố định theo địa chỉ cho trước</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; }
        pre.debug-log { background: #212529; color: #38ef7d; padding: 12px; border-radius: 6px; font-size: 12px; max-height: 250px; overflow-y: auto; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="mb-4 pb-2 border-bottom">
        <h2 class="text-primary mb-0">Bài 1: Gửi email cố định theo địa chỉ cho trước</h2>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $status; ?> alert-dismissible fade show" role="alert">
            <h5 class="alert-heading font-weight-bold"><?php echo ($status === 'success') ? 'Thành công!' : 'Thông báo lỗi chi tiết:'; ?></h5>
            <div><?php echo $message; ?></div>
            <?php if (!empty($debugLog) && $status === 'danger'): ?>
                <hr>
                <p class="mb-1 font-weight-bold text-dark">Nhật ký kết nối SMTP từ Google (Debug Log):</p>
                <pre class="debug-log"><?php echo $debugLog; ?></pre>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">
                    Cấu hình và thực thi gửi email
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold">SMTP Host:</label>
                                <input type="text" class="form-control" name="host" id="hostInput" value="<?php echo htmlspecialchars($current_host); ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Port:</label>
                                <input type="number" class="form-control" name="port" id="portInput" value="<?php echo htmlspecialchars((string)$current_port); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mã hóa (SMTPSecure):</label>
                            <select class="form-control" name="secure" id="secureInput" onchange="autoAdjustPort()">
                                <option value="ssl" <?php echo ($current_secure === 'ssl') ? 'selected' : ''; ?>>SSL (Port 465)</option>
                                <option value="tls" <?php echo ($current_secure === 'tls') ? 'selected' : ''; ?>>TLS (Port 587)</option>
                                <option value="" <?php echo ($current_secure === '') ? 'selected' : ''; ?>>Không mã hóa (Plain / Local)</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-danger">Email người gửi (Gmail của bạn):</label>
                                <input type="email" class="form-control border-danger" name="username" id="userInput" placeholder="ví dụ: your_email@gmail.com" value="<?php echo htmlspecialchars($current_user); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-danger">Mật khẩu ứng dụng 16 chữ cái:</label>
                                <input type="text" class="form-control border-danger" name="password" id="passInput" placeholder="16 ký tự, ví dụ: abcd efgh ijkl mnop" value="<?php echo htmlspecialchars($current_pass); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tên hiển thị người gửi:</label>
                            <input type="text" class="form-control" name="sender_name" value="<?php echo htmlspecialchars($current_sender_name); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Địa chỉ email người nhận ($to):</label>
                                <input type="email" class="form-control" name="receiver_email" placeholder="Nhập email bạn muốn nhận thư" value="<?php echo htmlspecialchars($current_to); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Tên người nhận ($to_name):</label>
                                <input type="text" class="form-control" name="receiver_name" value="<?php echo htmlspecialchars($current_to_name); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">File đính kèm:</label>
                            <input type="text" class="form-control" value="4.jpg (Đã đính kèm sẵn trong thư mục)" readonly>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" name="send" class="btn btn-success px-4 font-weight-bold">
                                Thực hiện gửi email ngay
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="setLocalMock()">
                                Dùng cấu hình kiểm thử (Local SMTP)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    Hướng dẫn tạo Mật khẩu ứng dụng Gmail (App Password)
                </div>
                <div class="card-body small">
                    <ol class="pl-3 mb-2">
                        <li>Truy cập trực tiếp: <a href="https://myaccount.google.com/apppasswords" target="_blank" class="font-weight-bold">myaccount.google.com/apppasswords</a>.</li>
                        <li>(Lưu ý: Tài khoản bắt buộc phải đang <strong>BẬT Xác minh 2 bước</strong>).</li>
                        <li>Nhập tên ứng dụng (ví dụ: <code>PHP</code>) rồi bấm <strong>Tạo</strong>.</li>
                        <li>Google sẽ cung cấp một mã <strong>16 ký tự</strong>. Copy dán vào ô <strong>Mật khẩu ứng dụng</strong> bên trái (hệ thống tự động xóa dấu cách cho bạn).</li>
                    </ol>
                    <div class="alert alert-info py-2 mb-0">
                        <strong>Mẹo:</strong> Nếu cổng 465 (SSL) bị lỗi mạng hoặc timeout, hãy chuyển sang <strong>Port: 587</strong> và <strong>Mã hóa: TLS</strong>!
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info text-white font-weight-bold">
                    Thông tin thư gửi
                </div>
                <div class="card-body">
                    <p><strong>Tiêu đề:</strong> Gửi thư từ php</p>
                    <p><strong>Nội dung HTML:</strong></p>
                    <div class="p-3 bg-light border rounded">
                        <b>Chào bạn!</b><br>Chúc an lành!
                    </div>
                    <p class="mt-3 mb-1"><strong>Ảnh đính kèm (4.jpg):</strong></p>
                    <?php if (file_exists("4.jpg")): ?>
                        <img src="4.jpg" alt="Attachment" class="img-thumbnail" style="max-height: 120px;">
                    <?php else: ?>
                        <span class="text-danger">Chưa có file 4.jpg</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function autoAdjustPort() {
    var secure = document.getElementById('secureInput').value;
    if (secure === 'ssl') {
        document.getElementById('portInput').value = '465';
    } else if (secure === 'tls') {
        document.getElementById('portInput').value = '587';
    } else {
        document.getElementById('portInput').value = '1025';
    }
}

function setLocalMock() {
    document.getElementById('hostInput').value = '127.0.0.1';
    document.getElementById('portInput').value = '1025';
    document.getElementById('secureInput').value = '';
    document.getElementById('userInput').value = 'test@example.com';
    document.getElementById('passInput').value = 'password';
    document.getElementById('receiver_email').value = 'test@example.com';
}
</script>
</body>
</html>
