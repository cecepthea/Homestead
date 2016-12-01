<?php
Class custom_resize {

    private $image;
    private $width;
    private $height;
    private $filename;
    private $imageResized; // 处理完的图片

    public function __construct($fileName){
        $this->filename = $fileName;
        $this->image = $this->openImage($fileName);
        if($this->image !== false){
            $this->width  = imagesx($this->image);
            $this->height = imagesy($this->image);
        }else{
            $this->width  = false;
            $this->height = false;
        }

    }

    private function openImage($file){
        $type_value = exif_imagetype($file);
        $img = false;
        if($type_value == IMAGETYPE_GIF){
            $img = @imagecreatefromgif($file);
        }
        if($type_value == IMAGETYPE_JPEG){
            $img = @imagecreatefromjpeg($file);
        }
        if($type_value == IMAGETYPE_PNG){
            $img = @imagecreatefrompng($file);
        }
        return $img;
    }

    public function resizeImage($option="portrait"){

        // $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
        // $optimalWidth  = $optionArray['optimalWidth'];
        // $optimalHeight = $optionArray['optimalHeight'];
        // $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        // imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);

        $angle = 90;
        if($option == 'portrait'){
            // 横转竖
        }else{
            // 竖转横
        }

        if($this->width !== false && $this->height !== false && $this->width > $this->height){
            $this->imageResized = imagerotate($this->image, $angle, 0);
            $this->saveImage($this->filename, 100); // savePath, imageQuality
        }

        // $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
        // dst_image , src_image , dst_x , dst_y , src_x , src_y , dst_w , dst_h , src_w , src_h
        // imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);

        // if ($option == 'crop') {
        //     $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        // }
    }

    public function saveImage($savePath, $imageQuality="100"){
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, $imageQuality);
                }
                break;
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;
            case '.png':
                $scaleQuality = round(($imageQuality/100) * 9);
                $invertScaleQuality = 9 - $scaleQuality;
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;
            default:
                break;
        }
        imagedestroy($this->imageResized);
    }

    public function show($savePath){
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);
        switch($extension) {
            case '.jpg':
            case '.jpeg':
                header('Content-type: image/jpeg');
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized);
                }
                break;
            case '.gif':
                header('Content-type: image/gif');
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized);
                }
                break;
            case '.png':
                header('Content-type: image/png');
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized);
                }
                break;
            default:
                break;
        }
        imagedestroy($this->imageResized);
    }

    private function getDimensions($newWidth, $newHeight, $option){
        switch ($option) {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function getSizeByFixedHeight($newHeight){
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }

    private function getSizeByFixedWidth($newWidth){
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }

    private function getSizeByAuto($newWidth, $newHeight){
        if ($this->height < $this->width) {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }elseif ($this->height > $this->width){
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }else{
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function getOptimalCrop($newWidth, $newHeight){
        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

        $crop = $this->imageResized;

        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }
}