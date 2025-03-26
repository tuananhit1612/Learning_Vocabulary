# 📚 Learning Vocab – Ứng dụng học từ vựng tiếng Anh với AI + Quiz

Một ứng dụng web giúp bạn quản lý và luyện tập từ vựng tiếng Anh hiệu quả bằng cách:
- ✨ Trích xuất từ vựng từ file PDF với trợ lý AI (Gemini)
- 🧠 Tạo quiz trắc nghiệm hoặc gõ từ bằng tiếng Anh
- 📊 Theo dõi tiến độ học (đã học, học lại, chưa học)

---

## 🔧 Công nghệ sử dụng

- PHP thuần (MVC structure)
- Bootstrap 5 (giao diện responsive)
- Gemini API (AI xử lý ngôn ngữ)
- MySQL (quản lý từ vựng)
- Laragon (hoặc XAMPP để chạy local)

---

## ⚙️ Cài đặt & chạy local

### 1. Clone project
```bash
git clone https://github.com/<your-username>/Learning_Vocab.git
cd Learning_Vocab
```
### 2. Cấu hình môi trường
- Tạo database MySQL: learning_vocab
- Import file SQL : script.sql
- Mở app/config/database.php và cập nhật:
``` bash
$host = 'localhost';
$db_name = 'learning_vocab';
$username = 'root'; // hoặc user DB của bạn
$password = '';
```
### 3. Cài Laragon (khuyên dùng)
- Tải Laragon
- Đặt thư mục vào C:\laragon\www\Learning_Vocab
- Truy cập tại: http://localhost/Learning_Vocab
  
---

## 🌟 Các tính năng chính
### ✅ Đăng ký & đăng nhập
- Mỗi tài khoản nhập API Key riêng cho Gemini

### 📄 Trích xuất từ vựng từ PDF
- Upload file PDF
- AI trích xuất từ vựng và nghĩa tiếng việt (có thể sửa trước khi lưu)

### 📝 Quản lý từ vựng
- Thêm / sửa / xóa từ vựng
- Danh sách được lưu trong MySQL

### 🧠 Luyện tập
- Chọn số câu hỏi
- Chế độ:
  - Trắc nghiệm
  - Gõ từ
- Hiển thị kết quả sau mỗi câu và cuối bài

### 📊 Thống kê học tập
- Số từ đã học, cần học lại, chưa học

---

### 🎓 Ghi chú
📘 Dự án này được thực hiện sau khi hoàn thành môn học **phát triển phần mềm Mã nguồn mở** tại trường đại học.  
Nó là sản phẩm kết hợp giữa lý thuyết và thực hành, đồng thời giúp rèn luyện tư duy thiết kế ứng dụng web thực tế.

