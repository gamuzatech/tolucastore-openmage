<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Catalog
 */

/**
 * API2 for product image. Admin role
 *
 * @package    Mage_Catalog
 */
class Mage_Catalog_Model_Api2_Product_Image_Rest_Admin_V1 extends Mage_Catalog_Model_Api2_Product_Image_Rest
{
    /**
     * Product image add
     *
     * @throws Mage_Api2_Exception
     * @return string|void
     * @SuppressWarnings("PHPMD.ErrorControlOperator")
     */
    protected function _create(array $data)
    {
        /** @var Mage_Catalog_Model_Api2_Product_Image_Validator_Image $validator */
        $validator = Mage::getModel('catalog/api2_product_image_validator_image');
        if (!$validator->isValidData($data)) {
            foreach ($validator->getErrors() as $error) {
                $this->_error($error, Mage_Api2_Model_Server::HTTP_BAD_REQUEST);
            }
            $this->_critical(self::RESOURCE_DATA_PRE_VALIDATION_ERROR);
        }
        $imageFileContent = @base64_decode($data['file_content'], true);
        if (!$imageFileContent) {
            $this->_critical(
                'The image content must be valid base64 encoded data',
                Mage_Api2_Model_Server::HTTP_BAD_REQUEST,
            );
        }
        unset($data['file_content']);

        $apiTempDir = Mage::getBaseDir('var') . DS . 'api' . DS . Mage::getSingleton('api/session')->getSessionId();
        $imageFileName = $this->_getFileName($data);

        try {
            $ioAdapter = new Varien_Io_File();
            $ioAdapter->checkAndCreateFolder($apiTempDir);
            $ioAdapter->open(['path' => $apiTempDir]);
            $ioAdapter->write($imageFileName, $imageFileContent, 0666);
            unset($imageFileContent);

            // try to create Image object to check if image data is valid
            try {
                $filePath = $apiTempDir . DS . $imageFileName;
                Mage::getModel('varien/image', $filePath);
                Mage::getModel('core/file_validator_image')->validate($filePath);
            } catch (Exception $e) {
                $ioAdapter->rmdir($apiTempDir, true);
                $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
            }
            $product = $this->_getProduct();
            $imageFileUri = $this->_getMediaGallery()
                ->addImage($product, $apiTempDir . DS . $imageFileName, null, false, false);
            $ioAdapter->rmdir($apiTempDir, true);
            // updateImage() must be called to add image data that is missing after addImage() call
            $this->_getMediaGallery()->updateImage($product, $imageFileUri, $data);

            if (isset($data['types'])) {
                $this->_getMediaGallery()->setMediaAttribute($product, $data['types'], $imageFileUri);
            }
            $product->save();
            return $this->_getImageLocation($this->_getCreatedImageId($imageFileUri));
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_critical(self::RESOURCE_UNKNOWN_ERROR);
        }
    }

    /**
     * Get added image ID
     *
     * @throws Mage_Api2_Exception
     * @param string $imageFileUri
     * @return int
     */
    protected function _getCreatedImageId($imageFileUri)
    {
        $imageId = null;

        $imageData = Mage::getResourceModel('catalog/product_attribute_backend_media')
            ->loadGallery($this->_getProduct(), $this->_getMediaGallery());
        foreach ($imageData as $image) {
            if ($image['file'] == $imageFileUri) {
                $imageId = $image['value_id'];
                break;
            }
        }
        if (!$imageId) {
            $this->_critical('Unknown error during image save', Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        }
        return $imageId;
    }

    /**
     * Retrieve product images data
     *
     * @throws Mage_Api2_Exception
     * @return array
     */
    protected function _retrieve()
    {
        $result = [];
        $imageId = (int) $this->getRequest()->getParam('image');
        $galleryData = $this->_getProduct()->getData(self::GALLERY_ATTRIBUTE_CODE);
        if (!isset($galleryData['images']) || !is_array($galleryData['images'])) {
            $this->_critical('Product image not found', Mage_Api2_Model_Server::HTTP_NOT_FOUND);
        }
        foreach ($galleryData['images'] as &$image) {
            if ($image['value_id'] == $imageId) {
                $result = $this->_formatImageData($image);
                break;
            }
        }
        if (empty($result)) {
            $this->_critical('Product image not found', Mage_Api2_Model_Server::HTTP_NOT_FOUND);
        }
        return $result;
    }

    /**
     * Update product image
     *
     * @throws Mage_Api2_Exception
     */
    protected function _update(array $data)
    {
        $imageId = (int) $this->getRequest()->getParam('image');
        $imageFileUri = $this->_getImageFileById($imageId);
        $product = $this->_getProduct();
        $this->_getMediaGallery()->updateImage($product, $imageFileUri, $data);
        if (isset($data['types']) && is_array($data['types'])) {
            $assignedTypes = $this->_getImageTypesAssignedToProduct($imageFileUri);
            $typesToBeCleared = array_diff($assignedTypes, $data['types']);
            if (count($typesToBeCleared) > 0) {
                $this->_getMediaGallery()->clearMediaAttribute($product, $typesToBeCleared);
            }
            $this->_getMediaGallery()->setMediaAttribute($product, $data['types'], $imageFileUri);
        }
        try {
            $product->save();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_critical(self::RESOURCE_INTERNAL_ERROR);
        }
    }

    /**
     * Product image delete
     *
     * @throws Mage_Api2_Exception
     */
    protected function _delete()
    {
        $imageId = (int) $this->getRequest()->getParam('image');
        $product = $this->_getProduct();
        $imageFileUri = $this->_getImageFileById($imageId);
        $this->_getMediaGallery()->removeImage($product, $imageFileUri);
        try {
            $product->save();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_critical(self::RESOURCE_INTERNAL_ERROR);
        }
    }

    /**
     * Retrieve product images data
     *
     * @return array
     */
    protected function _retrieveCollection()
    {
        $images = [];
        $galleryData = $this->_getProduct()->getData(self::GALLERY_ATTRIBUTE_CODE);
        if (isset($galleryData['images']) && is_array($galleryData['images'])) {
            foreach ($galleryData['images'] as $image) {
                $images[] = $this->_formatImageData($image);
            }
        }
        return $images;
    }

    /**
     * Get image resource location
     *
     * @param int $imageId
     * @return string URL
     */
    protected function _getImageLocation($imageId)
    {
        /** @var Mage_Api2_Model_Route_ApiType $apiTypeRoute */
        $apiTypeRoute = Mage::getModel('api2/route_apiType');

        $chain = $apiTypeRoute->chain(
            new Zend_Controller_Router_Route($this->getConfig()->getRouteWithEntityTypeAction($this->getResourceType())),
        );
        $params = [
            'api_type' => $this->getRequest()->getApiType(),
            'id'       => $this->getRequest()->getParam('id'),
            'image'    => $imageId,
        ];
        $uri = $chain->assemble($params);
        return '/' . $uri;
    }
}
