# Admin Panel Kullanım Kılavuzu

Bu doküman, web sitesi yönetim panelinin temel kullanım adımlarını anlatmak için hazırlanmıştır.

Panel üzerinden site ayarları, sayfalar, hizmetler, blog yazıları, medya dosyaları, slider alanları, menüler, iletişim mesajları ve e-posta bildirim ayarları yönetilebilir.

---

## İçindekiler

1. Admin Paneline Giriş  
2. Dashboard Ekranı  
3. Site Ayarları  
4. Medya Yönetimi  
5. Sayfa Yönetimi  
6. Hizmet Yönetimi  
7. Blog Yönetimi  
8. Slider Yönetimi  
9. Menü Yönetimi  
10. İletişim Mesajları  
11. SMTP / E-posta Bildirim Ayarları  
12. SEO Yönetimi  
13. Sitemap ve Robots  
14. Backup / Export  
15. Güvenlik Notları  
16. Sık Karşılaşılan Sorunlar  
17. Önerilen Görsel Ölçüleri  
18. İçerik Giriş Önerileri  
19. Yayına Alma Kontrol Listesi  
20. Destek Notları  

---

# 1. Admin Paneline Giriş

Admin paneline erişmek için tarayıcı üzerinden aşağıdaki adres kullanılır:

```text
https://domain-adresi.com/admin
```

Sistem kullanıcıyı otomatik olarak giriş ekranına yönlendirir.

Giriş ekranında kullanıcı adı/e-posta ve şifre bilgileri girilerek panele erişilir.

> Güvenlik için admin şifresi düzenli aralıklarla değiştirilmelidir.

---

# 2. Dashboard Ekranı

Giriş yaptıktan sonra ilk açılan ekran dashboard alanıdır.

Bu ekranda genel olarak şu bilgilere ulaşılabilir:

- Toplam sayfa sayısı
- Toplam hizmet sayısı
- Blog yazıları
- Gelen iletişim mesajları
- Hızlı erişim bağlantıları

Dashboard ekranı, paneldeki ana bölümlere hızlı geçiş yapmak için kullanılabilir.

---

# 3. Site Ayarları

Site ayarları bölümünden web sitesinin genel bilgileri yönetilir.

Bu bölümde düzenlenebilecek temel alanlar:

- Site adı
- Site sloganı
- Site açıklaması
- Header logosu
- Footer logosu
- Favicon
- İletişim e-posta adresi
- Telefon numarası
- Adres bilgisi
- Çalışma saatleri
- Sosyal medya bağlantıları
- SEO varsayılan bilgileri
- SMTP/e-posta gönderim ayarları

---

## 3.1 Logo Yönetimi

Header ve footer için ayrı logo tanımlanabilir.

Örnek kullanım:

```text
Header Logo: Açık zemin üzerinde görünür logo
Footer Logo: Koyu zemin üzerinde görünür logo
```

Medya yönetimi üzerinden logo yüklenip ilgili URL veya path alanına eklenebilir.

---

## 3.2 Renk Ayarları

Panelde tanımlı renk alanları üzerinden sitenin ana renkleri düzenlenebilir.

Genellikle şu alanlar kullanılır:

- Ana renk
- İkincil renk
- Vurgu rengi
- Arka plan rengi
- Yazı rengi
- Header rengi
- Footer rengi
- Buton rengi

Renk değişikliklerinden sonra site ön yüzü kontrol edilmelidir.

---

# 4. Medya Yönetimi

Medya yönetimi bölümü görsel ve dosya yüklemek için kullanılır.

Desteklenen örnek dosya türleri:

- JPG
- JPEG
- PNG
- WebP
- GIF
- SVG
- PDF
- Office dokümanları

Görsel yükleme sırasında sistem JPG, PNG ve WebP dosyalarını otomatik optimize eder. Büyük görseller maksimum genişlik/yükseklik değerlerine göre küçültülür.

---

## 4.1 Görsel Yükleme

1. Admin panelden **Medya Yönetimi** bölümüne girilir.
2. **Dosya Seç** alanından dosya seçilir.
3. **Yükle** butonuna basılır.
4. Sistem dosyayı yükler ve kullanılabilir URL bilgisini gösterir.

---

## 4.2 URL ve Path Kullanımı

Medya ekranında iki farklı kullanım bilgisi görülebilir:

```text
Tam URL:
https://domain-adresi.com/uploads/media/2026/04/ornek-gorsel.jpg

Relative Path:
uploads/media/2026/04/ornek-gorsel.jpg
```

Genel kullanımda **Tam URL** tercih edilebilir.

Site başka bir domaine taşınacaksa relative path daha taşınabilir bir kullanım sağlar.

---

## 4.3 Medyadan Seç Özelliği

Sayfa, hizmet, blog, slider veya ayarlar ekranlarında görsel alanlarının altında **Medyadan Seç** butonu bulunabilir.

Kullanım:

1. Görsel alanındaki **Medyadan Seç** butonuna tıklanır.
2. Açılan pencereden dosya seçilir.
3. Seçilen dosyanın URL/path bilgisi otomatik olarak ilgili alana yazılır.
4. Form kaydedilir.

---

# 5. Sayfa Yönetimi

Sayfa yönetimi bölümü kurumsal içerik sayfalarını oluşturmak için kullanılır.

Örnek sayfalar:

- Hakkımızda
- Hizmetler
- KVKK
- Gizlilik Politikası
- Sıkça Sorulan Sorular
- Kurumsal Bilgiler

---

## 5.1 Yeni Sayfa Ekleme

1. Admin panelden **Sayfalar** bölümüne girilir.
2. **Yeni Ekle** butonuna tıklanır.
3. Sayfa başlığı yazılır.
4. Slug alanı otomatik oluşturulur veya manuel düzenlenir.
5. İçerik editörü ile sayfa içeriği hazırlanır.
6. SEO alanları doldurulur.
7. Gerekirse görsel seçilir.
8. **Kaydet** butonuna basılır.

---

## 5.2 Slug Kullanımı

Slug, sayfanın URL yapısını belirler.

Örnek:

```text
Başlık: KVKK Danışmanlığı ve Uyum Süreci
Slug: kvkk-danismanligi-ve-uyum-sureci
URL: https://domain-adresi.com/kvkk-danismanligi-ve-uyum-sureci
```

Slug alanında Türkçe karakter, boşluk ve özel karakter kullanılmamalıdır. Sistem genellikle bunları otomatik dönüştürür.

---

## 5.3 İçerik Editörü

Sayfa içerik alanında zengin metin editörü bulunur.

Bu editör ile:

- Başlık eklenebilir
- Kalın/italik yazı kullanılabilir
- Liste oluşturulabilir
- Link eklenebilir
- Tablo eklenebilir
- Alıntı/blok içerik girilebilir

İçerik kaydedildikten sonra ön yüzde kontrol edilmelidir.

---

## 5.4 Sayfa Kopyalama

Sayfa listesinde bulunan **Kopyala** butonu ile mevcut bir sayfanın kopyası oluşturulabilir.

Kopyalanan sayfanın başlığına “Kopya” eklenir ve slug değeri benzersiz hale getirilir.

---

## 5.5 Sayfa Önizleme

Sayfa listesinde bulunan **Önizle** butonu ile ilgili sayfanın ön yüzde nasıl göründüğü yeni sekmede kontrol edilebilir.

---

# 6. Hizmet Yönetimi

Hizmet yönetimi bölümü, web sitesinde sunulan hizmetleri düzenlemek için kullanılır.

Örnek hizmetler:

- Danışmanlık
- Hukuki Hizmetler
- Kurumsal Destek
- Teknik Servis
- Proje Yönetimi

---

## 6.1 Yeni Hizmet Ekleme

1. **Hizmetler** bölümüne girilir.
2. **Yeni Ekle** butonuna tıklanır.
3. Hizmet başlığı yazılır.
4. Slug alanı kontrol edilir.
5. Hizmet açıklaması ve detay içeriği girilir.
6. Görsel varsa medya yönetimi üzerinden seçilir.
7. SEO alanları doldurulur.
8. Kaydedilir.

---

## 6.2 Hizmet Önizleme

Hizmet listesinde **Önizle** butonu ile hizmetin ön yüzdeki görünümü kontrol edilebilir.

Örnek URL yapısı:

```text
https://domain-adresi.com/hizmetler/hizmet-slug
```

---

## 6.3 Hizmet Kopyalama

Benzer içerikte yeni hizmet oluşturmak için **Kopyala** butonu kullanılabilir.

Bu özellik özellikle aynı şablonda ilerleyen hizmet sayfalarında zaman kazandırır.

---

# 7. Blog Yönetimi

Blog bölümü haber, makale, duyuru ve içerik yayınlamak için kullanılır.

---

## 7.1 Blog Kategorileri

Blog yazılarının düzenli görünmesi için önce kategori oluşturulabilir.

Örnek kategoriler:

- Haberler
- Duyurular
- Makaleler
- Rehberler
- Güncellemeler

Kategori oluştururken kategori adı ve slug bilgisi kontrol edilmelidir.

---

## 7.2 Blog Yazısı Ekleme

1. **Blog Yazıları** bölümüne girilir.
2. **Yeni Ekle** butonuna tıklanır.
3. Yazı başlığı yazılır.
4. Kategori seçilir.
5. Slug alanı kontrol edilir.
6. İçerik editörü ile yazı hazırlanır.
7. Kapak görseli seçilir.
8. SEO alanları doldurulur.
9. Yayın durumu kontrol edilir.
10. Kaydedilir.

---

## 7.3 Blog Önizleme

Blog listesinde bulunan **Önizle** butonu ile yazının ön yüzdeki görünümü kontrol edilebilir.

Örnek URL yapısı:

```text
https://domain-adresi.com/blog/yazi-slug
```

---

## 7.4 Blog Yazısı Kopyalama

Benzer formatta yeni yazı oluşturmak için **Kopyala** butonu kullanılabilir.

Sistem slug çakışmasını engellemek için yeni slug üretir.

---

# 8. Slider Yönetimi

Slider bölümü ana sayfadaki büyük görsel alanlarını yönetmek için kullanılır.

Slider eklerken dikkat edilmesi gerekenler:

- Görsel kaliteli olmalıdır.
- Önerilen ölçü: **1920 x 800 px**
- Başlık kısa ve net olmalıdır.
- Açıklama metni fazla uzun olmamalıdır.
- Buton metni ve linki doğru girilmelidir.
- Mobil görünüm mutlaka kontrol edilmelidir.

---

## 8.1 Slider Görseli Ekleme

1. Medya yönetiminden slider görseli yüklenir.
2. Slider ekleme/düzenleme ekranında görsel alanına medya URL’si seçilir.
3. Başlık, açıklama ve buton bilgileri girilir.
4. Sıralama ve aktif/pasif durumu kontrol edilir.
5. Kaydedilir.

---

# 9. Menü Yönetimi

Menü yönetimi ile header ve footer menüleri düzenlenebilir.

Menü öğelerinde genellikle şu bilgiler bulunur:

- Menü başlığı
- URL
- Hedef pencere
- Sıralama
- Aktif/pasif durumu
- Üst menü ilişkisi

---

## 9.1 Menü Link Örnekleri

```text
Anasayfa: /
Hakkımızda: /hakkimizda
Hizmetler: /hizmetler
Blog: /blog
İletişim: /iletisim
Dış bağlantı: https://ornek-domain.com
```

URL alanı doğru girilmelidir.

Site içi bağlantılarda `/` ile başlayan kısa yollar kullanılabilir.

---

# 10. İletişim Mesajları

Web sitesindeki iletişim formundan gönderilen mesajlar admin panelde listelenir.

Mesaj detayında şu bilgiler görülebilir:

- Ad soyad
- E-posta
- Telefon
- Konu
- Mesaj
- Gönderim tarihi
- IP adresi
- User-Agent
- Referer
- Kaynak URL

---

## 10.1 Mesaj Okundu/Okunmadı İşlemi

Mesajlar okundu veya okunmadı olarak işaretlenebilir.

Bu özellik, takip edilen mesajların ayrılmasını kolaylaştırır.

---

## 10.2 Mesaja E-posta ile Yanıt Verme

Mesaj detayında e-posta adresi varsa mailto bağlantısı üzerinden kullanıcıya yanıt verilebilir.

---

# 11. SMTP / E-posta Bildirim Ayarları

İletişim formu gönderildiğinde belirlenen adrese e-posta bildirimi gitmesi için SMTP ayarları yapılmalıdır.

Genel SMTP alanları:

```text
SMTP Host
SMTP Port
SMTP Kullanıcı Adı
SMTP Şifre
Şifreleme
Gönderen E-posta
Gönderen Adı
Form Bildirimi Gidecek E-posta
```

---

## 11.1 cPanel Mail Örneği

```text
SMTP Host: mail.domain-adresi.com
SMTP Port: 587
Şifreleme: TLS
SMTP Kullanıcı Adı: info@domain-adresi.com
Gönderen E-posta: info@domain-adresi.com
```

---

## 11.2 Gmail Kullanımı

Gmail kullanılacaksa normal hesap şifresi yerine uygulama şifresi gerekir.

SMTP ayarları kaydedildikten sonra iletişim formu ile test gönderimi yapılmalıdır.

---

# 12. SEO Yönetimi

Sayfa, hizmet ve blog içeriklerinde SEO alanları doldurulmalıdır.

Temel SEO alanları:

- Meta title
- Meta description
- Meta keywords
- OG image
- Slug

---

## 12.1 Meta Title

Sayfanın arama motorlarında görünen başlığıdır.

Kısa ve açıklayıcı olmalıdır.

---

## 12.2 Meta Description

Sayfanın kısa açıklamasıdır.

İçeriği özetlemelidir.

---

## 12.3 OG Image

Sosyal medya paylaşımlarında görünen görseldir.

Önerilen kullanım:

```text
1200 x 630 px
```

---

# 13. Sitemap ve Robots

Sistem sitemap ve robots çıktıları üretebilir.

Kontrol adresleri:

```text
https://domain-adresi.com/sitemap.xml
https://domain-adresi.com/robots.txt
```

Yeni sayfalar veya blog yazıları eklendikten sonra sitemap çıktısı kontrol edilebilir.

---

# 14. Backup / Export

Panelde veya ek araçlarla içerik yedeği alınabilir.

Yedek alınırken dikkat edilmesi gerekenler:

- Veritabanı yedeği alınmalıdır.
- Medya dosyaları ayrıca yedeklenmelidir.
- Site ayarları kontrol edilmelidir.
- Yedek dosyaları public klasörde bırakılmamalıdır.

---

# 15. Güvenlik Notları

Admin panel güvenliği için aşağıdaki noktalara dikkat edilmelidir:

- Admin şifresi güçlü olmalıdır.
- Gereksiz kullanıcı hesabı bırakılmamalıdır.
- Geçici PHP scriptleri işlem sonrası silinmelidir.
- SMTP şifreleri paylaşılmamalıdır.
- APP_DEBUG canlı ortamda kapalı olmalıdır.
- Public klasörde test/debug dosyaları bırakılmamalıdır.
- Yüklenen medya dosyaları kontrol edilmelidir.

---

# 16. Sık Karşılaşılan Sorunlar

## 16.1 Görsel görünmüyor

Kontrol edilecekler:

- Görsel URL’si doğru mu?
- Dosya medya klasöründe var mı?
- Tam URL mi relative path mi kullanılmış?
- Dosya adı Türkçe karakter veya boşluk içeriyor mu?

---

## 16.2 İletişim formu mail göndermiyor

Kontrol edilecekler:

- SMTP aktif mi?
- SMTP host doğru mu?
- Port doğru mu?
- Kullanıcı adı ve şifre doğru mu?
- Gönderen e-posta adresi SMTP hesabı ile uyumlu mu?
- Hosting firması SMTP çıkışına izin veriyor mu?

---

## 16.3 Sayfa 404 veriyor

Kontrol edilecekler:

- Slug doğru mu?
- Sayfa aktif mi?
- Menü linki doğru mu?
- Cache temizlenmiş mi?
- URL başında veya sonunda fazladan boşluk var mı?

---

## 16.4 Admin panel açılmıyor

Kontrol edilecekler:

- `/admin/login` adresi denenmeli
- Kullanıcı bilgileri doğru mu?
- Session ayarları çalışıyor mu?
- `.htaccess` dosyaları doğru mu?
- Hosting PHP sürümü uygun mu?

---

# 17. Önerilen Görsel Ölçüleri

| Kullanım Alanı | Önerilen Ölçü |
|---|---|
| Ana sayfa slider | 1920 x 800 px |
| Blog kapak görseli | 1200 x 630 px |
| Hizmet görseli | 1200 x 800 px |
| Sayfa üst görseli | 1600 x 700 px |
| Logo | SVG veya PNG |
| Favicon | 512 x 512 px |

---

# 18. İçerik Giriş Önerileri

İçerik girerken şu noktalara dikkat edilmelidir:

- Başlıklar kısa ve anlaşılır olmalıdır.
- Paragraflar çok uzun olmamalıdır.
- Hizmet sayfalarında net açıklama kullanılmalıdır.
- Blog yazılarında ara başlıklar eklenmelidir.
- Görseller sıkıştırılmış ve kaliteli olmalıdır.
- SEO alanları boş bırakılmamalıdır.
- Yayına almadan önce önizleme yapılmalıdır.

---

# 19. Yayına Alma Kontrol Listesi

Yeni içerik yayınlamadan önce:

- Başlık doğru mu?
- Slug doğru mu?
- İçerik kontrol edildi mi?
- Görsel yüklendi mi?
- SEO alanları dolduruldu mu?
- Menü gerekiyorsa eklendi mi?
- Önizleme yapıldı mı?
- Mobil görünüm kontrol edildi mi?

---

# 20. Destek Notları

Teknik destek gerektiren durumlarda aşağıdaki bilgiler hazırlanmalıdır:

- Hangi sayfada sorun oluştu?
- Hata mesajı var mı?
- İşlem hangi kullanıcı ile yapıldı?
- Sorun ne zaman başladı?
- Son yapılan değişiklik neydi?
- Ekran görüntüsü mevcut mu?

Bu bilgiler, sorunun daha hızlı analiz edilmesini sağlar.

---

# 21. Son Not

Bu kullanım kılavuzu, yönetim panelinin temel kullanımını anlatır.

Panelde yapılan değişikliklerin canlı siteye yansıdığı unutulmamalıdır.

Bu nedenle özellikle sayfa, menü, slider ve site ayarları değişikliklerinden sonra web sitesi hem masaüstü hem mobil görünümde kontrol edilmelidir.