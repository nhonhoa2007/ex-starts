<?php
require_once "PHPMailer-master/src/PHPMailer.php";
require_once "PHPMailer-master/src/SMTP.php";
require_once 'PHPMailer-master/src/Exception.php';

$results = [];
$status = 'secondary';
$overall_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_GET['action']) && $_GET['action'] === 'demo')) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet = "utf-8";

        $isDemo = (isset($_GET['action']) && $_GET['action'] === 'demo');
        $host = $isDemo ? '127.0.0.1' : (!empty($_POST['smtp_host']) ? trim($_POST['smtp_host']) : 'smtp.gmail.com');
        $port = $isDemo ? 1025 : (!empty($_POST['smtp_port']) ? (int)$_POST['smtp_port'] : 465);
        $secure = $isDemo ? '' : (isset($_POST['smtp_secure']) ? trim($_POST['smtp_secure']) : 'ssl');

        $nguoigui = 'ngolequanvh@gmail.com';
        $matkhau = 'wkle vdxt bqcr zxtz';
        $tennguoigui = 'Nhập tên người gửi';

        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $nguoigui;
        $mail->Password = $matkhau;
        $mail->SMTPSecure = $secure;
        $mail->Port = $port;
        $mail->setFrom($nguoigui, $tennguoigui);

        $tieude = !empty($_POST['tieude']) ? trim($_POST['tieude']) : 'Thông báo thực hành Lab 8 - Gửi hàng loạt';
        $content = !empty($_POST['content']) ? $_POST['content'] : 'Nội dung thư kiểm thử gửi hàng loạt tới danh sách sinh viên';
        $mail->Subject = $tieude;
        $mail->isHTML(true);
        $mail->Body = $content;

        $target_recipients = [];
        $source = $isDemo ? 'database' : ($_POST['recipient_source'] ?? 'manual');

        if ($source === 'database') {
            if ($isDemo) {
                $target_recipients = [
                    'ngolequanit@gmail.com' => 'Ngô Lê Quân',
                    'nguyenvana@gmail.com' => 'Nguyễn Văn A',
                    'tranthib@gmail.com' => 'Trần Thị B',
                    'levanc@gmail.com' => 'Lê Văn C'
                ];
            } else if (!empty($_POST['selected_recipients'])) {
                foreach ($_POST['selected_recipients'] as $item) {
                    $parts = explode('|', $item);
                    $email = trim($parts[0]);
                    $name = isset($parts[1]) ? trim($parts[1]) : 'Bạn';
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $target_recipients[$email] = $name;
                    }
                }
            }
        } else {
            $recipients_str = !empty($_POST['recipients_string']) ? $_POST['recipients_string'] : "test1@test.com,test2@test.com,test3@test.com";
            $email_array = explode(",", $recipients_str);
            foreach ($email_array as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $target_recipients[$email] = "Sinh viên";
                }
            }
        }

        if (empty($target_recipients)) {
            throw new Exception("Không có địa chỉ email hợp lệ nào trong danh sách!");
        }

        foreach ($target_recipients as $to_email => $to_name) {
            $mail->addAddress($to_email, $to_name);
        }

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

        $mail->send();
        $status = 'success';
        $overall_message = 'Đã gửi mail hàng loạt xong cho ' . count($target_recipients) . ' người nhận!';

        foreach ($target_recipients as $to_email => $to_name) {
            $results[] = [
                'email' => $to_email,
                'name' => $to_name,
                'status' => 'Thành công',
                'class' => 'success'
            ];
        }
    } catch (Exception $e) {
        $status = 'danger';
        $overall_message = 'Mail không gửi được. Lỗi: ' . ($mail->ErrorInfo ?: $e->getMessage());
        if (!empty($target_recipients)) {
            foreach ($target_recipients as $to_email => $to_name) {
                $results[] = [
                    'email' => $to_email,
                    'name' => $to_name,
                    'status' => 'Thất bại',
                    'class' => 'danger'
                ];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kết quả gửi email hàng loạt</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="text-primary mb-0">Kết quả thực thi Bài 3 - Gửi email hàng loạt</h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">Quay lại Form</a>
    </div>

    <div class="alert alert-<?php echo $status; ?> alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><?php echo ($status === 'success') ? 'Thành công!' : 'Có lỗi phát sinh!'; ?></h4>
        <p class="mb-0"><?php echo htmlspecialchars($overall_message); ?></p>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white font-weight-bold">
            Danh sách người nhận và trạng thái xử lý
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>Địa chỉ Email</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)): ?>
                            <?php $i = 1; foreach ($results as $res): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($res['name']); ?></td>
                                    <td><?php echo htmlspecialchars($res['email']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $res['class']; ?> p-2">
                                            <?php echo htmlspecialchars($res['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Không có thông tin người nhận.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <a href="index.php" class="btn btn-primary font-weight-bold">Thực hiện lượt gửi mới</a>
    </div>
</div>
</body>
</html>
