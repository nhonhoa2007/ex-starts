<?php
$db_recipients = [];
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mail_db;charset=utf8mb4', 'root', '');
    $stmt = $pdo->query("SELECT id, name, email FROM recipients WHERE status = 1");
    $db_recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=mail_db;charset=utf8mb4', 'nhonhoa', '');
        $stmt = $pdo->query("SELECT id, name, email FROM recipients WHERE status = 1");
        $db_recipients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e2) {
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài 3: Gửi email cho nhiều địa chỉ / CSDL SQL</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="mb-4 pb-2 border-bottom">
        <h2 class="text-primary mb-0">Bài 3: Gửi email cho nhiều địa chỉ (Danh sách / CSDL SQL)</h2>
    </div>

    <form action="send_bulk.php" enctype="multipart/form-data" method="POST">
        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        1. Lựa chọn nguồn danh sách người nhận
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Cách thức nhận danh sách email:</label>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="sourceManual" name="recipient_source" value="manual" class="custom-control-input" onclick="toggleSource('manual')">
                                <label class="custom-control-label" for="sourceManual">Nhập thủ công danh sách email (ngăn cách bởi dấu phẩy)</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="sourceDb" name="recipient_source" value="database" class="custom-control-input" checked onclick="toggleSource('database')">
                                <label class="custom-control-label" for="sourceDb">Lấy danh sách người nhận từ Cơ sở dữ liệu SQL (Bảng <code>recipients</code>)</label>
                            </div>
                        </div>

                        <div id="manualInputSection" class="form-group border p-3 rounded bg-light" style="display: none;">
                            <label class="font-weight-bold">Chuỗi danh sách email:</label>
                            <textarea class="form-control" name="recipients_string" rows="3" placeholder="email1@example.com, email2@example.com, email3@example.com">test1@test.com, test2@test.com, test3@test.com, ngolequanit@gmail.com</textarea>
                            <small class="form-text text-muted">Ví dụ: test1@test.com, test2@test.com, test3@test.com</small>
                        </div>

                        <div id="dbInputSection" class="form-group border p-3 rounded bg-light">
                            <label class="font-weight-bold">Chọn người nhận từ CSDL <code>mail_db</code>:</label>
                            <?php if (!empty($db_recipients)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered bg-white mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="40" class="text-center">
                                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" checked>
                                                </th>
                                                <th>Họ và Tên</th>
                                                <th>Địa chỉ Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($db_recipients as $r): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="selected_recipients[]" value="<?php echo htmlspecialchars($r['email'] . '|' . $r['name']); ?>" class="db-check" checked>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($r['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($r['email']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">Chưa tìm thấy bản ghi trong bảng <code>recipients</code>. Vui lòng chạy file <code>database.sql</code>.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white font-weight-bold">
                        2. Cấu hình kết nối SMTP
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold mb-1">SMTP Host:</label>
                                <input type="text" class="form-control" name="smtp_host" id="smtpHost" value="smtp.gmail.com">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="small font-weight-bold mb-1">Port:</label>
                                <input type="number" class="form-control" name="smtp_port" id="smtpPort" value="465">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="small font-weight-bold mb-1">Mã hóa:</label>
                                <select class="form-control" name="smtp_secure" id="smtpSecure">
                                    <option value="ssl" selected>SSL (465)</option>
                                    <option value="tls">TLS (587)</option>
                                    <option value="">Plain</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLocalMock()">
                            Chuyển sang Local Mock SMTP (127.0.0.1:1025)
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white font-weight-bold">
                        3. Soạn nội dung gửi hàng loạt
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề email:</label>
                            <input type="text" class="form-control" name="tieude" value="Thông báo quan trọng từ VKU" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nội dung thư (HTML):</label>
                            <textarea name="content" class="form-control" rows="6" required>Xin chào các bạn!<br>Đây là email thông báo tự động được gửi đồng loạt tới danh sách sinh viên qua hệ thống PHPMailer.</textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">File đính kèm (Tùy chọn):</label>
                            <input type="file" class="form-control-file border p-2 rounded w-100" name="file">
                        </div>

                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2 mt-4">
                            Gửi Email hàng loạt ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function toggleSource(mode) {
    if (mode === 'manual') {
        document.getElementById('manualInputSection').style.display = 'block';
        document.getElementById('dbInputSection').style.display = 'none';
    } else {
        document.getElementById('manualInputSection').style.display = 'none';
        document.getElementById('dbInputSection').style.display = 'block';
    }
}

function toggleSelectAll(master) {
    var checks = document.querySelectorAll('.db-check');
    for (var i = 0; i < checks.length; i++) {
        checks[i].checked = master.checked;
    }
}

function setLocalMock() {
    document.getElementById('smtpHost').value = '127.0.0.1';
    document.getElementById('smtpPort').value = '1025';
    document.getElementById('smtpSecure').value = '';
}
</script>
</body>
</html>
