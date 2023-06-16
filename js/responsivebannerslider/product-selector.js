var VarienRulesForm = new Class.create();
VarienRulesForm.prototype = {
    initialize : function (parent, newChildUrl) {
        this.parent = $(parent);
        this.newChildUrl  = newChildUrl;
        this.shownElement = null;
        this.updateElement = null;
        this.chooserSelectedItems = $H({});

        var elems = this.parent.getElementsByClassName('product-param');
        for (var i=0; i<elems.length; i++) {
            this.initParam(elems[i]);
        }
        
        var elems = this.parent.getElementsByClassName('product-param');
        for (var i=0; i<elems.length; i++) {
            var container = elems[i];
        }
    },
    
    initParam: function (container) {
        container.rulesObject = this;
        var label = Element.down(container, '.label');
        if (label) {
            Event.observe(label, 'click', this.showParamInputField.bind(this, container));
        }

        var elem = Element.down(container, '.element');
        if (elem) {
            var trig = elem.down('.product-chooser-trigger');
            if (trig) {
                Event.observe(trig, 'click', this.toggleChooser.bind(this, container));
            }
        }
    },
    
    showChooserElement: function (chooser) {
        this.chooserSelectedItems = $H({});
        var values = this.updateElement.value.split(','), s = '';
        for (i=0; i<values.length; i++) {
            s = values[i].strip();
            if (s!='') {
               this.chooserSelectedItems.set(s,1);
            }
        }

        new Ajax.Request(
            chooser.getAttribute('url'), {
            evalScripts: true,
            parameters: {'form_key': FORM_KEY, 'selected[]':this.chooserSelectedItems.keys() },
            onSuccess: function (transport) {
                if (this._processSuccess(transport)) {
                    $(chooser).update(transport.responseText);
                    this.showChooserLoaded(chooser, transport);
                }
            }.bind(this),
            onFailure: this._processFailure.bind(this)
            }
        );
    },
    
    showChooserLoaded: function (chooser, transport) {
        chooser.style.display = 'block';
    },

    showChooser: function (container, event) {
        var chooser = container.up('li');
        if (!chooser) {
            return;
        }

        chooser = chooser.down('.product-chooser');
        if (!chooser) {
            return;
        }

        this.showChooserElement(chooser);
    },

    hideChooser: function (container, event) {
        var chooser = container.up('li');
        if (!chooser) {
            return;
        }

        chooser = chooser.down('.product-chooser');
        if (!chooser) {
            return;
        }

        chooser.style.display = 'none';
    },

    toggleChooser: function (container, event) {
        var chooser = container.up('li').down('.product-chooser');
        if (!chooser) {
            return;
        }

        if (chooser.style.display=='block') {
            chooser.style.display = 'none';
            this.cleanChooser(container, event);
        } else {
            this.showChooserElement(chooser);
        }
    },

    cleanChooser: function (container, event) {
        var chooser = container.up('li').down('.product-chooser');
        if (!chooser) {
            return;
        }

        chooser.innerHTML = '';
    },
    
    showParamInputField: function (container, event) {
        if (this.shownElement) {
            this.hideParamInputField(this.shownElement, event);
        }

        Element.addClassName(container, 'product-param-edit');
        var elemContainer = Element.down(container, '.element');

        var elem = Element.down(elemContainer, 'input.input-text');
        if (elem) {
            elem.focus();
            this.updateElement = elem;
        }

        var elem = Element.down(elemContainer, '.element-value-changer');
        if (elem) {
           elem.focus();
        }

        this.shownElement = container;
    },

    hideParamInputField: function (container, event) {
        Element.removeClassName(container, 'product-param-edit');
        var label = Element.down(container, '.label'), elem;
        
        elem = Element.down(container, '.element-value-changer');
        if (elem.value) {
            this.addRuleNewChild(elem);
        }

        elem.value = '';

        this.hideChooser(container, event);
        this.updateElement = null;

        this.shownElement = null;
    },
    
    _processSuccess : function (transport) {
        if (transport.responseText.isJSON()) {
            var response = transport.responseText.evalJSON()
            if (response.error) {
                alert(response.message);
            }

            if(response.ajaxExpired && response.ajaxRedirect) {
                setLocation(response.ajaxRedirect);
            }

            return false;
        }

        return true;
    },

    _processFailure : function (transport) {
        location.href = BASE_URL;
    },

    chooserGridRowInit: function (grid, row) {
        if (!grid.reloadParams) {
            grid.reloadParams = {'selected[]':this.chooserSelectedItems.keys()};
        }
    },

    chooserGridRowClick: function (grid, event) {
        var trElement = Event.findElement(event, 'tr');
        var isInput = Event.element(event).tagName == 'INPUT';
        if (trElement) {
            var checkbox = Element.select(trElement, 'input');
            if (checkbox[0]) {
                var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                grid.setCheckboxChecked(checkbox[0], checked);
            }
        }
    },

    chooserGridCheckboxCheck: function (grid, element, checked) {
        if (checked) {
            if (!element.up('th')) {
                this.chooserSelectedItems.set(element.value,1);
            }
        } else {
            this.chooserSelectedItems.unset(element.value);
        }

        grid.reloadParams = {'selected[]':this.chooserSelectedItems.keys()};
        this.updateElement.value = this.chooserSelectedItems.keys().join(', ');
    } 
   
};
