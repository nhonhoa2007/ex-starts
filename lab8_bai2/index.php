<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài 2: Form gửi email tuỳ chỉnh</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="mb-4 pb-2 border-bottom">
        <h2 class="text-primary mb-0">Bài 2: Tạo Form gửi email tuỳ chỉnh</h2>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> Email của bạn đã được gửi đi thành công qua PHPMailer.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi gửi mail:</strong> <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white font-weight-bold">
                    Soạn thư và đính kèm tệp
                </div>
                <div class="card-body p-4">
                    <form action="mail.php" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Địa chỉ Email người nhận:</label>
                            <input type="email" class="form-control" name="email" placeholder="Nhập địa chỉ email người nhận (ví dụ: ngolequanit@gmail.com)" value="ngolequanit@gmail.com" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề email:</label>
                            <input type="text" class="form-control" name="tieude" placeholder="Nhập tiêu đề thư" value="Thư liên hệ từ Form PHP" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nội dung thư:</label>
                            <textarea name="content" id="editor" class="form-control" rows="5" placeholder="Nhập nội dung thư tại đây...">Xin chào thầy và các bạn! Đây là nội dung kiểm thử gửi mail từ form PHP của Bài 2 Lab 8.</textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tệp đính kèm (File):</label>
                            <input type="file" class="form-control-file border p-2 rounded w-100" name="file">
                        </div>

                        <div class="form-row mt-3 p-3 bg-light rounded border">
                            <div class="col-12 mb-2 font-weight-bold text-muted small">Cấu hình máy chủ SMTP (Tùy chọn):</div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="small mb-1">SMTP Host:</label>
                                <input type="text" class="form-control form-control-sm" name="smtp_host" id="smtpHost" value="smtp.gmail.com">
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <label class="small mb-1">Port:</label>
                                <input type="number" class="form-control form-control-sm" name="smtp_port" id="smtpPort" value="465">
                            </div>
                            <div class="col-md-3 form-group mb-2">
                                <label class="small mb-1">Mã hóa:</label>
                                <select class="form-control form-control-sm" name="smtp_secure" id="smtpSecure">
                                    <option value="ssl" selected>SSL (465)</option>
                                    <option value="tls">TLS (587)</option>
                                    <option value="">Plain</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold">Gửi Email</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="useLocalTestServer()">
                                Thiết lập Local Mock SMTP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function useLocalTestServer() {
    document.getElementById('smtpHost').value = '127.0.0.1';
    document.getElementById('smtpPort').value = '1025';
    document.getElementById('smtpSecure').value = '';
}
</script>
</body>
</html>
