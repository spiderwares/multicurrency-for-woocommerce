jQuery(function ($) {
    class MCWC_ProductCompare {
        constructor() {
            this.index = $(".mcwc-input-group").length; // Get initial count
            this.init();
        }

        init() {
            this.eventHandlers();
            this.initEvent();
        }

        eventHandlers() {
            $(document.body).on( 'change', '.mcwc-switch-field input[type="checkbox"], select', this.toggleVisibility.bind(this) );
            $(document.body).on( 'click', '.mcwc-add-repeater-row', this.addRepeaterRow.bind(this) );
            $(document.body).on( 'click', '.mcwc-remove-repeater-row', this.removeRepeaterRow.bind(this) );
            $(document.body).on( 'click', '.mcwc-update-all-rates', this.updateAllExchangeRates.bind(this) );
            $(document.body).on( 'click', '.mcwc-select-all-countries', this.selectAllCountries.bind(this) );
            $(document.body).on( 'click', '.mcwc-remove-all-countries', this.removeAllCountries.bind(this) );
            $(document.body).on( 'change', '.mcwc-currency-select', this.updateDefaultCurrency.bind(this) );
        }

        initEvent() {
            
            $(".mcwc-colorpicker").wpColorPicker({
                change: function (e, ui) {
                    $(this).siblings(".colorpickpreview").css("background-color", ui.color.toString());
                },
            });

            $('.mcwc-repeater-body').sortable({ items: 'tr:not(.mcwc-repeater-template)' });

            $('.mcwc-repeater-body tr').not('.mcwc-repeater-template').find('.mcwc-currency-select').select2({
                width: '100%'
            });

            $('.mcwc_currency_country').select2({
                width: '100%',
                placeholder: $('.mcwc_currency_country').data('placeholder')
            });

            $('.mcwc_multiselect').select2({
                width: '100%',
                placeholder: 'Select payment method',
            });
            
        }

        toggleVisibility(e) {
            var __this  = $(e.currentTarget);

            if (__this.is('select')) {
                var target      = __this.find(':selected').data('show'),
                    hideElemnt  = __this.data( 'hide' );
                    $(document.body).find(hideElemnt).hide();
                    $(document.body).find(target).show();
            } else {
                var target = __this.data('show');
                $(document.body).find(target).toggle();
            }
        }

        addRepeaterRow(e) {
            e.preventDefault();
            const table  = $(e.currentTarget).closest('table'),
                template = table.find('.mcwc-repeater-template').clone();

            template.removeClass('mcwc-repeater-template').show();
            table.find('tbody').append(template);
            $('.mcwc-repeater-body tr').not('.mcwc-repeater-template').find('.mcwc-currency-select').select2({
                width: '100%'
            });
        }

        removeRepeaterRow(e) {
            e.preventDefault();
            $(e.currentTarget).closest('tr').remove();
        }

        getDefaultCurrency() {
            return $('input[name="mcwc_settings[currencies][default][]"]:checked').closest('tr').find('select[name="mcwc_settings[currencies][currency][]"]').val() || '';
        }


        getOtherCurrencies() {
            const defaultCurrency = this.getDefaultCurrency(),
                otherCurrencies = [];

            $('.mcwc-currency-select').each(function () {
                const val = $(this).val();
                if (val && val !== defaultCurrency && !otherCurrencies.includes(val)) {
                    otherCurrencies.push(val);
                }
            });

            return otherCurrencies;
        }

        updateAllExchangeRates(e) {
            e.preventDefault();
            
            var __this          = $(e.currentTarget),
                defaultCurrency = this.getDefaultCurrency(),
                otherCurrencies = this.getOtherCurrencies();

            if (!defaultCurrency || !otherCurrencies.length){
                console.log('Currencie data is missing.');
                return;  
            } 

            $.ajax({
                type: 'POST',
                url: mcwcParams.ajax_url,
                data: {
                    action: 'mcwc_get_exchange_rates',
                    nonce: mcwcParams.nonce,
                    default_currency: defaultCurrency,
                    other_currencies: otherCurrencies.join(','),
                },
                beforeSend: function () {
                    __this.closest('td').addClass('mcwc-loading');
                },
                success(response) {
                    if (response.success) {
                        $.each(response.data, function (currency, rate) {
                            $('.mcwc-repeater-body tr').each(function () {
                                var row          = $(this),
                                selectedCurrency = row.find('select[name="mcwc_settings[currencies][currency][]"]').val();

                                if ( selectedCurrency === currency ) {
                                    row.find('input[name="mcwc_settings[currencies][rate][]"]').val(rate);
                                }

                                if ( selectedCurrency === defaultCurrency ) {
                                    row.find('input[name="mcwc_settings[currencies][rate][]"]').val('1');
                                }
                            });
                        });
                    } else {
                        $('#mcwc-update-rate-msg').html('Unable to update exchange rates. Please check your API key and Finance API settings.');
                    }
                },
                error(xhr) {
                    $('#mcwc-update-rate-msg').html('Failed to connect to the exchange rate service. Please try again later.');
                },
                complete: function () {
                    __this.closest('td').removeClass('mcwc-loading');
                }
            });
        }

        selectAllCountries(e) {
            e.preventDefault();
            var __this    = jQuery(e.currentTarget),
                row       = __this.closest('tr'),
                select    = row.find('.mcwc_currency_country'),
                allValues = select.find('option').map(function() {
                    return jQuery(this).val(); // Corrected line
                }).get();

            select.val(allValues).trigger('change');
        }


        // Remove all selected countries
        removeAllCountries(e) {
            e.preventDefault();
            var __this  = $(e.currentTarget),
                row     = __this.closest('tr'),
                select  = row.find('.mcwc_currency_country');
            select.val(null).trigger('change');
        }

        updateDefaultCurrency(e) {
            const row               = $(e.currentTarget).closest('tr'),
                 selectedCurrency   = $(e.currentTarget).val();
            row.find('.mcwc-default-currency').val(selectedCurrency);
        }


    }

    new MCWC_ProductCompare();
});