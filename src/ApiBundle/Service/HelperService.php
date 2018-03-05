<?php
namespace ApiBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Service con helpers Generales
 *
 * @package ApiBundle\Service
 */
class HelperService extends EntitiesService
{
    /**
     * Arma el Location header para las respuestas de la API
     *
     * @param  integer $id
     * @param  string  $format
     *
     * @return array
     */
    public function getLocationHeader($id, $format = 'json')
    {
        $routeOptions = array(
            'id' => $id,
            '_format' => $format,
        );

        return $routeOptions;
    }

    public function getHeaders($id, $method)
    {
        return [
            'Location' => $this->container->get('router')->generate($method, array('id' => $id), true),
        ];
    }

    /**
     * Funcion encargada de subir archivos encodeados en Base64
     * activando manualmente el bundle VichUploader
     *
     * @param  mixed   $entity
     * @param  string  $base64Image
     * @param  string  $originalFileName
     * @param  boolean $andFlush
     */
    public function uploadFiles($entity, $base64Image, $originalFileName = null, $andFlush = true)
    {
        if ($base64Image) {
            $uploadedFile = $this->container->get('kodear_api.base64_service')->transformBase64(
                $base64Image,
                $originalFileName
            );
            $entity->setFile($uploadedFile);

            $this->em->persist($entity);
            if ($andFlush) {
                $this->em->flush();
            }
        }
    }

    /**
     * Devuelve link de la imagen
     *
     * @param  mixed  $entity
     *
     * @return string
     */
    public function getFileLink($entity)
    {
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

        if ($helper->asset($entity, 'file')) {
            $fileName = $helper->asset($entity, 'file');

            return $this->container->get('request')->getSchemeAndHttpHost().$fileName;
        }

        return null;
    }

    /**
     * @param mixed $entity
     */
    public function generateSquareFilter($entity)
    {
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

        $webPath = $this->container->get('request')->server->get('DOCUMENT_ROOT');
        $file = basename($helper->asset($entity, 'file'));
        $path = dirname($helper->asset($entity, 'file'));
        $fullPathSquare = $webPath.'/'.$path.'/square_'.$file;

        if (!file_exists($fullPathSquare)) {
            $fullPathImage = $webPath.'/'.$helper->asset($entity, 'file');
            $fileO = new File($fullPathImage);
            $this->container->get('kodear_api.base64_service')->createSquareImage($fileO);
        }
    }

    /**
     * Remueve duplicados de un ArrayCollection
     * Warning: la entidad tiene que implementar __toString().
     *
     * @param  arrayCollection $arrayCollection
     *
     * @return ArrayCollection
     */
    public function removeDuplicatesFromArrayCollection($arrayCollection)
    {
        $uniqueArray = array_unique($arrayCollection->toArray(), SORT_REGULAR);

        return new ArrayCollection($uniqueArray);
    }

    /**
     * Arma respuesta de error siguiendo un formato estandar
     *
     * @param $message
     * @param $status
     *
     * @return array
     */
    public function buildResponseErrorMessage($message, $status)
    {
        return [
            'message' => [
                'error' => [
                    'code' => $status,
                    'message' => $message,
                ],
            ],
            'status' => $status,
        ];
    }

    /**
     * Arma respuesta de success siguiendo un formato estandar
     *
     * @param $message
     * @param $status
     *
     * @return array
     */
    public function buildResponseSuccessMessage($message, $status)
    {
        return [
            'message' => $message,
            'status' => $status,
        ];
    }

    public function generateReferralCode()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    public function fixImgName($file)
    {
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $file = basename($file);
        }

        return $file;
    }

    /**
     * This routine calculates the distance between two points (given the
     * latitude/longitude of those points). It is being used to calculate
     * the distance between two locations using GeoDataSource(TM) Products
     *
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param string $unit the unit you desire for results where:
     *              'M' is statute miles (default)
     *              'K' is kilometers
     *              'N' is nautical miles
     * @param boolean $rounded
     *
     * @return float
     */
    public function calculateDistanceBetweenTwoGeoPoints($lat1, $lon1, $lat2, $lon2, $unit = "K", $rounded = true)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            $result = ($miles * 1.609344);
        } else {
            if ($unit == "N") {
                $result = ($miles * 0.8684);
            } else {
                $result = $miles;
            }
        }

        if ($rounded) {
            $result = round($result);
        }

        return $result;
    }

}
