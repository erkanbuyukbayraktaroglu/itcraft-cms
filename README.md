# Kurulum Checklist

Bu doküman, Laravel tabanlı yönetim panelli web sitesi projesinin yeni müşteri/domain üzerine kurulumu sırasında takip edilmesi gereken adımları içerir.

Amaç; her kurulumda aynı standartla ilerlemek, eksik ayar bırakmamak ve canlıya geçiş öncesi güvenlik kontrollerini tamamlamaktır.

---

## İçindekiler

1. Kurulum Öncesi Hazırlık  
2. Sunucu Gereksinimleri  
3. Dosya Yapısı Kontrolü  
4. Domain ve Hosting Hazırlığı  
5. Dosyaların Sunucuya Yüklenmesi  
6. `.env` Ayarları  
7. Veritabanı Kurulumu  
8. İlk Admin Kullanıcı Kontrolü  
9. `.htaccess` Kontrolü  
10. Storage ve Cache İzinleri  
11. Admin Panel İlk Giriş  
12. Site Ayarları İlk Kurulum  
13. Logo ve Görsel Ayarları  
14. Menü Ayarları  
15. Slider Ayarları  
16. Sayfa / Hizmet / Blog Kontrolü  
17. Medya Yönetimi Kontrolü  
18. SMTP Ayarları  
19. SEO / Sitemap / Robots Kontrolü  
20. Güvenlik Kontrolleri  
21. Geçici Script Temizliği  
22. Final Canlı Kontrol  
23. Müşteri Teslim Kontrolü  
24. Kurulum Sonrası Bakım Notları  
25. Hızlı Kurulum Özeti  
26. Son Not  

---

# 1. Kurulum Öncesi Hazırlık

Kuruluma başlamadan önce aşağıdaki bilgiler hazır olmalıdır:

- Domain adı
- Hosting panel erişimi
- FTP / SFTP bilgileri
- Veritabanı adı
- Veritabanı kullanıcı adı
- Veritabanı şifresi
- Admin kullanıcı bilgileri
- SMTP bilgileri
- Logo dosyaları
- Favicon
- Kurumsal renk kodları
- Sosyal medya linkleri
- İletişim bilgileri
- İlk açılışta kullanılacak slider görselleri
- Temel sayfa içerikleri
- Hizmet listesi
- Blog kategorileri

---

# 2. Sunucu Gereksinimleri

Sunucuda aşağıdaki gereksinimler kontrol edilmelidir:

| Gereksinim | Durum |
|---|---|
| PHP 8.2 veya üzeri | Kontrol edilmeli |
| MySQL / MariaDB | Kontrol edilmeli |
| Apache mod_rewrite | Aktif olmalı |
| PDO | Aktif olmalı |
| PDO MySQL | Aktif olmalı |
| OpenSSL | Aktif olmalı |
| Mbstring | Aktif olmalı |
| Tokenizer | Aktif olmalı |
| XML | Aktif olmalı |
| Ctype | Aktif olmalı |
| JSON | Aktif olmalı |
| Fileinfo | Aktif olmalı |
| GD | Görsel optimizasyon için aktif olmalı |

Özellikle medya optimizasyonu için `gd` eklentisi önemlidir.

---

# 3. Dosya Yapısı Kontrolü

Proje dosya yapısı genel olarak aşağıdaki gibi olmalıdır:

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

Web kökü cPanel üzerinde `public_html` olabilir. Laravel uygulaması ise `/public` klasörü üzerinden çalışmalıdır.

URL’de `/public` görünmemelidir.

Doğru kullanım:

```text
https://domain.com
https://domain.com/admin
```

Yanlış kullanım:

```text
https://domain.com/public
https://domain.com/public/admin
```

---

# 4. Domain ve Hosting Hazırlığı

Kurulum yapılacak domain hosting panelinde doğru dizine yönlendirilmelidir.

Kontrol edilecekler:

- Domain doğru hosting hesabına bağlı mı?
- SSL aktif mi?
- PHP sürümü doğru mu?
- `public_html` dizini doğru mu?
- Eski site dosyaları yedeklendi mi?
- Eski `.htaccess` dosyası varsa kontrol edildi mi?

---

# 5. Dosyaların Sunucuya Yüklenmesi

Proje dosyaları sunucuya yüklendikten sonra şu kontroller yapılmalıdır:

- Tüm klasörler eksiksiz yüklendi mi?
- `.env` dosyası mevcut mu?
- `vendor` klasörü mevcut mu?
- `storage` klasörü mevcut mu?
- `bootstrap/cache` klasörü mevcut mu?
- `public/uploads` klasörü mevcut mu?
- `public/uploads/media` klasörü mevcut mu?

Eğer `vendor` klasörü yoksa Composer kurulumu gerekir.

Paylaşımlı hosting ortamında Composer çalıştırılamıyorsa, proje local ortamda hazırlanıp `vendor` klasörüyle birlikte yüklenmelidir.

---

# 6. `.env` Ayarları

`.env` dosyasında aşağıdaki alanlar kontrol edilmelidir:

```env
APP_NAME="Website"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password

SESSION_DRIVER=database
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

## 6.1 APP_KEY Kontrolü

`APP_KEY` boş olmamalıdır.

Boşsa Laravel komutu ile oluşturulmalıdır:

```bash
php artisan key:generate
```

Paylaşımlı hostingte artisan çalıştırılamıyorsa APP_KEY local ortamda üretilip `.env` içine yazılabilir.

## 6.2 APP_DEBUG Kontrolü

Canlı ortamda mutlaka şu şekilde olmalıdır:

```env
APP_DEBUG=false
```

## 6.3 APP_ENV Kontrolü

Canlı ortamda şu şekilde olmalıdır:

```env
APP_ENV=production
```

## 6.4 APP_URL Kontrolü

Domain doğru yazılmalıdır:

```env
APP_URL=https://domain.com
```

Sonunda `/` bırakılmaması önerilir.

---

# 7. Veritabanı Kurulumu

Veritabanı kurulumunda aşağıdaki adımlar izlenir:

1. Hosting panelinden yeni MySQL veritabanı oluşturulur.
2. Veritabanı kullanıcısı oluşturulur.
3. Kullanıcıya ilgili veritabanı için tüm yetkiler verilir.
4. `.env` dosyasına DB bilgileri yazılır.
5. SQL dump dosyası içe aktarılır.
6. Tablolar kontrol edilir.

Kontrol edilecek temel tablolar:

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

Eksik tablo varsa kurulum SQL dosyası tekrar kontrol edilmelidir.

---

# 8. İlk Admin Kullanıcı Kontrolü

Kurulumdan sonra admin kullanıcısı kontrol edilmelidir.

Kontrol edilecekler:

- Admin kullanıcısı mevcut mu?
- E-posta adresi doğru mu?
- Şifre biliniyor mu?
- Kullanıcı aktif mi?
- Rol/yetki alanı varsa doğru mu?

Varsayılan admin bilgileri kurulumdan sonra mutlaka değiştirilmelidir.

Örnek güvenli işlem:

```text
Kurulum sonrası geçici admin şifresi değiştirilir.
Müşteriye yeni şifre güvenli kanaldan iletilir.
Varsayılan/test kullanıcıları silinir.
```

---

# 9. `.htaccess` Kontrolü

Projede iki önemli `.htaccess` dosyası bulunur.

```text
/public_html/.htaccess
/public_html/public/.htaccess
```

## 9.1 Root `.htaccess`

Root `.htaccess` dosyası şu görevleri yapmalıdır:

- Laravel’i `/public` klasöründen çalıştırmak
- URL’de `/public` görünmesini engellemek
- `.env` gibi hassas dosyalara erişimi engellemek
- `app`, `storage`, `vendor` gibi klasörleri dış erişime kapatmak

Kontrol:

```text
https://domain.com/.env
```

Beklenen sonuç:

```text
403 Forbidden
```

veya erişim engeli.

## 9.2 Public `.htaccess`

Public `.htaccess` dosyası şu görevleri yapmalıdır:

- Laravel route sistemini çalıştırmak
- Gerçek dosyaları doğrudan servis etmek
- Directory listing’i kapatmak
- `index.php` yönlendirmesini yapmak

Kontrol:

```text
https://domain.com/admin
```

Beklenen:

```text
/admin/login
```

veya admin giriş ekranı.

---

# 10. Storage ve Cache İzinleri

Aşağıdaki klasörlerin yazılabilir olduğundan emin olunmalıdır:

```text
storage
storage/framework
storage/framework/views
storage/logs
bootstrap/cache
public/uploads
public/uploads/media
```

Paylaşımlı hosting ortamında genellikle `755` yeterlidir. Sorun yaşanırsa hosting panel izinleri kontrol edilmelidir.

---

# 11. Admin Panel İlk Giriş

Admin panel giriş adresi:

```text
https://domain.com/admin
```

Alternatif:

```text
https://domain.com/admin/login
```

Kontrol edilecekler:

- Login ekranı açılıyor mu?
- Giriş yapılabiliyor mu?
- Dashboard açılıyor mu?
- Menü linkleri çalışıyor mu?
- Çıkış yapılabiliyor mu?

---

# 12. Site Ayarları İlk Kurulum

Admin panelde **Site Ayarları** bölümüne girilerek aşağıdaki alanlar doldurulmalıdır:

- Site adı
- Site sloganı
- Site açıklaması
- Header logo
- Footer logo
- Favicon
- Telefon
- E-posta
- Adres
- Çalışma saatleri
- Sosyal medya linkleri
- Ana renkler
- Varsayılan SEO bilgileri

Bu alanlar doldurulmadan site teslim edilmemelidir.

---

# 13. Logo ve Görsel Ayarları

Logo dosyaları medya yönetimi üzerinden yüklenmelidir.

Önerilen logo formatları:

```text
SVG
PNG
WebP
```

Kontrol edilecekler:

- Header logosu açık zeminde görünüyor mu?
- Footer logosu koyu zeminde görünüyor mu?
- Mobil menüde logo düzgün mü?
- Favicon tarayıcı sekmesinde görünüyor mu?

---

# 14. Menü Ayarları

Menü yönetiminde temel linkler kontrol edilmelidir:

```text
Anasayfa: /
Hakkımızda: /hakkimizda
Hizmetler: /hizmetler
Blog: /blog
İletişim: /iletisim
```

Kontrol edilecekler:

- Header menüsü doğru mu?
- Footer menüsü doğru mu?
- Linkler çalışıyor mu?
- Dış bağlantılar yeni sekmede açılıyor mu?
- Mobil menü düzgün çalışıyor mu?

---

# 15. Slider Ayarları

Ana sayfa slider alanı kontrol edilmelidir.

Önerilen slider görsel ölçüsü:

```text
1920 x 800 px
```

Kontrol edilecekler:

- Slider görseli yüklendi mi?
- Başlık doğru mu?
- Açıklama doğru mu?
- Buton metni doğru mu?
- Buton linki doğru mu?
- Sıralama doğru mu?
- Mobil görünüm bozuluyor mu?

---
# 16. Sayfa / Hizmet / Blog Kontrolü

Kurulum sonrası örnek içerikler kontrol edilmelidir.

## 16.1 Sayfalar

Kontrol edilecekler:

- Hakkımızda sayfası var mı?
- İletişim sayfası var mı?
- KVKK / Gizlilik sayfası var mı?
- Slug değerleri doğru mu?
- Önizleme çalışıyor mu?

## 16.2 Hizmetler

Kontrol edilecekler:

- Hizmet listesi doğru mu?
- Hizmet detay sayfaları açılıyor mu?
- Görseller doğru mu?
- SEO alanları dolu mu?

## 16.3 Blog

Kontrol edilecekler:

- Blog kategorileri var mı?
- Blog yazısı eklenebiliyor mu?
- Blog önizleme çalışıyor mu?
- Kapak görseli doğru görünüyor mu?

---

# 17. Medya Yönetimi Kontrolü

Medya yönetimi test edilmelidir.

Test adımları:

1. Küçük bir JPG görsel yükle.
2. Yükleme sonrası dosya listede görünüyor mu kontrol et.
3. Tam URL kopyalanabiliyor mu kontrol et.
4. Path kopyalanabiliyor mu kontrol et.
5. Medyadan seç özelliğini test et.
6. Görsel ön yüzde açılıyor mu kontrol et.

GD aktifse görsel optimizasyon çalışır.

GD aktif değilse dosya yüklenebilir fakat optimizasyon yapılmayabilir.

---

# 18. SMTP Ayarları

İletişim formunun e-posta göndermesi için SMTP ayarları yapılmalıdır.

Site ayarları içinde aşağıdaki bilgiler doldurulur:

```text
SMTP aktif/pasif
SMTP host
SMTP port
SMTP kullanıcı adı
SMTP şifre
Şifreleme
Gönderen e-posta
Gönderen adı
Form bildirimi gidecek e-posta
```

## 18.1 cPanel Mail Örneği

```text
SMTP Host: mail.domain.com
SMTP Port: 587
Şifreleme: TLS
SMTP Kullanıcı Adı: info@domain.com
Gönderen E-posta: info@domain.com
```

## 18.2 Test

1. SMTP ayarları kaydedilir.
2. İletişim sayfasından test mesajı gönderilir.
3. Mesaj admin panelde görünüyor mu kontrol edilir.
4. E-posta bildirimi geliyor mu kontrol edilir.
5. Spam/Junk klasörü kontrol edilir.

---

# 19. SEO / Sitemap / Robots Kontrolü

Kontrol adresleri:

```text
https://domain.com/sitemap.xml
https://domain.com/robots.txt
```

Kontrol edilecekler:

- Sitemap açılıyor mu?
- Robots.txt açılıyor mu?
- Domain doğru mu?
- Yeni sayfalar sitemap içinde görünüyor mu?
- SEO title ve description alanları dolu mu?

---

# 20. Güvenlik Kontrolleri

Canlıya geçmeden önce aşağıdaki güvenlik kontrolleri yapılmalıdır:

- `APP_DEBUG=false`
- `APP_ENV=production`
- `.env` dışarıdan erişilemiyor
- `composer.json` dışarıdan erişilemiyor
- `artisan` dışarıdan erişilemiyor
- `app` klasörü dışarıdan erişilemiyor
- `storage` klasörü dışarıdan erişilemiyor
- `vendor` klasörü dışarıdan erişilemiyor
- Public altında geçici PHP script yok
- Varsayılan/test kullanıcılar kaldırıldı
- Admin şifresi değiştirildi
- SMTP şifresi paylaşılmadı
- Docs klasörü dış erişime kapatıldı

---

# 21. Geçici Script Temizliği

Kurulum sırasında kullanılan geçici scriptler işlem bitince mutlaka silinmelidir.

Kontrol edilecek örnek dosya isimleri:

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

Public klasörde sadece gerekli dosyalar kalmalıdır.

Özellikle şu tarz dosyalar canlıda bırakılmamalıdır:

```text
final_live_check.php
read_last_laravel_error.php
create_admin_user.php
backup_export.php
```

---

# 22. Final Canlı Kontrol

Final kontrol scripti çalıştırılarak sistem raporu alınabilir.

Kontrol edilmesi gereken başlıklar:

- Kritik hata sayısı
- Uyarı sayısı
- Public geçici dosya kontrolü
- APP_DEBUG
- APP_ENV
- .htaccess kontrolleri
- Route kontrolleri
- Storage/cache yazılabilirliği
- GD eklentisi
- Laravel log boyutu

Final kontrolde sadece kontrol dosyasının kendisi kritik görünüyorsa, dosya silindikten sonra kritik sayı 0 olur.

---

# 23. Müşteri Teslim Kontrolü

Müşteriye teslim öncesi aşağıdaki kontroller yapılmalıdır:

- Admin panel giriş bilgileri hazırlandı mı?
- Kullanım kılavuzu hazır mı?
- Kullanım kılavuzu admin panelden açılıyor mu?
- Docs klasörü dış erişime kapalı mı?
- Logo ve renkler müşteri bilgilerine göre güncellendi mi?
- İletişim bilgileri doğru mu?
- SMTP test edildi mi?
- Menü linkleri doğru mu?
- Mobil görünüm kontrol edildi mi?
- Form gönderimi test edildi mi?
- Sitemap/robots kontrol edildi mi?
- Geçici dosyalar silindi mi?
- Yedek alındı mı?

---

# 24. Kurulum Sonrası Bakım Notları

Kurulumdan sonra aşağıdaki periyodik kontroller önerilir.

## Haftalık

- İletişim mesajları kontrol edilir.
- Yeni içerikler kontrol edilir.
- Log dosyası aşırı büyümüş mü bakılır.

## Aylık

- Admin kullanıcıları kontrol edilir.
- Yedek alınır.
- Medya klasörü kontrol edilir.
- Gereksiz dosyalar temizlenir.
- Sitemap kontrol edilir.

## Büyük değişikliklerden önce

- Veritabanı yedeği alınır.
- Medya klasörü yedeklenir.
- `.env` dosyası yedeklenir.
- Güncelleme sonrası admin panel test edilir.

---

# 25. Hızlı Kurulum Özeti


```text
1. Domain/hosting hazırla
2. Dosyaları yükle
3. .env düzenle
4. DB oluştur ve import et
5. APP_KEY kontrol et
6. .htaccess kontrol et
7. Storage/cache izinlerini kontrol et
8. Admin girişini test et
9. Site ayarlarını doldur
10. Logo/favicon yükle
11. Menüleri düzenle
12. Slider ekle
13. Hizmet/sayfa/blog içeriklerini kontrol et
14. SMTP ayarlarını yap
15. İletişim formunu test et
16. Sitemap/robots kontrol et
17. Final güvenlik kontrolü yap
18. Geçici scriptleri sil
19. Yedek al
20. Müşteriye teslim et
```

---

# 26. Son Not

Bu checklist, kurulum sürecinde eksik adım kalmaması için hazırlanmıştır.

Her kurulumdan sonra bu doküman üzerinden işaretleme yapılması önerilir.

Kurulum tamamlandıktan sonra özellikle `.env`, `.htaccess`, admin kullanıcıları, geçici scriptler ve SMTP ayarları mutlaka tekrar kontrol edilmelidir.
