<?php
/*
   |  ========================================================================  |
   |
   |  Authors        : Yusuf Tuğrul Kocaman
   |  Homepage       : http://www.penguencik.com
   |  Version        : 1.0
   |  Date           : 29-08-2013
   |  ========================================================================= |
*/

class Effects4Images { 
    
    /**
     * Program versiyonu.
     */
    const VERSION  = '1.0';
  
    /**
     * Programın yapılmasında katkıda bulunan kişiler ve anasayfaları.
     */  
    const AUTHOR   = 'Yusuf Tuğrul Kocaman';
    const URL      = 'http://www.penguencik.com';
    
    
    /**
     * Resim çıktının kalite ayarı.
     * ULTRA, BEST, NORMAL, LOW, SCRAP
     */
    const SCRAP    = 1;
    const LOW      = 2;
    const NORMAL   = 3;
    const BEST     = 4; 
    const ULTRA    = 5;

    /**
     * Resim genişlik
     */
    public $width  = 0;
   
    /**
     * Resim yükselik
     */ 
    public $height = 0;

    /**
     * Resim türü
     */
    public $type;
    
    /**
     * Ana resim kaynağı.
     */
    public $raw_source;
    
    /**
     * İşlem görmüş resim kaynağı.
     */
    public $processed_source;
    
    /**
     * Resim damgası.
     */
    public $watermark_source;
    
    /**
     * Damga pozisyonu
     */
    public $watermark_position = 5; // Neden 5? Numpad'ine bak!
    
    /**
     * Damga genişliği
     */
    public $watermark_width = 0;
    
    /**
     * Damga boyu
     */ 
    public $watermark_height = 0;
    
    # ============ MAGIC METHODS ============ #
    public function __toString() {
        // TODO: Do something.
    }
    
    # CONSTRUCT
    public function __construct($image) {
        $load_information = @getimagesize($image);
        
        if( $load_information ) { # Yüklenme durumunda aşağıdaki işlemleri yap.
            $this->width  = $load_information[0];
            $this->height = $load_information[1];
            $this->type   = $load_information[2];
            
            // İzin verilen türler için switch bloğu.
            switch( $this->type ) {
                case IMAGETYPE_PNG:
                    $this->raw_source = @imagecreatefrompng($image);
                    $this->processed_image = $this->raw_source;
                    $this->autoResize($this->width);
                break;
                
                case IMAGETYPE_JPEG;
                    $this->raw_source = @imagecreatefromjpeg($image);
                    $this->processed_image = $this->raw_source;
                    $this->autoResize($this->width);
                break;
                
                default:
                    throw new EffectException('Yüklemeye çalıştığınız dosya izin verilen formatlarda değil. (PNG ve JPEG)');
            }            
            
            
        } else { # Yüklenmemişse bir istisna oluştur ve fırlat.
            // Dosya yüklenmiş mi?
            if( $image ) {
                throw new EffectException('Yüklediğiniz dosya bir resim değil!');
            } else {
                throw new EffectException('Lütfen bir dosya yükleyin!');
            }
        }
        
        return $this;
    }
    
    # DESTRUCT
    public function __destruct() {
        @imagedestroy($this->raw_source);
        @imagedestroy($this->processed_image);
        @imagedestroy($this->watermark_source);
    }
    
    # CLONE
    public function __clone() {
    
    }

    # ============ /MAGIC METHODS ============ #

    public function getWidth() {
        return (int) $this->width;
    }
    
    public function getHeight() {
        return (int) $this->height;
    }
    # ============ RESIZING ============ #
    public function resize($width, $height) {
        $width   = (int) $width;
        $height  = (int) $height;
    
        $process = imagecreatetruecolor($width, $height);
        
        imagealphablending($process, false);
        imagesavealpha($process, true);
        
        // Saydam bir dikdörtgen oluşturalım.
        $transparency = imagecolorallocatealpha($process, 0, 0, 0, 127);
        imagefilledrectangle($process, 0, 0, $width, $height, $transparency);
        
        // Ve diğer resme kopyalayalım.
        imagecopyresampled($process, $this->raw_source, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        
        $this->processed_image = $process;
        $this->width = imagesx($process);
        $this->height = imagesy($process);
        return $this;
    }
    
    public function autoResize($width = null, $height = null) { 
        $ratio = null;
        $new_width = null;
        $new_height = null;
        
        if( $width ) {
            $ratio = $width / $this->getWidth();
            $new_height = round($this->getHeight() * $ratio);
            $new_width = $width;
        } elseif( $height ) {
            $ratio = $height / $this->getHeight();
            $new_height = $height;
            $new_width = round($this->getWidth() * $ratio);
        } elseif( $width and $height ) {
            $ratio = $width / $this->getWidth(); // Genişliği severim, öyleyse genişliğe göre oran alayım.
            $new_height = round($this->getHeight() * $ratio);
            $new_width = $width;    
        }
        
        $process = imagecreatetruecolor($new_width, $new_height);
        
        imagealphablending($process, false);
        imagesavealpha($process, true);
        
        // Saydam bir dikdörtgen oluşturalım.
        $transparency = imagecolorallocatealpha($process, 0, 0, 0, 127);
        imagefilledrectangle($process, 0, 0, $new_width, $new_height, $transparency);
        
        // Ve diğer resime kopyalayalım.
        imagecopyresampled($process, $this->raw_source, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        
        $this->processed_image = $process;
        $this->width = imagesx($process);
        $this->height = imagesy($process);
        return $this;
    }
    # ============ /RESIZING ============ #
    
    # ============ EFFECTS ============ #
    public function gaussianBlur($times = 1) {
        $times = (int) $times;
        $gaussian = array(
            array(1.0, 2.0, 1.0),
            array(2.0, 4.0, 2.0),
            array(1.0, 2.0, 1.0)
        );
        $filter = array_sum(array_map('array_sum', $gaussian));
        $divisor = 0;
        
        for($i = 0; $i<$times; $i++) {
            imageconvolution($this->processed_image, $gaussian, $filter, $divisor);
        }
        return $this;
    }
    
    public function blur($times = 1) {
        $times = (int) $times;
        for($i = 0; $i<$times; $i++) {
            imagefilter($this->processed_image, IMG_FILTER_SELECTIVE_BLUR);
        }
        return $this;
    }
    
    public function sharpen() {
        // 3'lü matris değerleri
        $sharpen = array(
            array(-1.2, -1 , -1.2),
            array(-1, 20 , -1),
            array(-1.2, -1 , -1.2)
        );
        // Toplam değer
        $filter = array_sum(array_map('array_sum', $sharpen)); // Toplam değeri
        $divisor = 0;
        
        imageconvolution($this->processed_image, $sharpen, $filter, $divisor);
        return $this;
    }
    
    public function sepian(){
	    for($_x = 0; $_x < $this->getWidth(); $_x++) {
		    for($_y = 0; $_y < $this->getHeight(); $_y++) {
		
			    $rgb = imagecolorat($this->processed_image, $_x, $_y); // O pikselin renkleri.
			    $alpha = imagecolorsforindex($this->processed_image, $rgb); // Alpha kanalı için
			    $r = ($rgb >> 16) & 0xFF; 
			    $g = ($rgb >> 8) & 0xFF;
			    $b = $rgb & 0xFF;
			
			    $y = $r * 0.299 + $g * 0.587 + $b * 0.114; // Oran
			    $i = 0.15 * 0xFF;
			    $q = -0.001 * 0xFF;
			
			    $r = $y + 0.956 * $i + 0.621 * $q;
			    $g = $y - 0.272 * $i - 0.647 * $q;
			    $b = $y - 1.105 * $i + 1.702 * $q;
			    
			    // Yeşil, kırmızı veya mavi en fazla 0xFF - 1 olabilir. Yani en fazla 255.
			    if( $r<0 || $r>0xFF ) {
			        $r = ($r<0) ? 0 : 0xFF;
                }
                
			    if( $g<0 || $g>0xFF ) {
			        $g = ($g<0) ? 0 : 0xFF;
			    }
			    
			    if( $b<0 || $b>0xFF ) { 
			        $b = ($b<0) ? 0 : 0xFF;
			    }
			    
			    $color = imagecolorallocatealpha($this->processed_image, $r, $g, $b, $alpha['alpha']);
			    imagesetpixel($this->processed_image, $_x, $_y, $color); // Rengini ayarla.
		    }
	    }
	    return $this;
    }
    
    public function grayscale() {
        for($_x = 0; $_x < $this->getWidth(); $_x++) {
            for($_y = 0; $_y < $this->getHeight(); $_y++) {
    
                $rgb = imagecolorat($this->processed_image, $_x, $_y);
                $alpha = imagecolorsforindex($this->processed_image, $rgb); // Alpha
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $grey = (int) ( ($r+$g+$b) / 3);
                $color = imagecolorallocatealpha($this->processed_image, $grey, $grey, $grey, $alpha['alpha']); // Gri skalası için hepsinin gri olması gerek.
                
                imagesetpixel($this->processed_image, $_x, $_y, $color);
            }
        }
        return $this;
    }
    
    public function negative() {
        imagefilter($this->processed_image, IMG_FILTER_NEGATE);
        return $this;
    }
    
    public function sketchy() {
        imagefilter($this->processed_image, IMG_FILTER_MEAN_REMOVAL);
        return $this;
    }
    
    public function roundCorners($radius = 4 ) {
        $q = 15; // Her şeyi 15'le çarpıp ardından boyutlandırma yapmak bize pürüzsüz köşeler kazandıracaktır.
        $radius = $radius * $q;
        
        // Resimde bulunmayan benzersiz bir renk bul. Belki performansı düşürür ama mükemmel sonuç aldırır.
        do{
            $r = rand(0,255);
            $g = rand(0,255);
            $b = rand(0,255);
        } while (imagecolorexact($this->processed_image, $r, $g, $b) < 0); 
        
        $n_width = $this->getWidth() * $q;
        $n_height = $this->getHeight() * $q;
        // Tuval oluştur.
        $canvas = imagecreatetruecolor($n_width, $n_height);
        $transparency = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefilledrectangle($canvas, 0, 0, $n_width, $n_height, $transparency); // Tamamen saydam.
        
        imagefill($canvas, 0, 0, $transparency);
        
        // Ana resmi büyük resme kopyala.
        imagecopyresampled($canvas, $this->processed_image, 0, 0, 0, 0, $n_width, $n_height, $this->getWidth(), $this->getHeight());
        
        // Sol üst köşe
        imagearc($canvas, $radius-1, $radius-1, $radius*2, $radius*2, 180, 270, $transparency); // $radius*2 = Çap, $radius-1 = X veya Y başlangıç noktası, 180->270 = 180 dereceden 270'e kadar çiz
        imagefilltoborder($canvas, 0, 0, $transparency, $transparency); // Resimde olmayan renk sınır oluyor.
        // Sağ üst köşe
        imagearc($canvas, $n_width-$radius, $radius-1, $radius*2, $radius*2, 270, 0, $transparency); // $n_width-$radius = Sağ üst köşe için yarıçapa kadar alınan sınırç
        imagefilltoborder($canvas, $n_width-1, 0, $transparency, $transparency);
        // Sol alt köşe
        imagearc($canvas, $radius-1, $n_height-$radius, $radius*2, $radius*2, 90, 180, $transparency);
        imagefilltoborder($canvas, 0, $n_height-1, $transparency, $transparency);
        // Sağ alt köşe
        imagearc($canvas, $n_width-$radius, $n_height-$radius, $radius*2, $radius*2, 0, 90, $transparency);
        imagefilltoborder($canvas, $n_width-1, $n_height-1, $transparency, $transparency);
        
        imagealphablending($canvas, true);
        imagecolortransparent($canvas, $transparency);
    
        // Orijinal boyutuna geri döndür.
        $origin = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagealphablending($origin, false);
        imagesavealpha($origin, true);
        imagefilledrectangle($origin, 0, 0, $this->getWidth(), $this->getHeight(), $transparency);
        imagecopyresampled($origin, $canvas, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), $n_width, $n_height); // Diğerine kopyala
        $this->processed_image = $origin;        
        return $this;
    }
    # ============ /EFFECTS ============ #
    
    # ============ WATERMARK ============ #
    public function setWatermark( $watermark = null ) {
        $info = @getimagesize($watermark);
        $type = $info[2]; // Tip
        
        if( $info ) {
            switch( $type ) {
                case IMAGETYPE_PNG:
                    $this->watermark_source = @imagecreatefrompng($watermark);
                    $this->watermark_width = $info[0];
                    $this->watermark_height = $info[1];
                    
                break;
                
                case IMAGETYPE_JPEG:
                    throw new EffectException('Bu resim türü saydamlıkta ve resimlerin üzerine yapıştırmada sıkıntı çıkardığı için resim damgası olarak kullanılamaz!');
                break;
                
                default:
                    throw new EffectException('Resim damgası olarak seçtiğiniz resmin türü PNG olmalıdır!');
                break;
            }
            
        } else { // Resim damgası belirtilmemiş.
            throw new EffectException('Resim damgasına ulaşılamıyor: Doğru bir adres yazdığınızdan emin misiniz?');
        }
        
        return $this;
    }
    
    public function setWatermarkPosition( $position ) {
        $this->watermark_position = (int) $position;
        return $this;
    }
    
    protected function getPosition() {
        $pos = array(
            'x' => 0,
            'y' => 0
        );
        
        switch( $this->watermark_position ) {
            case 1: // Sol üst
                // Formül: 10 yukarıdan margin, 10 soldan margin
                $pos['x'] = 10;
                $pos['y'] = 10;
            break;
            
            case 2:// Üst orta
                // Formül: 10 yukarıdan margin, soldan resmin genişliğinin yarısından watermark genişliğinin yarısı çıkarılmış şekilde margin.
                $pos['x'] = round($this->getWidth() / 2) - round($this->watermark_width / 2); // 110px ise 55 sola gittiğinde ortalanır.
                $pos['y'] = 10;
            break;
            
            case 3:// Sağ üst
                // Formül: 10 yukarıdan margin, sağdan 10 margin. Yani resim genişliğinden watermark genişliği + 10 çıkarılacak.
                $pos['x'] = $this->getWidth() - ($this->watermark_width + 10);
                $pos['y'] = 10;
            break;
            
            case 4: // Orta sol
                // Formül: Resim yüksekliğinin yarısından damga yükseliğinin yarısı çıkartılacak üstten margin olacak, soldan margin 10.
                $pos['x'] = 10;
                $pos['y'] = round($this->getHeight() / 2) - round($this->watermark_height / 2);
            break;
            
            case 5: // Merkez
                // Formül: Resim yüksekliğinin yarısından damga yüksekliğinin yarısı çıkarılır, aynı işlem genişliğe de yapılır.
                $pos['x'] = round($this->getWidth() / 2) - round($this->watermark_width / 2);
                $pos['y'] = round($this->getHeight() / 2) - round($this->watermark_height / 2);
            break;
            
            case 6: // Orta sağ
                // Formül: Yükseliğin yarısından damga yükseliğinin yarısını çıkar, sağdan 10 margin.
                $pos['x'] = $this->getWidth() - ($this->watermark_width + 10);
                $pos['y'] = round($this->getHeight() / 2) - round($this->watermark_height / 2);
            break;
            
            case 7: // Alt sol
                // Formül: Soldan 10 margin, alttan 10.
                $pos['x'] = 10;
                $pos['y'] = $this->getHeight() - ($this->watermark_height + 10);
            break;
            
            case 8: // Alt merkez
                $pos['x'] = round($this->getWidth() / 2) - round($this->watermark_width / 2);
                $pos['y'] = $this->getHeight() - ($this->watermark_height + 10);
            break;
            
            case 9: // Alt sağ
                // Formül: Sağdan 10 margin, alttan 10 margin.
                $pos['x'] = $this->getWidth() - ($this->watermark_width + 10);
                $pos['y'] = $this->getHeight() - ($this->watermark_height + 10);
            break;
            default:
                throw new EffectException('Lütfen resim damgası için bir pozisyon belirleyiniz [1-9].'.$this->watermark_position);
            break;
            // dafuq
        }
        
        return $pos;
    }
    
    public function doWatermark() {
        $pos = $this->getPosition();
        imagealphablending($this->processed_image, true);
        imagesavealpha($this->processed_image,true);
        imagecopy($this->processed_image, $this->watermark_source, $pos['x'], $pos['y'], 0, 0, $this->watermark_width,$this->watermark_height); 
        
        return $this;
    }
    # ============ /WATERMARK ============ #
    public function out($png = true) {
        if( $png ) {
            imagepng($this->processed_image);
        } else {
            imagejpeg($this->processed_image,null,100);
        }
    }
   
    public function save($path,$const = self::ULTRA) {
        $quality = null;
        switch($const) {
            case self::ULTRA:
                $this->sharpen();
                $quality = 100;
            break;
            
            case self::BEST:
                $quality = 80;
            break;
            
            case self::NORMAL:
                $quality = 60;
            break;
            
            case self::LOW:
                $quality = 40;
            break;
            
            case self::SCRAP:
                $quality = 20;
            break;
            
            default:
                throw new EffectException('Kaydederken kalite belirlemek istiyorsanız lütfen sınıf değerlerini kullanınız!');
            break;
        }
        
        if( is_null($path) or $path == '' ) {
            throw new EffectException('Kayıt etmek için bir yol (PATH) belirtmelisiniz!');
        }
        $quality = round(($quality/100) * 9);
        $quality = abs(9 - $quality);
        
        imagepng($this->processed_image(), $path, $quality):
        return $this; // Sonrasında "out" ile ekran çıktısı yapabilmek için gerekli.
    }

}

class EffectException extends Exception {
  public function errorMessage() {
    $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
    return $errorMsg;
  }
}
?>
