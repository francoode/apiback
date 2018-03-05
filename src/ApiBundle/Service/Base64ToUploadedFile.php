<?php

/**
 * Copyright (c) 2015 - Kodear
 */

namespace ApiBundle\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service con helpers Generales de Users
 *
 * @package ApiBundle\Service
 */
class Base64ToUploadedFile
{

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Transforma base64 a UploadedFile
     *
     * @param  string $base64Image
     * @param  string $originalFileName Opcional si se quiere preservar el nombre original de la imagen
     *
     * @return UploadedFile
     */
    public function transformBase64($base64Image, $originalFileName = null)
    {
        $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $fileName = ($originalFileName ? $originalFileName : uniqid());
        $tempPath = $this->container->getParameter('kernel.cache_dir').'/images';
        if (!is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        $tempFile = $tempPath.'/'.$fileName;
        file_put_contents($tempFile, $img);

        try {
            $mimeType = getimagesize($tempFile)['mime'];
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "The file uploaded was not a valid image file.");
        }
        $size = filesize($tempFile);

        if (!$originalFileName) {
            // Viene sin la extension, agregarla en base al mime
            $tempFile .= '.'.$this->getFileExtension($mimeType);
            file_put_contents($tempFile, $img);
            $fileName .= '.'.$this->getFileExtension($mimeType);
        }

        $uploadedFile = new UploadedFile($tempFile, $fileName, $mimeType, $size, null, true);

        return $uploadedFile;
    }

    /**
     * Image resize
     * @param File $file
     */
    public function createSquareImage(File $file)
    {
        $imagine = new Imagine();
        $image = $imagine->open($file->getRealPath());

        $width = $image->getSize()->getWidth();
        $height= $image->getSize()->getHeight();
        $startX = $startY = 0;
        if ($width > $height) {
            $startX = ($width - $height) / 2;
            $size = $height;
        } else {
            $startY = ($height - $width) / 2;
            $size = $width;
        }

        $output = $file->getPath().'/square_'.$file->getFilename();
        $image
            ->crop(new Point($startX, $startY), new Box($size, $size))
            ->save($output, ['jpeg_quality' => 90]);
    }

    /**
     * Elimina archivos del cache
     */
    public function removeFilesFromCache()
    {
        $tempPath = $this->container->getParameter('kernel.cache_dir').'/images/*';
        $files = glob($tempPath); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }
    }

    /**
     * Devuelve extension en base al mimeType
     *
     * @param  string $mimeType
     *
     * @return string
     */
    private function getFileExtension($mimeType)
    {
        switch ($mimeType) {
            case 'image/gif':
                $extension = 'gif';
                break;
            case 'image/png':
                $extension = 'png';
                break;
            case 'image/jpeg':
                $extension = 'jpg';
                break;

            default :
                throw new HttpException(Response::HTTP_BAD_REQUEST, "The file uploaded was not a valid image file.");
                break;
        }

        return $extension;
    }
}
