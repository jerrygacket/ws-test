<?php


class ThumbGenerator
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * @return string[]
     */
    public static function getSizes(): array
    {
        $db = db::getInstance();
        $sql = 'SELECT * FROM sizes;';
        $sizes = $db->Query($sql);
        $sizes = array_combine(
            array_column($sizes, 'name'),
            array_column($sizes, 'size')
        );

        return $sizes;
        //return ['big' => '800 * 600', 'med' => '640 * 480', 'min' => '320 * 240', 'mic' => '150 * 150'];
    }


    /**
     * @param $file String Filename
     * @return bool
     */
    public static function generate(string $file): bool
    {
        $result = false;

        $oldFile = 'gallery' . self::DS . $file . '.jpg';

        try {
            $imagick = new Imagick($oldFile);
        } catch (ImagickException $e) {
            echo $e->getMessage().PHP_EOL;
            return false;
        }

        foreach (self::getSizes() as $sizeName => $sizes) {
            $thumbDir = 'cache' . self::DS . $file . self::DS . $sizeName;
            $newFile = $thumbDir . self::DS . $file . '.jpg';

            if (!is_dir($thumbDir) && !mkdir($thumbDir, 0755, true)) {
                echo 'проблемы с созданием каталога '.$thumbDir.PHP_EOL;
                continue;
            }

            list($width, $height) = explode(' * ', $sizes);
            $w = $imagick->getImageWidth();
            $h = $imagick->getImageHeight();

            if ($w < $h) {
                $resize_w = round($w * $height / $h);
                $resize_h = $height;
            }
            else {
                $resize_w = $width;
                $resize_h = round($h * $width / $w);
            }
            // уменьшаем и ужимаем картинку, оптимизируем для быстрой загрузки
            $exec = 'convert '.$oldFile.' -strip -quality 85 -interlace JPEG -colorspace RGB -resize '.$resize_w.'x'.$resize_h.' '.$newFile;
            exec($exec);

            /* этот код рабочий. Видимо из-за обновления php появился баг.
            * при сохранении выдает Ошибку сегментации
            $imagick->setCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setCompressionQuality(85);
            $imagick->setImageFormat('jpeg');
            $imagick->stripImage();
            $imagick->setInterlaceScheme(Imagick::INTERLACE_JPEG);
            $imagick->transformImageColorspace(Imagick::COLORSPACE_SRGB);
            $imagick->resizeImage($resize_w, $resize_h, imagick::FILTER_LANCZOS, 1);

            $result = ($result && $imagick->writeImage($newFile));
            if (!$result) {
                echo 'проблемы с сохранением файла ' . $newFile . PHP_EOL;
            }
            */
        }

        return $result;
    }

    public static function getGallery($image, $screenSize) {

    }
}
