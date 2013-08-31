[effects4images](http://www.penguencik.com/)
==================================================

# Effects4Images
Effects4Images, PNG ve JPEG resimlerinize efekt veren, onları boyutlandırmanızı sağlayan mükemmel bir PHP sınıfıdır.

Effects4Images'ın desteklediği birden çok efekt vardır. Bu efektlerle kombinasyonlar oluşturabilir, ya da tek efekt
kullanarak resimlerinizi daha cazip hale getirebilir, ya da belirli yerler için tekrar boyutlandırabilirsiniz. Ayrıca
Effects4Images orantılı boyutlandırmayı da destekler!

## Yazar
[Yusuf Tuğrul Kocaman](http://www.penguencik.com) bu sınıfı kişisel kullanımı için yazmış, sonra da tüm dünyaya yararlı
olabileceğini düşünerek Github'da paylaşmaya karar vermiştir. Fakat bu sınıfı yazarken az kalsın bilgisayarının işlemcisini yakıyormuş. Debug yaparken <strong>Development Box'ının disk alanı dolmuş</strong> ve <em>gedit'te düzenleme yapamaması sonucu cinler tepesine çıkmıştır.</em>. Toplamda 3-4 saat anca uğraşmış ama [Twitter](http://www.twitter.com/kulturlupenguen), [Facebook](https://www.facebook.com/yusuftugrul.kocaman.1) gibi sosyal ağlarda yazdığı yazılarla sanki 24 saat uğraşmış gibi gözükmektedir.

## Sınıfın Kullanımı
Sınıfı kullanmak oldukça basit. Tek yapmanız gereken şey `include 'Effects4Images.php'` diyerek sınıfı kullanacağınız dosyaya yüklemek. Ardından bir resim yüklemeniz gerekiyor.
```php
<?php
include 'Effects4Images.php';
$effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
?>
```
Resim yüklenmezse veya istenilen formatlarda değilse `png/jpeg` sınıf bir `EffectException` fırlatır. Bu yüzden `try-catch` arasına yazmak daha doğru olacaktır.

```php
<?php
include 'Effects4Images.php';
try {
	$effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
} catch(EffectException $e) {
	echo $e->getMessage();
}
?>
```
### Resim Boyutlandırma
Resim boyutlandırma için iki seçeneğiniz var: Genişlik ve yükseliği el ile girmek, ya genişliği ya da yükseliği girerek orantılı boyutlandırma yapmak. İlk seçeneğimiz için basit sözdizimi şöyle:
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->resize(400,400)->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Otomatik orantılı olarak boyutlandırmak isterseniz iki adet seçeneğiniz var: Ya ene göre, ya boya göre oranlamak:
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->autoResize(400)->out();
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Yukarıdaki örnek genişliğe göre boyutlandırır. Boya göre boyutlandırmak için:
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->autoResize(null,400)->out();
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Blur
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->blur()->out();
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Üst üste `blur` yapılmak isteniyorsa fonksiyona `integer` bir değer verilerek yapılabilir:
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->blur(15)->out(); // 15 kez blurlar.
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Gaussian Blur
`Gauss` yöntemiyle `Blur` eklemek.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->gaussianBlur()->out();
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Üst üste `Blur` işlemi için.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->gaussianBlur(15)->out(); // 15 kez blurlar.
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Sepian Efekti
Tarihin tozlu sayfalarını çok mu seviyorsunuz? Saman kağıdı rengine benzer efekt veren bu fonksiyon şu şekilde kullanılır.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->sepian()->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
### Sharpen Efekti
Resimlerin keskinliğini arttırmak için harika bir efekt.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->sharpen()->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Grayscale Efekti
Nostaljik bir efekt.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->grayscale()->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Sketchy Efekti
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->sketchy()->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
### Negative Efekti
Ters renkler, ters hayat.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->negative()->out(true);
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Not: İki kez üst üste bu efekti uygularsanız orijinal resmi elde edersiniz. ( -- = +)

### Rounded Corners
Bu efekt çok dikkatli kullanılmalıdır. Oldukça güzel sonuçlar vermesi açısından 15 kere büyütülüp öyle işlem yapılır, bu yüzden çok yüksek boyuttaki resimlere işlem uygulamanızı önermiyorum. 256MB'tan fazla RAM'e sahip olması gerekir PHP'nin.
```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->roundCorners(20)->out(true); // Yarıçap uzunluğu 20 piksel.
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```
Resim kenarlarında yarıçap uzunluğu 20 piksel olan daireler oluşur ve artan alan saydamlaştırılır.
### Watermark Ekleme
Resmin üzerine sitenizin logosunu vurmak istiyorsanız bu işlem tam size göre. Fonksiyonun tek bir argümanı var: Watermark pozisyonu. Bu 1 ile 9 arasında değişen `int` veriden oluşuyor.
* 1- Üst sol
* 2- Üst merkez
* 3- Üst sağ
* 4- Merkez sol
* 5- Resim merkezi
* 6- Merkez sağ
* 7- Alt sol
* 8- Alt merkez
* 9- Alt sağ
Numpadinizi bir resim gibi düşünüp kullanabilirsiniz. Kullanımı:

```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->setWatermark('http://files.kulturlupenguen.com/logo.png') // Watermark için bir PNG resim adresi.
		->setWatermarkPosition(9) // Sağ altta olmasını istiyorum.
		->doWatermark() // Watermark işlemini gerçekleştir.
		->out(true); // Başka işlemler yapılabilir: Boyutlandırma, efekt verme vs.
		
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

### Resim Kaydetme
Resim kaydetmek için bir dosya adı ve yol belirtmeniz yeterlidir. İsterseniz kalite de belirtebilirsiniz. Belirtebileceğiniz kaliteler:
* Effects4Images::ULTRA
* Effects4Images::BEST
* Effects4Images::NORMAL
* Effects4Images::LOW
* Effects4Images::SCRAP
`save` metodu ile kullanabilirsiniz. Örnek kod aşağıdadır.

```php
<?php
include 'Effects4Images.php';
try {
	header('Content-Type:image/png');
    $effects = new Effects4Images('http://www.hdwallpapers3d.com/wp-content/uploads/Top-Hd-Wallpapers-2.jpg');
	$effects->setWatermark('http://files.kulturlupenguen.com/logo.png') // Watermark için bir PNG resim adresi.
		->setWatermarkPosition(9) // Sağ altta olmasını istiyorum.
		->doWatermark() // Watermark işlemini gerçekleştir.
		->save('out.png',Effects4Images::ULTRA) // İkinci argüman opsiyoneldir.
		->out(true); // Başka işlemler yapılabilir: Boyutlandırma, efekt verme vs.
		
} catch(EffectException $e) {
	header('Content-Type:text/plain;charset="UTF-8"');
    echo $e->getMessage();
}
?>
```

#### Notlar
* Hoş efektler için, ilk önce `sharpen` filtresini uygulayıp ardından efekti uygularsanız daha güzel sonuç elde edersiniz.
* Sınıf daha geliştirilme düzeyinde, sadece ekran çıktısı veriyor ama bunu geliştirmek sizin elinizde, `out` fonksiyonunu düzenleyerek yapabilirsiniz.
* Ultra, best, normal gibi ayarlar şu anlık desteklenmemektedir (29 Ağustos 2013)
* Eğer sınıfı sevdiyseniz [bana bağış](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=yusuftugrul%40kulturlupenguen%2ecom&lc=TR&item_name=Yaz%c4%b1l%c4%b1m%20Geli%c5%9ftiricili%c4%9fi&currency_code=TRY&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest) yapabilirsiniz :) 
