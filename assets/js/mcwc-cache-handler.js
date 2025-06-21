document.addEventListener('DOMContentLoaded', function () {
    if (!window.MCWC_Settings) return;

    const mode = MCWC_Settings.use_cache;

    if (mode === 'ajax_override') {
        mcwcUpdatePricesViaAjax();
    } else if (mode === 'json_override') {
        mcwcOverridePricesFromJSON();
    }
});

function mcwcUpdatePricesViaAjax() {
    jQuery.post(MCWC_Settings.ajax_url, {
        action: 'mcwc_get_updated_prices',
        currency: MCWC_Settings.currency
    }, function(response) {
        if (response.success && response.data) {
            jQuery('.price').each(function () {
                const productId = jQuery(this).data('product-id');
                if (response.data[productId]) {
                    jQuery(this).html(response.data[productId].formatted);
                }
            });
        }
    });
}

function mcwcOverridePricesFromJSON() {
    document.querySelectorAll('.mcwc-price-json').forEach(el => {
        const prices = JSON.parse(el.textContent || '{}');
        Object.keys(prices).forEach(productId => {
            const target = document.querySelector(`.price[data-product-id="${productId}"]`);
            if (target) {
                target.innerHTML = prices[productId].formatted;
            }
        });
    });
}
