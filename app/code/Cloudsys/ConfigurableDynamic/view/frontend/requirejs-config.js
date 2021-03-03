var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Cloudsys_ConfigurableDynamic/js/model/optionswitch': true
            },
			'Magento_Swatches/js/swatch-renderer': {
                'Cloudsys_ConfigurableDynamic/js/model/swatch-optionswitch': true
            }
        }
    }
};
