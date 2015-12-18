# nukeviet-oauth2

NukeViet Oauth 2 cung cấp tiện ích đăng nhập thông qua Oauth 2 trên các website sử dụng NukeViet, thường thấy ở Google, Facebook. Với tiện ích này, người dùng có thể sử dụng một tài khoản duy nhất ở website NukeVietA để đăng nhập cho tất cả các website NukeVietB, NukeVietC... khác mà không cần đăng ký tài khoản trên các website B, C nữa.

## Yêu cầu hệ thống

- Sử dụng NukeViet >= 4.0.21
- Web Server hỗ trợ url_rewrite
- Web Client hỗ trợ OpenSSL

## Hướng dẫn cài đặt

### Cài đặt cho máy server

Để sử dụng Oauth2, máy server cần cài đặt và cấu hình module oauth2.

#### Cài đặt module oauth2

Có thể lựa chọn một trong hai cách sau:

##### Cài đặt tự động

- Truy cập https://github.com/hoaquynhtim99/nukeviet-oauth2/releases/, chọn phiên bản phù hợp, tải về file nukeviet-oauth2-server.zip
- Đăng nhập quản trị của web server, vào Mở rộng -> Cài đặt gói ứng dụng, chọn file vừa tải về để cài đặt
- Làm theo các bước hướng dẫn để cài đặt module oauth2

##### Cài đặt thủ công

- Truy cập https://github.com/hoaquynhtim99/nukeviet-oauth2/releases/, chọn phiên bản phù hợp, tải về file Source code
- Giải nén file tải về, vào thư mục server, xóa file config.ini
- Copy thư mục modules và themes còn lại vào thư mục gốc của web server (thư mục có file .htaccess, index.php, robots.txt)
- Đăng nhập quản trị của web server, vào Quản lý modules -> Thiết lập module mới để cài đặt module oauth2.

#### Tạo App và cấu hình rewrite

Sau khi cài đặt xong module oauth2, vào phần quản trị của module, nhấp chọn Thêm APP để tạo mới một ứng dụng, copy lại giá trị Mã ứng dụng và Mã bí mật.
Hãy đảm bảo web server đang bật rewrite, mở file .htaccess ở thư mục gốc của website. Tìm dòng
```
##################################################################################
#nukeviet_rewrite_start //Please do not change the contents of the following lines
##################################################################################
```
Thêm lên trên đoạn đó đoạn sau:

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^oauth2/(resource|token|authorize)$ /index.php?nv=oauth2&op=$1&%{QUERY_STRING} [L]
</IfModule>
```

Cuối cùng truy cập đường dẫn sau http://server.oauth2.nukeviet.vn/oauth2/authorize?client_id=123456
Trong đó thay http://server.oauth2.nukeviet.vn bằng tên miền tương ứng của web server. Nếu có thông báo `Hệ thống không tìm thấy APP đã được chỉ định` là đã thành công.

### Cài đặt cho các máy client

- Nếu cài đặt cho server thủ công, trong thư mục giải nén được từ file Source code, vào thư mục client, nếu cài đặt tự động cho server cần tải file Source code, giải nén để có thư mục client. Copy nội dung trong thư mục client vào web client (thư mục có file .htaccess, index.php, robots.txt).
- Mở file `modules/users/oAuthLib/OAuth/OAuth2/Service/NukeViet.php` tìm và thay thế tất cả các giá trị `http://server.oauth2.nukeviet.vn` bằng địa chỉ tương ứng của web server.
- Đăng nhập quản trị của web client, vào khu vực quản trị module Tài khoản -> Cấu hình module, tại "Các nhà cung cấp Oauth, OpenID được chấp nhận" tích vào `oauth oauthnkv` và ấn Lưu.
- Nếu web client không sử dụng giao diện default, trong thư mục client giải nén được, đổi tên `themes/default` thành tên tương ứng với giao diện mà web client đang sử dung sau đó copy lên web client
