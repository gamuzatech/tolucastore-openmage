<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml add Review main block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Review_Add extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_controller = 'review';
        $this->_mode = 'add';

        $this->_updateButton('save', 'label', Mage::helper('review')->__('Save Review'));
        $this->_updateButton('save', 'id', 'save_button');

        $this->_updateButton('reset', 'id', 'reset_button');

        $this->_formScripts[] = '
            toggleParentVis("add_review_form");
            toggleVis("save_button");
            toggleVis("reset_button");
        ';

        $this->_formInitScripts[] = '
            //<![CDATA[
            var review = function() {
                return {
                    productInfoUrl : null,
                    formHidden : true,

                    gridRowClick : function(data, click) {
                        if(Event.findElement(click,\'TR\').title){
                            review.productInfoUrl = Event.findElement(click,\'TR\').title;
                            review.loadProductData();
                            review.showForm();
                            review.formHidden = false;
                        }
                    },

                    loadProductData : function() {
                        var con = new Ext.lib.Ajax.request(\'POST\', review.productInfoUrl, {success:review.reqSuccess,failure:review.reqFailure}, {form_key:FORM_KEY});
                    },

                    showForm : function() {
                        toggleParentVis("add_review_form");
                        toggleVis("reviewProductGrid");
                        toggleVis("save_button");
                        toggleVis("reset_button");
                    },

                    updateRating: function() {
                        elements = [$("select_stores"), $("rating_detail").getElementsBySelector("input[type=\'radio\']")].flatten();
                        $(\'save_button\').disabled = true;
                        var params = Form.serializeElements(elements);
                        if (!params.isAjax) {
                            params.isAjax = "true";
                        }
                        if (!params.form_key) {
                            params.form_key = FORM_KEY;
                        }
                        new Ajax.Updater("rating_detail", "' . $this->getUrl('*/*/ratingItems') . '", {parameters:params, evalScripts: true,  onComplete:function(){ $(\'save_button\').disabled = false; } });
                    },

                    reqSuccess :function(o) {
                        var response = Ext.util.JSON.decode(o.responseText);
                        if( response.error ) {
                            alert(response.message);
                        } else if( response.id ){
                            $("product_id").value = response.id;

                            $("product_name").innerHTML = \'<a href="' . $this->getUrl('*/catalog_product/edit') . 'id/\' + response.id + \'" target="_blank">\' + response.name.escapeHTML() + \'</a>\';
                        } else if( response.message ) {
                            alert(response.message);
                        }
                    }
                }
            }();

             Event.observe(window, \'load\', function(){
                 if ($("select_stores")) {
                     Event.observe($("select_stores"), \'change\', review.updateRating);
                 }
           });
           //]]>
        ';
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('review')->__('Add New Review');
    }
}
