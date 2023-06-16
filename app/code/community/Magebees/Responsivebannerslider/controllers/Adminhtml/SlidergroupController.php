<?php
/***************************************************************************
 Extension Name  : Magento Responsive Banner Slider with Lazy Load Extension
 Extension URL   : http://www.magebees.com/magento-responsive-banner-slider-with-lazy-load-extension.html
 Copyright    : Copyright (c) 2016 MageBees, http://www.magebees.com
 Support Email   : support@magebees.com 
 ***************************************************************************/

class Magebees_Responsivebannerslider_Adminhtml_SlidergroupController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction() 
    {
        $this->loadLayout()
            ->_setActiveMenu('cws')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Responsive Banner Slider'), Mage::helper('adminhtml')->__('Responsive Banner Slider'));
        return $this;
    }   
 
    public function indexAction() 
    {
        $this->_initAction()
            ->renderLayout();
    }
    
    public function gridAction() 
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_grid')->toHtml()
        );
    }

    public function editAction() 
    {
        $id = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('responsivebannerslider/responsivebannerslider')->load($id);
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('slidergroup_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('cws');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Slider Group Manager'), Mage::helper('adminhtml')->__('Slider Group Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit'))
                ->_addLeft($this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsivebannerslider')->__('Item does not exist'));
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
            $dataid = $this->getRequest()->getParam('id');    
            if (isset($data['category_ids'])) {
                $data['category_ids'] = explode(',', $data['category_ids']);
                if (is_array($data['category_ids'])) {
                    $data['category_ids'] = array_unique($data['category_ids']);
                }
            }

            if (isset($data['product_sku'])) {
                $data['product_sku'] = explode(', ', $data['product_sku']);
                if (is_array($data['product_sku'])) {
                    $data['product_sku'] = array_unique($data['product_sku']);
                }
            }

            $model = Mage::getModel('responsivebannerslider/responsivebannerslider');    
            $model->setData($data)->setId($this->getRequest()->getParam('id'));
            
            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
    
                $model->save();
                $store_model = Mage::getModel('responsivebannerslider/store');    
                if($dataid != "") {            
                    $store_data = $store_model->getCollection()
                                    ->addFieldToFilter('slidergroup_id', $dataid); 
                    $store_data->walk('delete');  
                }

                foreach($model->getData('store_id') as $store){
                    $data_store['slidergroup_id'] = $model->getData('slidergroup_id');
                    $data_store['store_id'] = $store;
                    $store_model->setData($data_store);
                    $store_model->save();
                }
     
                $page_model = Mage::getModel('responsivebannerslider/page');
                if($dataid != "") {            
                    $page_data = $page_model->getCollection()
                                    ->addFieldToFilter('slidergroup_id', $dataid); 
                    $page_data->walk('delete');  
                }

                $cmspages = $model->getData('pages');
                if(isset($cmspages)) {
                    if(count($model->getData('pages')) > 0) {
                        foreach($model->getData('pages') as $pages)    {
                            $data_page['slidergroup_id'] = $model->getData('slidergroup_id');
                            $data_page['pages'] = $pages;
                            $page_model->setData($data_page);
                            $page_model->save();
                        } 
                    }
                }

                $cate_model = Mage::getModel('responsivebannerslider/categories');
                if($dataid != "") {            
                    $cate_data = $cate_model->getCollection()
                                    ->addFieldToFilter('slidergroup_id', $dataid); 
                    $cate_data->walk('delete');  
                }

                foreach($model->getData('category_ids') as $category_id)    {
                    if($category_id != "") {
                        $data_cate['slidergroup_id'] = $model->getData('slidergroup_id');
                        $data_cate['category_ids'] = $category_id;
                        $cate_model->setData($data_cate);
                        $cate_model->save();
                    }
                }
 
                $product_model = Mage::getModel('responsivebannerslider/product');
                if($dataid != "") {            
                    $prd_data = $product_model->getCollection()
                                    ->addFieldToFilter('slidergroup_id', $dataid); 
                    $prd_data->walk('delete');  
                }

                foreach($model->getData('product_sku') as $product)    {
                    $data_prd['slidergroup_id'] = $model->getData('slidergroup_id');
                    $data_prd['product_sku'] = $product;
                    $product_model->setData($data_prd);
                    $product_model->save();
                } 
        
                if($this->getRequest()->getParam('id') == ""){
                    $group_id = $model->getData('slidergroup_id');
                }else{
                    $group_id = $this->getRequest()->getParam('id');
                }

                if (!file_exists(Mage::getBaseDir('skin').DS.'frontend'.DS.'base'.DS.'default'.DS.'css'.DS.'responsivebannerslider')) {
                    mkdir(Mage::getBaseDir('skin').DS.'frontend'.DS.'base'.DS.'default'.DS.'css'.DS.'responsivebannerslider', 0777, true);
                }

                $path = Mage::getBaseDir('skin').DS.'frontend'.DS.'base'.DS.'default'.DS.'css'.DS.'responsivebannerslider'.DS;
                $path .= "group-".$group_id.".css";
                $css = $this->get_menu_css($group_id);
                file_put_contents($path, $css);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('responsivebannerslider')->__('Group was successfully saved'));
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

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('responsivebannerslider')->__('Unable to find Group to save'));
        $this->_redirect('*/*/');
    }
 
    public function deleteAction() 
    {
        if($this->getRequest()->getParam('id') > 0) {
            $slide_collection = Mage::getModel('responsivebannerslider/slide')->getCollection();
            $current_groupid = $this->getRequest()->getParam('id');
            $slide_collection->addFieldToFilter('group_names', array(array('finset' => $current_groupid)));
            if(count($slide_collection->getData()) <= 0){
                try {
                    $model = Mage::getModel('responsivebannerslider/responsivebannerslider');
                    $model->setId($this->getRequest()->getParam('id'))->delete();
                    $dataid = $this->getRequest()->getParam('id');
                    $store_model = Mage::getModel('responsivebannerslider/store');    
                    $store_data = $store_model->getCollection()
                            ->addFieldToFilter('slidergroup_id', $dataid); 
                    $store_data->walk('delete');  
                    $page_model = Mage::getModel('responsivebannerslider/page');
                    $page_data = $page_model->getCollection()
                            ->addFieldToFilter('slidergroup_id', $dataid); 
                    $page_data->walk('delete');  
                    $cate_model = Mage::getModel('responsivebannerslider/categories');
                    $cate_data = $cate_model->getCollection()
                            ->addFieldToFilter('slidergroup_id', $dataid); 
                    $cate_data->walk('delete');  
                    $product_model = Mage::getModel('responsivebannerslider/product');
                    $prd_data = $product_model->getCollection()
                            ->addFieldToFilter('slidergroup_id', $dataid); 
                    $prd_data->walk('delete');  
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Group was successfully deleted'));
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                }
            }else {
                Mage::getSingleton('adminhtml/session')->addError("Please Remove Assigned slider form the selected group before delete group.");
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }            
        }

        $this->_redirect('*/*/');
    }
    
    public function massDeleteAction() 
    {
        $webIds = $this->getRequest()->getParam('responsivebannerslider_group');
        if(!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Group(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $responsivebannerslider_group = Mage::getModel('responsivebannerslider/responsivebannerslider')->load($webId);
                    $slide_collection = Mage::getModel('responsivebannerslider/slide')->getCollection();
                    $slide_collection->addFieldToFilter('group_names', array(array('finset' => $webId)));
                    if(count($slide_collection->getData()) <= 0){
                        $responsivebannerslider_group->delete();
                        $store_model = Mage::getModel('responsivebannerslider/store');    
                        $store_data = $store_model->getCollection()
                                ->addFieldToFilter('slidergroup_id', $webId); 
                        $store_data->walk('delete');  
                        $page_model = Mage::getModel('responsivebannerslider/page');
                        $page_data = $page_model->getCollection()
                                ->addFieldToFilter('slidergroup_id', $webId); 
                        $page_data->walk('delete');  
                        $cate_model = Mage::getModel('responsivebannerslider/categories');
                        $cate_data = $cate_model->getCollection()
                                ->addFieldToFilter('slidergroup_id', $webId); 
                        $cate_data->walk('delete');  
                        $product_model = Mage::getModel('responsivebannerslider/product');
                        $prd_data = $product_model->getCollection()
                                ->addFieldToFilter('slidergroup_id', $webId); 
                        $prd_data->walk('delete');  
                        $groupname = $responsivebannerslider_group->getData('title');
                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(''.$groupname.' Group was successfully deleted'));
                    }else{
                        $groupname = $responsivebannerslider_group->getData('title');
                        Mage::getSingleton('adminhtml/session')->addError("Please Remove Assigned slider form the selected ".$groupname." group before delete ".$groupname." group.");
                    }
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    
    public function massStatusAction()
    {
        $webIds = $this->getRequest()->getParam('responsivebannerslider_group');
        if(!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $responsivebannerslider_group = Mage::getSingleton('responsivebannerslider/responsivebannerslider')
                        ->load($webId)
                        ->setStatus($this->getRequest()->getParam('status'))
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
    
    public function categoriesAction()
    {
        $this->_initcategory();
        $this->loadLayout();
        $this->renderLayout();
    }
    public function categoriesJsonAction()
    {
    
        $this->_initcategory();
        $this->getResponse()->setBody( 
            $this->getLayout()->createBlock('responsivebannerslider/adminhtml_slidergroup_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
    protected function _initcategory()
    {
        $categoryId  = $this->getRequest()->getParam('id');
        $category    = Mage::getModel('responsivebannerslider/responsivebannerslider');
              if ($categoryId) {
                $category->load($categoryId);
              }

            Mage::register('slidergroup_data', $category);
        return $category;
    }
    
    public function get_menu_css($group_id)
    {
        $groupdata = Mage::getModel("responsivebannerslider/responsivebannerslider")->load($group_id);
        $max_width = $groupdata->getMaxWidth();
        $content_background = $groupdata->getContentBackground();
        $content_opacity = $groupdata->getContentOpacity();
        $navigation_acolor = $groupdata->getNavigationAcolor();
        $pagination_color = $groupdata->getPaginationColor();
        $pagination_active_color = $groupdata->getPaginationActive();
        $pagination_bar = $groupdata->getPaginationBar();
        $thumbnail_size = $groupdata->getThumbnailSize();
        if ($max_width > 0) {
            $max_width = $groupdata->getMaxWidth().'px';
        } else {
            $max_width = "";
        }

        $css = '';
        $css .= '#bnrSlider-'.$group_id.' { }';
        $css .= '#bnrSlider-'.$group_id.' { max-width:'.$max_width.'; }';
        $css .= '#bnrSlider-'.$group_id.' .sliderdecs { background-color:#'.$content_background.'; opacity:0.'.$content_opacity.'; }';
        $css .= '#bnrSlider-'.$group_id.' .cws-arw a:before { color:#'.$navigation_acolor.'; }';
        $css .= '#bnrSlider-'.$group_id.' .cws-pager a { background-color:#'.$pagination_color.'; }';
        $css .= '#bnrSlider-'.$group_id.' .cws-pager a.cws-active { background-color:#'.$pagination_active_color.'; }';
        $css .= '#bnrSlider-'.$group_id.' .cws-pager.cir-bar { background-color:#'.$pagination_bar.'; }';
        $css .= '#bnrSlider-'.$group_id.' .cws-pager.squ-bar { background-color:#'.$pagination_bar.'; }';
        $css .= '@media (min-width:999px){#carousel-'.$group_id.' ul.slides li { width:'.$thumbnail_size.'px !important;}}';
        return $css;
    }
    
    public function _isAllowed()
    {
        return true;
    }

}
