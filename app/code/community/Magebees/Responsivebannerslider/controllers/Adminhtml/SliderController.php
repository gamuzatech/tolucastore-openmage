<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Adminhtml_SliderController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction() 
    {
        $this->loadLayout()
            ->_setActiveMenu('cws')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Responsive Banner Slider Manager'), Mage::helper('adminhtml')->__('Responsive Banner Slider Manager'));
        
        return $this;
    }   
     public function indexAction() 
     {
        $this->_initAction()
            ->renderLayout();
     }
    public function editAction() 
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('responsivebannerslider/slide')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('slider_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('cws');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Responsive Banner Slider Manager'), Mage::helper('adminhtml')->__('Responsive Banner Slider Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('responsivebannerslider/adminhtml_slider_edit'))
                ->_addLeft($this->getLayout()->createBlock('responsivebannerslider/adminhtml_slider_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsivebannerslider')->__('Slider does not exist'));
            $this->_redirect('*/*/');
        }
    }
     public function newAction() 
     {
        $this->_forward('edit');
     }
     public function saveAction() 
     {
        if ($data = $this->getRequest()->getPost()) {
            if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {    
                    $uploader = new Varien_File_Uploader('filename');
                       $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . 'responsivebannerslider'. DS;
                    $filenames = preg_replace('/[^a-zA-Z0-9._]/s', '', $_FILES['filename']['name']);
                    $path_parts = pathinfo($filenames);
                    $filename = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
                    $uploader->save($path, $filename);
                    Mage::helper('responsivebannerslider/data')->resizeImg($filename);
                } catch (Exception $e) {   
                }

                $data['filename'] = $filename;
            }else {
                if (isset($data['filename']['delete']) && $data['filename']['delete'] == 1) {
                    $path =str_replace("responsivebannerslider/", "", $data['filename']['value']);
                    $img_filename = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . $path;
                        if (file_exists($img_filename)) {
                            unlink($img_filename);
                        }

                    $img_file = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . "thumbnails" . DS . $path;
                        if (file_exists($img_file)) {
                            unlink($img_file);
                        }

                    $data['filename'] = '';
                }else {
                    unset($data['filename']);
                }                
            }
    
              $model = Mage::getModel('responsivebannerslider/slide');        
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            try {
                $group_label ='';
                for($i=0;$i<count($data['group_names']);$i++) {
                    if($i < count($data['group_names'])-1){
                        $group_label .= $data['group_names'][$i].",";
                    }else{
                        $group_label .= $data['group_names'][$i];
                    }
                }
                    
                $validateResult = $model->validateData(new Varien_Object($data));//for validate start ,end date and time duration
                if ($validateResult !== true) {
                    foreach ($validateResult as $errorMessage) {
                        $this->_getSession()->addError($errorMessage);
                    }

                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
    
                $model->setData("group_names", $group_label);
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('responsivebannerslider')->__('Slide was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsivebannerslider')->__('Unable to find Slide to save'));
        $this->_redirect('*/*/');
     }
 
    public function deleteAction() 
    {
        if($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('responsivebannerslider/slide');
                $imgdata = $model->load($this->getRequest()->getParam('id'));
                if($imgdata['filename']) {
                    $img_list_filename = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . $imgdata['filename'];
                    if (file_exists($img_list_filename)) {
                        unlink($img_list_filename);
                    }

                    $img_file = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . "thumbnails" . DS . $imgdata['filename'];
                        if (file_exists($img_file)) {
                            unlink($img_file);
                        }
                }

                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slides was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }

        $this->_redirect('*/*/');
    }

    public function massDeleteAction() 
    {
        $webIds = $this->getRequest()->getParam('responsivebannerslider_slide');
        if(!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Slide(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $responsivebannerslider_group = Mage::getModel('responsivebannerslider/slide')->load($webId);
                    if($responsivebannerslider_group['filename']) {
                        $img_list_filename = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . $responsivebannerslider_group['filename'];
                        if (file_exists($img_list_filename)) {
                            unlink($img_list_filename);
                        }

                        $img_file = Mage::getBaseDir('media') . DS . "responsivebannerslider" . DS . "thumbnails" .DS . $responsivebannerslider_group['filename'];
                            if (file_exists($img_file)) {
                                unlink($img_file);
                            }
                    }

                    $responsivebannerslider_group->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($webIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    
    public function massStatusAction() 
    {
        $webIds = $this->getRequest()->getParam('responsivebannerslider_slide');
        if(!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $responsivebannerslider_group = Mage::getSingleton('responsivebannerslider/slide')
                        ->load($webId)
                        ->setStatuss($this->getRequest()->getParam('statuss'))
                        ->setIsMassupdate(true)
                        ->save();
                }

                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($webIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    public function gridAction() 
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slider_grid')->toHtml()
        );
    }
    
    public function _isAllowed() 
    {
        return true;
    }
}
