<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Downloadable
 */

/**
 * Download controller
 *
 * @package    Mage_Downloadable
 */
class Mage_Downloadable_DownloadController extends Mage_Core_Controller_Front_Action
{
    /**
     * Return core session object
     *
     * @return Mage_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * Return customer session object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * @param string $resource
     * @param string $resourceType
     * @throws Zend_Controller_Response_Exception
     */
    protected function _processDownload($resource, $resourceType)
    {
        $helper = Mage::helper('downloadable/download');
        $helper->setResource($resource, $resourceType);

        $fileName       = $helper->getFilename();
        $contentType    = $helper->getContentType();

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($fileSize = $helper->getFilesize()) {
            $this->getResponse()
                ->setHeader('Content-Length', $fileSize);
        }

        if ($contentDisposition = $helper->getContentDisposition()) {
            $this->getResponse()
                ->setHeader('Content-Disposition', $contentDisposition . '; filename=' . $fileName);
        }

        $this->getResponse()
            ->clearBody();
        $this->getResponse()
            ->sendHeaders();

        session_write_close();
        $helper->output();
    }

    /**
     * Download sample action
     *
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    public function sampleAction()
    {
        $sampleId = $this->getRequest()->getParam('sample_id', 0);
        $sample = Mage::getModel('downloadable/sample')->load($sampleId);
        if ($sample->getId()
            && Mage::helper('catalog/product')
                ->getProduct((int) $sample->getProductId(), Mage::app()->getStore()->getId(), 'id')
                ->isAvailable()
        ) {
            $resource = '';
            $resourceType = '';
            if ($sample->getSampleType() == Mage_Downloadable_Helper_Download::LINK_TYPE_URL) {
                $resource = $sample->getSampleUrl();
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_URL;
            } elseif ($sample->getSampleType() == Mage_Downloadable_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('downloadable/file')->getFilePath(
                    Mage_Downloadable_Model_Sample::getBasePath(),
                    $sample->getSampleFile(),
                );
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
                exit(0);
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError(Mage::helper('downloadable')->__('Sorry, there was an error getting requested content. Please contact the store owner.'));
            }
        }
        return $this->_redirectReferer();
    }

    /**
     * Download link's sample action
     *
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    public function linkSampleAction()
    {
        $linkId = $this->getRequest()->getParam('link_id', 0);
        $link = Mage::getModel('downloadable/link')->load($linkId);
        if ($link->getId()
            && Mage::helper('catalog/product')
                ->getProduct((int) $link->getProductId(), Mage::app()->getStore()->getId(), 'id')
                ->isAvailable()
        ) {
            $resource = '';
            $resourceType = '';
            if ($link->getSampleType() == Mage_Downloadable_Helper_Download::LINK_TYPE_URL) {
                $resource = $link->getSampleUrl();
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_URL;
            } elseif ($link->getSampleType() == Mage_Downloadable_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('downloadable/file')->getFilePath(
                    Mage_Downloadable_Model_Link::getBaseSamplePath(),
                    $link->getSampleFile(),
                );
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
                exit(0);
            } catch (Mage_Core_Exception $e) {
                $this->_getCustomerSession()->addError(Mage::helper('downloadable')->__('Sorry, there was an error getting requested content. Please contact the store owner.'));
            }
        }
        return $this->_redirectReferer();
    }

    /**
     * Download link action
     *
     * @return Mage_Core_Controller_Varien_Action|void
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    public function linkAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $linkPurchasedItem = Mage::getModel('downloadable/link_purchased_item')->load($id, 'link_hash');
        if (!$linkPurchasedItem->getId()) {
            $this->_getCustomerSession()->addNotice(Mage::helper('downloadable')->__('Requested link does not exist.'));
            return $this->_redirect('*/customer/products');
        }
        if (!Mage::helper('downloadable')->getIsShareable($linkPurchasedItem)) {
            $customerId = $this->_getCustomerSession()->getCustomerId();
            if (!$customerId) {
                $product = Mage::getModel('catalog/product')->load($linkPurchasedItem->getProductId());
                if ($product->getId()) {
                    $notice = Mage::helper('downloadable')->__('Please log in to download your product or purchase <a href="%s">%s</a>.', $product->getProductUrl(), $product->getName());
                } else {
                    $notice = Mage::helper('downloadable')->__('Please log in to download your product.');
                }
                $this->_getCustomerSession()->addNotice($notice);
                $this->_getCustomerSession()->authenticate($this);
                $this->_getCustomerSession()->setBeforeAuthUrl(
                    Mage::getUrl('downloadable/customer/products/'),
                    ['_secure' => true],
                );
                return;
            }
            $linkPurchased = Mage::getModel('downloadable/link_purchased')->load($linkPurchasedItem->getPurchasedId());
            if ($linkPurchased->getCustomerId() != $customerId) {
                $this->_getCustomerSession()->addNotice(Mage::helper('downloadable')->__('Requested link does not exist.'));
                return $this->_redirect('*/customer/products');
            }
        }
        $downloadsLeft = $linkPurchasedItem->getNumberOfDownloadsBought()
            - $linkPurchasedItem->getNumberOfDownloadsUsed();

        $status = $linkPurchasedItem->getStatus();
        if ($status == Mage_Downloadable_Model_Link_Purchased_Item::LINK_STATUS_AVAILABLE
            && ($downloadsLeft || $linkPurchasedItem->getNumberOfDownloadsBought() == 0)
        ) {
            $resource = '';
            $resourceType = '';
            if ($linkPurchasedItem->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_URL) {
                $resource = $linkPurchasedItem->getLinkUrl();
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_URL;
            } elseif ($linkPurchasedItem->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('downloadable/file')->getFilePath(
                    Mage_Downloadable_Model_Link::getBasePath(),
                    $linkPurchasedItem->getLinkFile(),
                );
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
                $linkPurchasedItem->setNumberOfDownloadsUsed($linkPurchasedItem->getNumberOfDownloadsUsed() + 1);

                if ($linkPurchasedItem->getNumberOfDownloadsBought() != 0 && !($downloadsLeft - 1)) {
                    $linkPurchasedItem->setStatus(Mage_Downloadable_Model_Link_Purchased_Item::LINK_STATUS_EXPIRED);
                }
                $linkPurchasedItem->save();
                exit(0);
            } catch (Exception $e) {
                $this->_getCustomerSession()->addError(
                    Mage::helper('downloadable')->__('An error occurred while getting the requested content. Please contact the store owner.'),
                );
            }
        } elseif ($status == Mage_Downloadable_Model_Link_Purchased_Item::LINK_STATUS_EXPIRED) {
            $this->_getCustomerSession()->addNotice(Mage::helper('downloadable')->__('The link has expired.'));
        } elseif ($status == Mage_Downloadable_Model_Link_Purchased_Item::LINK_STATUS_PENDING
            || $status == Mage_Downloadable_Model_Link_Purchased_Item::LINK_STATUS_PAYMENT_REVIEW
        ) {
            $this->_getCustomerSession()->addNotice(Mage::helper('downloadable')->__('The link is not available.'));
        } else {
            $this->_getCustomerSession()->addError(
                Mage::helper('downloadable')->__('An error occurred while getting the requested content. Please contact the store owner.'),
            );
        }
        return $this->_redirect('*/customer/products');
    }
}
