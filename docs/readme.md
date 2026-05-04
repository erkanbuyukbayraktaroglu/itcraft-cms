# Proje README

Bu doküman, Laravel tabanlı yönetim panelli web sitesi projesinin teknik özetini, klasör yapısını, modüllerini ve bakım notlarını içerir.

Bu dosya daha çok geliştirici, sistem yöneticisi veya projeyi devralacak teknik kişi için hazırlanmıştır.

---

## İçindekiler

1. Proje Özeti  
2. Teknoloji Yapısı  
3. Klasör Yapısı  
4. Temel Modüller  
5. Admin Panel Modülleri  
6. Public / Frontend Yapısı  
7. Route Yapısı  
8. Veritabanı Yapısı  
9. Medya Yönetimi  
10. SMTP ve İletişim Formu  
11. SEO Yapısı  
12. Güvenlik Yapısı  
13. Kurulum Notları  
14. Bakım Notları  
15. Geçici Script Politikası  
16. Sorun Giderme  
17. Teslim Notları  

---

# 1. Proje Özeti

Bu proje, kurumsal web siteleri için hazırlanmış Laravel tabanlı bir yönetim paneli altyapısıdır.

Panel üzerinden aşağıdaki alanlar yönetilebilir:

- Site ayarları
- Logo ve favicon
- Slider
- Sayfalar
- Hizmetler
- Blog kategorileri
- Blog yazıları
- Menü alanları
- Medya dosyaları
- İletişim formu mesajları
- SMTP/e-posta bildirim ayarları
- Kullanım kılavuzu
- Kurulum checklist

Proje farklı firmalara uyarlanabilir yapıdadır. Bu nedenle kod içerisinde sabit marka, firma veya CMS adı kullanılmamalıdır.

---

# 2. Teknoloji Yapısı

Projede kullanılan ana teknolojiler:

| Bileşen | Açıklama |
|---|---|
| Backend | Laravel |
| Dil | PHP |
| Veritabanı | MySQL / MariaDB |
| Web Sunucusu | Apache / cPanel uyumlu |
| Frontend | Blade, HTML, CSS, JavaScript |
| Editör | CKEditor CDN |
| Görsel İşleme | PHP GD |
| Oturum | Laravel Session |
| E-posta | SMTP |

Önerilen PHP sürümü:

```text
PHP 8.2 veya üzeri
```

Mevcut canlı ortamda PHP 8.3 ile kullanılabilir.

---

# 3. Klasör Yapısı

Genel proje dizini:

```text
/public_html
    /app
    /bootstrap
    /config
    /database
    /docs
    /public
    /resources
    /routes
    /storage
    /vendor
    .env
    artisan
    composer.json
    composer.lock
```

## 3.1 Public Klasörü

Web üzerinden erişilebilen ana klasör:

```text
/public_html/public
```

Bu klasörde bulunabilecek temel alanlar:

```text
/public
    /uploads
    /uploads/media
    index.php
    .htaccess
```

## 3.2 Docs Klasörü

Teknik ve kullanım dokümanları:

```text
/public_html/docs
    KULLANIM-KILAVUZU.md
    KURULUM-CHECKLIST.md
    README.md
    .htaccess
```

`docs` klasörü dış erişime kapalı olmalıdır. Dokümanlar admin panel içinden gösterilebilir.

---

# 4. Temel Modüller

Projede yer alan ana modüller:

- Admin authentication
- Dashboard
- Site settings
- Slider management
- Page management
- Service management
- Blog category management
- Blog post management
- Menu management
- Contact message management
- Media management
- SMTP notification settings
- Usage guide page
- Setup checklist page
- Content duplicate feature
- Content preview feature

---

# 5. Admin Panel Modülleri

Admin panel genel erişim adresi:

```text
https://domain.com/admin
```

Alternatif giriş:

```text
https://domain.com/admin/login
```

## 5.1 Dashboard

Dashboard ekranında genel durum ve hızlı erişim bağlantıları bulunur.

## 5.2 Site Ayarları

Site genel bilgileri burada yönetilir.

Örnek alanlar:

- Site adı
- Slogan
- Header logo
- Footer logo
- Favicon
- Telefon
- E-posta
- Adres
- Sosyal medya linkleri
- Renk ayarları
- SMTP ayarları
- SEO varsayılanları

## 5.3 Sayfa Yönetimi

Kurumsal sayfalar bu modül ile yönetilir.

Örnek URL yapısı:

```text
https://domain.com/sayfa-slug
```

## 5.4 Hizmet Yönetimi

Hizmet içerikleri bu modül ile yönetilir.

Örnek URL yapısı:

```text
https://domain.com/hizmetler/hizmet-slug
```

## 5.5 Blog Yönetimi

Blog yazıları ve kategorileri bu modül ile yönetilir.

Örnek URL yapısı:

```text
https://domain.com/blog/yazi-slug
```

## 5.6 Medya Yönetimi

Görsel ve dosya yükleme işlemleri bu modül üzerinden yapılır.

Medya yolu örneği:

```text
uploads/media/2026/04/ornek-gorsel.jpg
```

Tam URL örneği:

```text
https://domain.com/uploads/media/2026/04/ornek-gorsel.jpg
```

## 5.7 İletişim Mesajları

Web sitesindeki iletişim formundan gelen mesajlar admin panelde görüntülenir.

## 5.8 Kullanım Kılavuzu

Admin panel içinden görüntülenir:

```text
/admin/usage-guide
```

İlgili markdown dosyası:

```text
/docs/KULLANIM-KILAVUZU.md
```

## 5.9 Kurulum Checklist

Admin panel içinden görüntülenir:

```text
/admin/setup-checklist
```

İlgili markdown dosyası:

```text
/docs/KURULUM-CHECKLIST.md
```

Checklist tamamlandı bilgisi şu dosyada tutulur:

```text
/storage/app/setup_checklist_completed.flag
```

---

# 6. Public / Frontend Yapısı

Frontend tarafı Blade template dosyaları üzerinden çalışır.

Genel public route mantığı:

- Ana sayfa
- Dinamik sayfa slug
- Hizmet liste/detay
- Blog liste/detay
- İletişim sayfası
- Sitemap
- Robots

Dinamik slug route dosyanın alt kısmına yakın tutulmalıdır. Admin, sitemap, robots, blog, hizmet gibi özel route’lar catch-all slug route’undan önce tanımlanmalıdır.

---

# 7. Route Yapısı

Ana route dosyası:

```text
/routes/web.php
```

Route dosyasında aşağıdaki bloklar bulunabilir:

```text
admin-root-redirect-start
media-picker-routes-start
duplicate-content-routes-start
preview-content-routes-start
usage-guide-routes-start
setup-checklist-routes-start
```

Bu bloklar kontrollü güncelleme için marker mantığıyla eklenmiştir.

## 7.1 Admin Root Redirect

`/admin` adresinin `/admin/login` veya ilgili admin giriş ekranına yönlendirilmesi için kullanılır.

## 7.2 Media Routes

Medya yönetimi ve medya seçici için kullanılır.

## 7.3 Duplicate Routes

Sayfa, hizmet ve blog yazısı kopyalama özelliği için kullanılır.

## 7.4 Preview Routes

Sayfa, hizmet ve blog yazısı önizleme özelliği için kullanılır.

## 7.5 Usage Guide Routes

Admin panel içindeki kullanım kılavuzu sayfası için kullanılır.

## 7.6 Setup Checklist Routes

Admin panel içindeki kurulum checklist sayfası için kullanılır.

---

# 8. Veritabanı Yapısı

Projede kullanılan temel tablolar şunlardır:

- users
- site_settings
- pages
- services
- posts
- post_categories
- sliders
- menus
- contact_messages
- sessions

Kurulum sırasında SQL dump import edildikten sonra bu tablolar kontrol edilmelidir.

## 8.1 Slug Alanları

Sayfa, hizmet ve blog gibi içeriklerde slug alanı kullanılır.

Slug değerleri benzersiz olmalıdır.

Örnek:

```text
kvkk-danismanligi-ve-uyum-sureci
```

---

# 9. Medya Yönetimi

Medya dosyaları şu klasöre yüklenir:

```text
/public/uploads/media
```

Dosyalar yıl/ay bazlı alt klasörlere ayrılabilir:

```text
/uploads/media/2026/04/dosya-adi.jpg
```

## 9.1 Görsel Optimizasyonu

JPG, PNG ve WebP dosyaları GD aktifse optimize edilir.

Varsayılan hedef:

```text
Maksimum genişlik/yükseklik: 1920 px
```

Bu değer `MediaController` içinde yönetilir.

## 9.2 Tam URL ve Relative Path

Panelde iki kullanım biçimi bulunur:

```text
Tam URL:
https://domain.com/uploads/media/2026/04/ornek.jpg

Relative Path:
uploads/media/2026/04/ornek.jpg
```

Yeni domain taşıması yapılacaksa relative path daha esnek olabilir.

---

# 10. SMTP ve İletişim Formu

İletişim formu gönderimleri hem admin panelde saklanabilir hem de SMTP üzerinden e-posta bildirimi gönderebilir.

SMTP için gereken alanlar:

- SMTP host
- SMTP port
- SMTP username
- SMTP password
- Encryption
- From address
- From name
- Notification recipient

Örnek cPanel SMTP ayarı:

```text
SMTP Host: mail.domain.com
SMTP Port: 587
Encryption: TLS
Username: info@domain.com
From Address: info@domain.com
```

---

# 11. SEO Yapısı

SEO için yönetilebilecek alanlar:

- Meta title
- Meta description
- Meta keywords
- OG image
- Slug
- Sitemap
- Robots

Sitemap adresi:

```text
https://domain.com/sitemap.xml
```

Robots adresi:

```text
https://domain.com/robots.txt
```

---

# 12. Güvenlik Yapısı

Canlı sistemde aşağıdaki güvenlik kuralları korunmalıdır:

- `APP_DEBUG=false`
- `APP_ENV=production`
- `.env` dışarıdan erişime kapalı
- `composer.json` dışarıdan erişime kapalı
- `artisan` dışarıdan erişime kapalı
- `app`, `storage`, `vendor` klasörleri dışarıdan erişime kapalı
- `docs` klasörü dışarıdan erişime kapalı
- Public altında geçici PHP script bulunmamalı
- Admin şifreleri güçlü olmalı
- Varsayılan/test kullanıcılar silinmeli
- SMTP şifreleri paylaşılmamalı

## 12.1 Root .htaccess

Konum:

```text
/public_html/.htaccess
```

Görevi:

- Laravel’i public klasöründen çalıştırmak
- Hassas dosyaları korumak
- Laravel ana klasörlerini dış erişime kapatmak
- URL’de `/public` görünmesini engellemek

## 12.2 Public .htaccess

Konum:

```text
/public_html/public/.htaccess
```

Görevi:

- Laravel route sistemini çalıştırmak
- Directory listing’i kapatmak
- `index.php` yönlendirmesini yapmak

## 12.3 Docs .htaccess

Konum:

```text
/public_html/docs/.htaccess
```

Görevi:

- Dokümanların dışarıdan görüntülenmesini engellemek

Beklenen test sonucu:

```text
https://domain.com/docs/KULLANIM-KILAVUZU.md
```

Bu adres 403 Forbidden dönmelidir.

---

# 13. Kurulum Notları

Yeni kurulumlarda şu sıraya uyulması önerilir:

1. Domain ve hosting hazırlanır.
2. Dosyalar yüklenir.
3. `.env` düzenlenir.
4. Veritabanı oluşturulur.
5. SQL dump import edilir.
6. `.htaccess` dosyaları kontrol edilir.
7. Storage/cache izinleri kontrol edilir.
8. Admin giriş testi yapılır.
9. Site ayarları girilir.
10. Medya, logo ve slider ayarları yapılır.
11. Menü ve içerikler kontrol edilir.
12. SMTP testi yapılır.
13. Sitemap/robots kontrol edilir.
14. Final canlı kontrol yapılır.
15. Geçici scriptler silinir.

---

# 14. Bakım Notları

## 14.1 Haftalık Kontrol

- Yeni iletişim mesajları kontrol edilir.
- Log dosyası aşırı büyümüş mü kontrol edilir.
- Yeni içerikler ön yüzde test edilir.

## 14.2 Aylık Kontrol

- Veritabanı yedeği alınır.
- Medya klasörü yedeklenir.
- Admin kullanıcıları kontrol edilir.
- Sitemap kontrol edilir.
- Gereksiz dosyalar temizlenir.

## 14.3 Büyük Değişiklik Öncesi

- Veritabanı yedeği alınır.
- Medya klasörü yedeklenir.
- `.env` dosyası yedeklenir.
- `routes/web.php` yedeklenir.
- Admin panel test edilir.

---

# 15. Geçici Script Politikası

Kurulum veya düzeltme sırasında public klasöre geçici scriptler eklenebilir.

Örnek geçici script adları:

```text
create_*.php
fix_*.php
update_*.php
emergency_*.php
read_*.php
test_*.php
check_*.php
debug_*.php
repair_*.php
install_*.php
setup_*.php
temp_*.php
add_*.php
final_*.php
```

Bu dosyalar işlem tamamlandıktan sonra mutlaka silinmelidir.

Canlı sistemde public altında bu dosyaların kalması güvenlik riski oluşturur.

---

# 16. Sorun Giderme

## 16.1 500 Server Error

Kontrol edilecekler:

- `storage/logs/laravel.log`
- Son değiştirilen Blade dosyası
- Son değiştirilen route bloğu
- PHP syntax hatası
- Cache dosyaları
- `.env` ayarları

View cache temizliği gerekebilir:

```text
storage/framework/views
```

## 16.2 404 Not Found

Kontrol edilecekler:

- Route var mı?
- Catch-all slug route özel route’un önüne geçmiş mi?
- `.htaccess` doğru mu?
- URL doğru mu?
- Slug doğru mu?

## 16.3 Admin Giriş Sorunu

Kontrol edilecekler:

- `/admin/login` açılıyor mu?
- Session driver çalışıyor mu?
- Kullanıcı aktif mi?
- Şifre doğru mu?
- `sessions` tablosu var mı?
- Storage yazılabilir mi?

## 16.4 Görsel Yüklenmiyor

Kontrol edilecekler:

- `public/uploads/media` yazılabilir mi?
- Dosya boyutu uygun mu?
- Dosya uzantısı izinli mi?
- GD aktif mi?
- Disk kotası dolu mu?

## 16.5 Mail Gitmiyor

Kontrol edilecekler:

- SMTP host doğru mu?
- Port doğru mu?
- TLS/SSL doğru mu?
- Kullanıcı adı doğru mu?
- Şifre doğru mu?
- From address SMTP hesabı ile uyumlu mu?
- Spam/Junk klasörü kontrol edildi mi?

---

# 17. Teslim Notları

Müşteri teslimi öncesi şu dosyalar hazır olmalıdır:

```text
/docs/KULLANIM-KILAVUZU.md
/docs/KURULUM-CHECKLIST.md
/docs/README.md
```

Admin panelde şu sayfalar çalışmalıdır:

```text
/admin/usage-guide
/admin/setup-checklist
```

Docs klasörü dış erişime kapalı olmalıdır:

```text
https://domain.com/docs/KULLANIM-KILAVUZU.md
```

Beklenen:

```text
403 Forbidden
```

Teslim öncesi final kontrol yapılmalı ve public altındaki varsa geçici scriptler silinmelidir.

---

# 18. Son Not

Bu README dosyası projenin teknik işletim ve bakım dokümanıdır.

Yeni geliştirme yapıldıkça bu dosya güncellenmelidir.

Özellikle route blokları, yeni modüller, yeni veritabanı tabloları ve güvenlik ayarları değiştiğinde README içeriği güncel tutulmalıdır.
