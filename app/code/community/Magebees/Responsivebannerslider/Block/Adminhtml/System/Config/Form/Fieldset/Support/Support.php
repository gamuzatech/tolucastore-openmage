<?php
class Magebees_Responsivebannerslider_Block_Adminhtml_System_Config_Form_Fieldset_Support_Support
    extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);
        $html .= '<div style="float: left;">
<a href="https://www.magebees.com" target="_blank"><img src="https://www.magebees.com/skin/frontend/default/magentoextensiondesign/images/logo.gif" style="float:left; padding-right: 35px; margin-top: 30px;" /></a></div>
<div style="float:left">
<h2>MageBees Responsive Banner Slider Extension</h2>
<p>
<b>Installed Version: v1.0.5</b><br>
Website: <a target="_blank" href="https://www.magebees.com">https://www.magebees.com</a><br>
Like, share and follow us on 
<a target="_blank" href="https://www.facebook.com/magebees">Facebook</a>, 
<a target="_blank" href="https://plus.google.com/103198825494380131025">Google+</a> and
<a target="_blank" href="https://twitter.com/magebees">Twitter</a>.<br>
Do you need Extension Support? Please create support ticket from <a href="http://support.magebees.com" target="_blank">here</a> or <br> please contact us on <a href="mailto:support@magebees.com">support@magebees.com</a> for quick reply.

</p>
</div>';
        
        $html .= $this->_getFooterHtml($element);
        return $html;
    }
}
