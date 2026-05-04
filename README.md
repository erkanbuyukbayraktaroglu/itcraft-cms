# ITCRAFT CMS

**ITCRAFT CMS**, Laravel tabanlı, kurumsal web siteleri için geliştirilmiş çok amaçlı bir içerik yönetim sistemidir.

Bu proje; kurumsal web sitesi, hizmet tanıtım sitesi, blog, ekip tanıtımı, slider yönetimi, iletişim mesajları, tema ayarları, SEO kontrolleri ve müşteri teslim süreçlerini tek panel üzerinden yönetebilmek amacıyla hazırlanmıştır.

---

## İçindekiler

- [Genel Bakış](#genel-bakış)
- [Öne Çıkan Özellikler](#öne-çıkan-özellikler)
- [Teknolojiler](#teknolojiler)
- [Kurulum Mantığı](#kurulum-mantığı)
- [Tek Dosyalık Kurulum](#tek-dosyalık-kurulum)
- [cPanel Kullanımı](#cpanel-kullanımı)
- [Demo İçerikler](#demo-içerikler)
- [Admin Panel](#admin-panel)
- [Güvenlik](#güvenlik)
- [Dosya Yapısı](#dosya-yapısı)
- [Kurulum Sonrası Kontroller](#kurulum-sonrası-kontroller)
- [Geliştirici Notları](#geliştirici-notları)
- [Sorun Giderme](#sorun-giderme)
- [Lisans ve Kullanım](#lisans-ve-kullanım)
- [Geliştirici](#geliştirici)
- [Durum ve Yol Haritası](#durum-ve-yol-haritası)

---

## Genel Bakış

ITCRAFT CMS, klasik bir web sitesi yönetim panelinden daha fazlasını hedefler.

Sistem; farklı sektörlerde kullanılabilecek şekilde esnek yapılandırılmıştır. İlk kurulum sonrasında demo içerikler ile birlikte gelir. Müşteri veya yönetici, admin panel üzerinden logo, favicon, slider, hizmetler, ekip üyeleri, blog yazıları, menüler, SEO bilgileri ve tema renklerini kolayca değiştirebilir.

Proje özellikle cPanel, paylaşımlı hosting ve klasik PHP hosting ortamlarında kolay kurulum yapılabilmesi için tasarlanmıştır. Son kullanıcı kurulum sırasında yalnızca veritabanı bilgilerini ve ilk admin hesabını girer. Dosya taşıma, `.htaccess` düzenleme veya `/public` yönlendirmesi gibi teknik işlemler otomatik olarak hazırlanır.

---

## Öne Çıkan Özellikler

- Laravel tabanlı modern yapı
- cPanel uyumlu tek dosyalık kurulum hazırlığı
- GitHub üzerinden otomatik dosya çekme
- İlk kurulumda veritabanı ve admin hesabı oluşturma
- Dummy logo, favicon, slider, hizmet, ekip ve blog içerikleri
- Yönetilebilir sayfa yapısı
- Hizmet yönetimi
- Blog ve kategori yönetimi
- Slider yönetimi
- Ekip üyeleri yönetimi
- Menü yönetimi
- Medya yönetimi
- İletişim mesajları yönetimi
- Tema ve renk ayarları
- Footer renk yönetimi
- Tema preset altyapısı
- SEO kontrol modülü
- İçerik versiyon geçmişi
- Admin login güvenliği
- Login deneme kayıtları
- Sistem sağlık kontrol mantığı
- Kullanım kılavuzu görüntüleme altyapısı
- Kurulum sonrası güvenlik temizliği
- `/public` görünmeden çalışma desteği
- Demo verilerle hazır kurulum

---

## Teknolojiler

Bu proje aşağıdaki teknolojiler kullanılarak geliştirilmiştir:

- PHP 8.2+
- Laravel 11
- MySQL / MariaDB
- Blade Template Engine
- HTML5
- CSS3
- JavaScript
- cPanel / Apache `.htaccess` uyumlu yapı

---

## Kurulum Mantığı

ITCRAFT CMS, müşteri tarafında kolay kurulum hedefiyle hazırlanmıştır.

Temel kurulum akışı şu şekildedir:

```text
1. Müşteri sunucusuna tek kurulum dosyası yüklenir
2. Kurulum dosyası GitHub reposundan projeyi indirir
3. Proje dosyaları sunucuya açılır
4. Gerekli storage, cache ve uploads klasörleri oluşturulur
5. Root .htaccess otomatik yazılır
6. Sistem /public görünmeden çalışacak hale gelir
7. Kullanıcı /install ekranına yönlendirilir
8. Veritabanı bilgileri girilir
9. İlk admin hesabı oluşturulur
10. Demo içerikler veritabanına yüklenir
11. Kurulum sonrası güvenlik temizliği yapılır
```

---

## Tek Dosyalık Kurulum

Müşteri sunucusunda domain ana dizinine aşağıdaki dosya yüklenir:

```text
itcraft_cms.php
```

Ardından tarayıcıdan çalıştırılır:

```text
https://domain.com/itcraft_cms.php
```

Kurulum hazırlığı tamamlandıktan sonra sistem otomatik olarak şu adrese yönlenir:

```text
https://domain.com/install
```

Son kullanıcı yalnızca şu bilgileri girer:

- Site adı
- Site URL
- Veritabanı host
- Veritabanı port
- Veritabanı adı
- Veritabanı kullanıcı adı
- Veritabanı şifresi
- Admin adı soyadı
- Admin e-posta adresi
- Admin şifresi

Kurulum tamamlandığında admin paneline aşağıdaki adresten erişilir:

```text
https://domain.com/admin
```

---

## cPanel Kullanımı

Proje cPanel ortamları düşünülerek hazırlanmıştır.

Domain ana dizininde Laravel dosyaları bulunur. Ancak kullanıcı URL tarafında `/public` görmez.

Root `.htaccess` dosyası, tüm istekleri içeriden Laravel `public` klasörüne aktarır.

Örnek çalışan adresler:

```text
https://domain.com/
https://domain.com/admin
https://domain.com/install
```

Kullanıcının aşağıdaki gibi bir URL kullanmasına gerek yoktur:

```text
https://domain.com/public
```

Bu yapı özellikle paylaşımlı hostinglerde Laravel projesinin daha kolay çalıştırılması için tercih edilmiştir.

---

## Demo İçerikler

İlk kurulumda sistem boş gelmez. Müşterinin paneli ve siteyi daha kolay anlaması için demo içerikler hazır olarak gelir.

Demo içerikler şunları kapsar:

- Demo logo
- Demo favicon
- Demo slider görselleri
- Demo hizmetler
- Demo ekip üyeleri
- Demo blog kategorileri
- Demo blog yazıları
- Demo sayfalar
- Demo menüler
- Demo tema renkleri
- Demo footer ayarları

Demo görseller şu dizinde tutulur:

```text
public/assets/itcraft-cms-demo/
```

Demo veritabanı dosyası:

```text
database/dump/itcraft_cms_base.sql
```

Bu SQL dosyası kurulum sırasında içeri aktarılır. İçinde hazır admin şifresi bulunmaz. İlk admin hesabı kurulum ekranından oluşturulur.

---

## Admin Panel

Kurulum tamamlandıktan sonra admin paneline aşağıdaki adresten erişilir:

```text
https://domain.com/admin
```

İlk admin hesabı, kurulum ekranında kullanıcı tarafından oluşturulur. Proje içerisinde hazır admin şifresi bulunmaz.

Admin panel üzerinden yönetilebilecek ana alanlar:

- Dashboard
- Sayfalar
- Hizmetler
- Blog yazıları
- Blog kategorileri
- Slider
- Ekip üyeleri
- Menü yönetimi
- Medya yönetimi
- İletişim mesajları
- Site ayarları
- Tema ayarları
- Tema presetleri
- SEO kontrol
- İçerik versiyonları
- Kullanım kılavuzu
- Login güvenliği
- Sistem kontrolleri

---

## Güvenlik

Kurulum sonrası sistem aşağıdaki dosya ve klasörleri otomatik silmeye çalışır:

```text
itcraft_cms.php
public/install/
```

Ayrıca sistem kurulumun tamamlandığını anlamak için şu dosyayı oluşturur:

```text
storage/install.lock
```

Bu dosya sistemde kalmalıdır. Kurulumun tekrar çalıştırılmasını engeller.

GitHub reposuna kesinlikle yüklenmemesi gereken dosyalar:

```text
.env
.env.*
storage/logs/*.log
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
storage/app/backups/*
public/create_*.php
public/fix_*.php
public/audit_*.php
public/check_*.php
public/final_*.php
public/*backup*
```

Repo’ya dahil edilmesi gereken önemli dosyalar:

```text
.env.example
database/dump/itcraft_cms_base.sql
public/assets/itcraft-cms-demo/
vendor/
composer.json
composer.lock
public/install/index.php
```

> Not: Normal Laravel kaynak kod reposunda `vendor/` klasörü genellikle tutulmaz. Ancak bu proje cPanel üzerinde Composer çalıştırmadan kurulabilir bir release paketi olarak planlandığı için `vendor/` klasörü kurulum paketine dahil edilebilir.

---

## Dosya Yapısı

Önerilen temel proje yapısı:

```text
itcraft-cms/
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── dump/
│       └── itcraft_cms_base.sql
├── docs/
├── public/
│   ├── assets/
│   │   └── itcraft-cms-demo/
│   ├── install/
│   │   └── index.php
│   ├── uploads/
│   ├── index.php
│   └── .htaccess
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
├── composer.lock
├── .env.example
├── .gitignore
└── README.md
```

---

## Kurulum Sonrası Kontroller

Kurulumdan sonra aşağıdaki kontroller yapılmalıdır:

- `.env` dosyası oluştu mu?
- `APP_DEBUG=false` mı?
- `APP_ENV=production` mı?
- Admin panel açılıyor mu?
- Site ana sayfası açılıyor mu?
- `/public` URL’de görünmüyor mu?
- Logo ve favicon değiştirilebiliyor mu?
- Slider içerikleri düzenlenebiliyor mu?
- Hizmetler düzenlenebiliyor mu?
- Ekip üyeleri düzenlenebiliyor mu?
- Blog yazıları düzenlenebiliyor mu?
- Menü yönetimi çalışıyor mu?
- İletişim formu çalışıyor mu?
- SMTP ayarları tamamlandı mı?
- SEO ayarları düzenlendi mi?
- Tema renkleri değiştirilebiliyor mu?
- Footer renkleri ayrı yönetilebiliyor mu?
- `itcraft_cms.php` silindi mi?
- `public/install/` silindi mi?
- `storage/install.lock` mevcut mu?

---

## Geliştirici Notları

Bu proje, farklı sektörlere uyarlanabilir bir CMS altyapısı olarak planlanmıştır.

Uyarlanabilecek örnek kullanım alanları:

- Kurumsal web sitesi
- Hukuk bürosu sitesi
- Klinik / sağlık sitesi
- Lojistik firması sitesi
- Danışmanlık firması sitesi
- Eğitim kurumu sitesi
- Ajans / portföy sitesi
- Blog / haber sitesi
- Landing page yapısı
- BT danışmanlık sitesi
- Hizmet tanıtım sitesi

---

## Sektörel Uyarlama Fikri

ITCRAFT CMS’in temel altyapısı sektör bağımsızdır. Demo içerikler ve tema ayarları değiştirilerek farklı sektörlere hızlıca uyarlanabilir.

Örnek sektör presetleri:

- Teknoloji
- Hukuk
- Sağlık
- Lojistik
- Eğitim
- Danışmanlık
- Ajans
- İnşaat
- Turizm
- Finans

Her sektör için farklı:

- Tema renkleri
- Slider metinleri
- Hizmet örnekleri
- Demo sayfalar
- Footer metinleri
- SEO varsayılanları

hazırlanabilir.

---

## Önerilen Canlıya Alma Akışı

```text
1. Domain / hosting hazırlanır
2. Veritabanı oluşturulur
3. itcraft_cms.php domain ana dizinine yüklenir
4. https://domain.com/itcraft_cms.php çalıştırılır
5. Kurulum hazırlığı tamamlanır
6. /install ekranında DB ve admin bilgileri girilir
7. Demo içerikler yüklenir
8. Admin panelden müşteri bilgileri düzenlenir
9. SMTP ve SEO ayarları yapılır
10. Testler tamamlanır
11. Site canlıya alınır
```

---

## Sorun Giderme

### `/public` URL’de görünüyorsa

Root `.htaccess` dosyasının doğru yazıldığından emin olun.

### `/install` erişim hatası veriyorsa

`public/install/index.php` dosyasının mevcut olduğundan ve root `.htaccess` yönlendirmesinin aktif olduğundan emin olun.

### `vendor/autoload.php` bulunamıyor hatası

Kurulum paketinde `vendor/` klasörünün bulunduğundan emin olun.

### SQL import sırasında unique hatası

`database/dump/itcraft_cms_base.sql` dosyasının güncel olduğundan emin olun. Dummy verilerde `slug` alanları benzersiz olmalıdır.

### Admin girişi yapılamıyor

Kurulum sırasında oluşturulan admin e-posta ve şifresinin doğru olduğundan emin olun. `users` tablosunda kullanıcının `is_active` alanı varsa `1` olmalıdır.

---

## Lisans ve Kullanım

Bu proje ITCRAFT Bilgi Teknolojileri tarafından geliştirilmiş özel bir CMS altyapısıdır.

İzinsiz kopyalanması, dağıtılması, yeniden satılması veya farklı marka altında sunulması önerilmez.

---

## Geliştirici

**ITCRAFT Bilgi Teknolojileri**  
Sistem, Network, Güvenlik ve Web Altyapı Çözümleri

Website:

```text
https://www.itcraft.com.tr
```

GitHub:

```text
https://github.com/erkanbuyukbayraktaroglu/itcraft-cms
```

---

## Durum ve Yol Haritası

Proje aktif olarak geliştirilmektedir.

Planlanan geliştirmeler:

- Modül aktif/pasif yönetimi
- Kurulum sihirbazı geliştirmeleri
- Form builder
- Çoklu dil altyapısı
- Rol ve yetki yönetimi
- Gelişmiş yedekleme sistemi
- Müşteri teslim kontrol ekranı
- Sektörel tema presetleri
- Gelişmiş medya kütüphanesi
- Otomatik sitemap geliştirmeleri
- Daha kapsamlı SEO analizleri
- Sayfa builder altyapısı
- Paket lisanslama yapısı
- Güncelleme mekanizması

---

## Versiyon

```text
Initial Release
```

---

## Son Not

ITCRAFT CMS, hızlı kurulum, kolay yönetim ve farklı sektörlere uyarlanabilir kurumsal web altyapısı hedefiyle geliştirilmiştir.

Amaç, müşterinin teknik detaylarla uğraşmadan yayına alınabilir bir web yönetim paneline sahip olmasıdır.
