jQuery(function ($) {
    class JthemesKitLoader {

        constructor() {
            this.wrapperSelector = '.jthemes_plugin_kit';
            this.nonce = typeof mcwcParams !== 'undefined' ? mcwcParams.nonce : '';

            this.init();
        }

        /**
         * Initialize plugin kit loader.
         */
        init() {
            this.loadEssentialKit();
            this.bindEvents();
        }
        
        /**
         * Bind install button click events.
         */
        bindEvents() {
            $(document.body).on('click', '.install-now', this.handleInstallClick.bind(this));
        }

        /**
         * Perform AJAX request to load plugin list.
         */
        loadEssentialKit() {
            const wrapper = $(this.wrapperSelector);

            if (wrapper.length === 0) {
                return;
            }

            $.ajax({
                url: mcwcParams.ajax_url,
                method: 'POST',
                dataType: 'html',
                data: {
                    action: 'jthemes_get_plugins_kit',
                    nonce: this.nonce,
                },
                beforeSend: () => {
                    wrapper.addClass('jthemes-kit-loading');
                },
                complete: () => {
                    wrapper.removeClass('jthemes-kit-loading');
                },
                success: (response) => {
                    wrapper.html(response);
                },
                error: () => {
                    wrapper.html('<p class="error">Failed to load plugin kit. Please refresh the page.</p>');
                }
            });
        }

        /**
         * Handle plugin install button click.
         *
         * @param {Event} e
         */
        handleInstallClick(e) {
            e.preventDefault();

            const btn = $(e.currentTarget),
                href  = btn.attr('href');

            btn.addClass('updating-message').text('Installing...');

            $.get(href, () => {
                window.location.reload();
            });
        }
    }

    // Initialize loader
    new JthemesKitLoader();
});
