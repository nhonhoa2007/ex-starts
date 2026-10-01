import test from 'node:test';
import assert from 'node:assert/strict';
import { spawn } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';

const PROJECT_DIR = '/home/nhonhoa/year2/php-web/baitapCNWEB/bai7_assignment1';
const PORT = 8995;
const BASE_URL = `http://127.0.0.1:${PORT}`;

let phpProcess;

test.before(async () => {
    // Start PHP local server
    phpProcess = spawn('php', ['-S', `127.0.0.1:${PORT}`, '-t', PROJECT_DIR], {
        stdio: 'ignore'
    });
    // Wait for server to start
    await new Promise((resolve) => setTimeout(resolve, 800));
});

test.after(() => {
    if (phpProcess) {
        phpProcess.kill('SIGTERM');
    }
});

test('1. GET /index.php redirects to admin/theloai.php', async () => {
    const res = await fetch(`${BASE_URL}/index.php`, { redirect: 'manual' });
    assert.strictEqual(res.status, 302);
    const location = res.headers.get('location');
    assert.ok(location.includes('admin/theloai.php'));
});

test('2. GET /admin/theloai.php displays initial seeded categories', async () => {
    const res = await fetch(`${BASE_URL}/admin/theloai.php`);
    assert.strictEqual(res.status, 200);
    const html = await res.text();
    assert.ok(html.includes('Quan Ly The Loai'));
    assert.ok(html.includes('Giải trí'));
    assert.ok(html.includes('Pháp luật'));
    assert.ok(html.includes('Văn Hóa'));
    assert.ok(html.includes('Xã hội'));
    assert.ok(html.includes('theloai_them.php'));
    assert.ok(html.includes('theloai_sua.php?idTL='));
});

test('3. GET /admin/theloai_them.php renders creation form', async () => {
    const res = await fetch(`${BASE_URL}/admin/theloai_them.php`);
    assert.strictEqual(res.status, 200);
    const html = await res.text();
    assert.ok(html.includes('THEM THE LOAI'));
    assert.ok(html.includes('theloai_them_xl.php'));
    assert.ok(html.includes('name="TenTL"'));
    assert.ok(html.includes('name="ThuTu"'));
    assert.ok(html.includes('name="AnHien"'));
});

test('4. POST /admin/theloai_them_xl.php creates a new category with icon upload', async () => {
    const testIconName = 'test_khoahoc.png';
    const fakeImageBuffer = Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'base64');

    const boundary = '----WebKitFormBoundary7MA4YWxkTrZu0gW';
    let body = '';
    body += `--${boundary}\r\nContent-Disposition: form-data; name="TenTL"\r\n\r\nKhoa Hoc Vu Tru\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="ThuTu"\r\n\r\n55\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="AnHien"\r\n\r\n1\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="image"; filename="${testIconName}"\r\nContent-Type: image/png\r\n\r\n`;

    const payload = Buffer.concat([
        Buffer.from(body, 'utf-8'),
        fakeImageBuffer,
        Buffer.from(`\r\n--${boundary}--\r\n`, 'utf-8')
    ]);

    const res = await fetch(`${BASE_URL}/admin/theloai_them_xl.php`, {
        method: 'POST',
        headers: {
            'Content-Type': `multipart/form-data; boundary=${boundary}`
        },
        body: payload
    });

    assert.strictEqual(res.status, 200);
    const html = await res.text();
    assert.ok(html.includes("Them thanh cong"));

    // Verify uploaded file in image/
    const uploadedFilePath = path.join(PROJECT_DIR, 'image', testIconName);
    assert.ok(fs.existsSync(uploadedFilePath), 'Uploaded icon file must exist in image/');

    // Verify listing contains newly created category
    const listRes = await fetch(`${BASE_URL}/admin/theloai.php`);
    const listHtml = await listRes.text();
    assert.ok(listHtml.includes('Khoa Hoc Vu Tru'));
    assert.ok(listHtml.includes(testIconName));
});

test('5. GET & POST /admin/theloai_sua.php updates category details', async () => {
    // Find ID of Khoa Hoc Vu Tru from listing
    const listRes = await fetch(`${BASE_URL}/admin/theloai.php`);
    const listHtml = await listRes.text();
    const match = listHtml.match(/theloai_sua\.php\?idTL=(\d+)[^>]*>Sua<\/a>\s*<\/td>\s*<td>\s*<a href="theloai_xoa\.php\?idTL=\1[^>]*>[^<]*<\/a><\/td>\s*<\/tr>\s*<\/table>/) 
      || listHtml.match(/href="theloai_sua\.php\?idTL=(\d+)">Sua<\/a><\/td>\s*<td><a href="theloai_xoa\.php\?idTL=\1/g);

    // Extract all matches
    const allIds = [...listHtml.matchAll(/theloai_sua\.php\?idTL=(\d+)/g)].map(m => m[1]);
    const targetId = allIds[allIds.length - 1]; // last created

    // Check GET
    const getRes = await fetch(`${BASE_URL}/admin/theloai_sua.php?idTL=${targetId}`);
    assert.strictEqual(getRes.status, 200);
    const getHtml = await getRes.text();
    assert.ok(getHtml.includes('Khoa Hoc Vu Tru'));

    // Check POST update
    const boundary = '----WebKitFormBoundaryUpdate123';
    let body = '';
    body += `--${boundary}\r\nContent-Disposition: form-data; name="Sua"\r\n\r\nSua\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="idTL"\r\n\r\n${targetId}\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="TenTL"\r\n\r\nKhoa Hoc Cong Nghe\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="ThuTu"\r\n\r\n88\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="AnHien"\r\n\r\n0\r\n`;
    body += `--${boundary}\r\nContent-Disposition: form-data; name="ten_anh"\r\n\r\ntest_khoahoc.png\r\n`;
    body += `--${boundary}--\r\n`;

    const updateRes = await fetch(`${BASE_URL}/admin/theloai_sua.php?idTL=${targetId}`, {
        method: 'POST',
        headers: {
            'Content-Type': `multipart/form-data; boundary=${boundary}`
        },
        body: Buffer.from(body, 'utf-8')
    });
    assert.strictEqual(updateRes.status, 200);
    const updateHtml = await updateRes.text();
    assert.ok(updateHtml.includes("Sua thanh cong"));

    // Verify change in listing
    const checkRes = await fetch(`${BASE_URL}/admin/theloai.php`);
    const checkHtml = await checkRes.text();
    assert.ok(checkHtml.includes('Khoa Hoc Cong Nghe'));
    assert.ok(checkHtml.includes('88'));
});

test('6. GET /admin/theloai_xoa.php deletes category and removes icon file', async () => {
    // Find ID of Khoa Hoc Cong Nghe
    const listRes = await fetch(`${BASE_URL}/admin/theloai.php`);
    const listHtml = await listRes.text();
    const allIds = [...listHtml.matchAll(/theloai_sua\.php\?idTL=(\d+)/g)].map(m => m[1]);
    const targetId = allIds[allIds.length - 1];

    const delRes = await fetch(`${BASE_URL}/admin/theloai_xoa.php?idTL=${targetId}`);
    assert.strictEqual(delRes.status, 200);
    const delHtml = await delRes.text();
    assert.ok(delHtml.includes('Xoa thanh cong'));

    // Verify unlinked file
    const testIconName = 'test_khoahoc.png';
    const uploadedFilePath = path.join(PROJECT_DIR, 'image', testIconName);
    assert.ok(!fs.existsSync(uploadedFilePath), 'Icon file should be unlinked upon record deletion');

    // Verify removed from listing
    const finalListRes = await fetch(`${BASE_URL}/admin/theloai.php`);
    const finalListHtml = await finalListRes.text();
    assert.ok(!finalListHtml.includes('Khoa Hoc Cong Nghe'));
});
