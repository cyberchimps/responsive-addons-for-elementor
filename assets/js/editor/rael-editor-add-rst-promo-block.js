(function ($) {
    'use strict';
    if (raelEditorAddRstPromoBlock?.isRstActive == 1) {
        return;
    }
    /**
     * Inject CSS for RAEL button dynamically
     */
    function injectRaelButtonCSS() {
        const rstPromoIconUrl = raelEditorAddRstPromoBlock.rstPromoIconUrl;
        const css = `
            .elementor-add-new-section .elementor-add-rael-rst-button {
                display: inline-block;
                width: 40px;
                height: 40px;
                margin-left: 5px !important;
                background-image: url('${rstPromoIconUrl}') !important;
                background-repeat: no-repeat !important;
                background-position: center center !important;
                background-size: contain !important;
                background-color: #a392ad !important;
                border-radius: 50%;
                cursor: pointer;
            }

            .rael-promo-temp-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
            }

            .rael-promo-temp {
                background: #fff;
                width: 1175px;
                display: flex;
                overflow: hidden;
            }
            .rael-promo-subheading{
                font-family: 'Inter', sans-serif;
                font-weight: 700;
                font-style: normal;
                font-size: 30px;
                color: #111827;
                margin-top: 20px;
            }

            .rael-promo-temp--left {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 calc(100% - 754px);
                flex: 0 0 calc(100% - 754px);
                padding: 45px 45px 51px 45px;
                text-align: left;
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                font-style: normal;
                font-size: 18px;

            }
            .rael-promo-temp__feature__list{
                text-align:left;
                padding-left: 20px;
                margin-top: 20px;
                line-height: 33px;
            }
            .rael-promo-temp__feature__list li{
                list-style: circle;
                color: #475569;
            }

            .rael-promo-temp--right img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .rael-promo-temp--right{
                -webkit-box-flex: 0;
                -ms-flex: 0 0 755px;
                flex: 0 0 755px;
                }

            .rael-promo-temp__close {
                position: absolute;
                top: 12px;
                right: 12px;
                cursor: pointer;
            }
            .rael-rst-plugin-installer{
                background: linear-gradient(89.14deg, #2563EB 0%, #00C3FF 102.04%);
                color: #ffffff;
                padding: 12px;
                font-size: 16px;
                font-weight: 600;
                width: 90%;
                border: none;
                border-radius: 10px;
                font-family: 'Inter';
                margin: 30px 0 0 10px;
                cursor: pointer;
            }
            .subhead-text{
                font-size: 48px;
            }
            #rael-rst-promo-popup .dialog-widget-content{
                border-radius: 16px;
            }
            #rael-rst-promo-popup .dialog-widget-content.dialog-lightbox-widget-content{
                top: 150px !important;
                left: 370px !important;
            }
            .rael-promo-temp .eicon-close{
                color: black;
                background-color: white;
                padding: 9px;
                border: 1px solid black;
                border-radius: 5px;
                font-weight: 900;
                font-size: 20px;
            }
            .rael-rst-plugin-installer i{
                margin-left: 5px;
            }
            .promo-success-msg{
                font-size: 14px;
                font-family: 'Inter';
                margin-top: 11px;
                display: inline-block;
                background-color: #fff3cd;
                border: 1px solid #FFDB58;
                border-radius: 5px;
                padding: 9px;
            }
        `;


        // Inject into iframe (for button)
        if (elementor?.$previewContents?.[0]) {
            const iframeHead = elementor.$previewContents[0].head;
            if (iframeHead && !iframeHead.querySelector('#rael-editor-css')) {
                const style = document.createElement('style');
                style.id = 'rael-editor-css';
                style.innerHTML = css;
                iframeHead.appendChild(style);
            }
        }

        // Inject into parent window (for dialog)
        const parentHead = window.top.document.head;
        if (parentHead && !parentHead.querySelector('#rael-editor-css-parent')) {
            const style = document.createElement('style');
            style.id = 'rael-editor-css-parent';
            style.innerHTML = css;
            parentHead.appendChild(style);
        }

    }

    /**
     * Inject RAEL button into Elementor Add Section template
     */
    function injectRaelButton() {
        const addSectionTemplate = $('#tmpl-elementor-add-section');
        if (!addSectionTemplate.length) return;

        // Prevent duplicate injection
        if (addSectionTemplate.data('rael-injected')) return;
        addSectionTemplate.data('rael-injected', true);

        // Insert RAEL button into the template
        let templateHtml = addSectionTemplate.text();
        templateHtml = templateHtml.replace(
            '<div class="elementor-add-section-drag-title',
            '<div class="elementor-add-section-area-button elementor-add-rael-rst-button" title="Add Responsive Starter Templates Library"></div><div class="elementor-add-section-drag-title'
        );
        addSectionTemplate.text(templateHtml);

    }

    /**
     * Bind delegated click event for RAEL button
     */
    function bindRaelButtonClick() {
        if (!elementor || !elementor.$previewContents) return;

        $(elementor.$previewContents[0].body)
            .off('click', '.elementor-add-rael-rst-button')
            .on('click', '.elementor-add-rael-rst-button', function (e) {
                e.preventDefault();
                e.stopPropagation();
                alert('RAEL clicked');
            });
    }
 
    function openRstPromoPopupViaDialog() {

        if (window.raelPromoDialog) {
            return;
        }

        window.raelPromoDialog = elementorCommon.dialogsManager.createWidget(
            'lightbox',
            {
                id: 'rael-rst-promo-popup',
                headerMessage: false,
                message: '',
                hide: {
                    auto: false,
                    onBackgroundClick: true
                },
                position: {
                    my: 'center',
                    at: 'center'
                },
                onShow: function () {
                    
                    const $source = $('#rael-promo-temp-wrap');

                    if (!$source.length) {
                        console.warn('RAEL promo markup not found');
                        return;
                    }

                    // CLONE ONLY INNER CONTENT
                    const $content = $source
                        .find('.rael-promo-temp')
                        .clone(true, true)
                        .css('display', 'flex');

                    $('#rael-rst-promo-popup .dialog-message').html($content);
                },
                onHide: function () {
                    window.raelPromoDialog.destroy();
                    window.raelPromoDialog = null;
                }
            }
        );

        window.raelPromoDialog.getElements('header').remove();
        window.raelPromoDialog.show();
    
    }

    function bindRaelButtonClick() {
        if (!elementor || !elementor.$previewContents) return;

        $(elementor.$previewContents[0].body)
            .off('click.raelPromo')
            .on('click.raelPromo', '.elementor-add-rael-rst-button', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openRstPromoPopupViaDialog();
            });

    }
    function removeRaelRstButton() {

        // iframe
        if (elementor?.$previewContents?.[0]) {
            $(elementor.$previewContents[0].body)
                .find('.elementor-add-rael-rst-button')
                .remove();
        }

        // prevent future injections
        $('#tmpl-elementor-add-section')
            .data('rael-injected', true);
    }


    function bindRstPluginInstaller() {
        if (!elementor || !elementor.$previewContents) return;

        $(document)
            .off('click', '.rael-rst-plugin-installer')
            .on('click', '.rael-rst-plugin-installer', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $btn = $(this);
                if ($btn.hasClass('is-processing')) return;

                $btn
                    .addClass('is-processing')
                    .text('Installing...')
                    .prop('disabled', true);

                $.ajax({
                    url: raelEditorAddRstPromoBlock.ajaxUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'rae_install_rplus_plugin',
                        nonce: raelEditorAddRstPromoBlock.nonce
                    },
                    success(res) {
                        if (res.success) {
                            $btn.text(res.data.message || 'Installed');

                            raelEditorAddRstPromoBlock.isRstActive = 1;
                            removeRaelRstButton();

                            $('#rael-rst-promo-popup .dialog-message')
                                .find('.promo-success-msg')
                                .text(
                                    'Almost there! Save your changes and reload the editor to see ready-made sections.'
                                ).fadeIn();
                        } else {
                            $btn.text('Failed');
                            console.error(res.data);
                        }
                    },
                    error(err) {
                        $btn.text('Error');
                        console.error(err);
                    }
                });
            });

    }

    // Wait for Elementor preview to load
    elementor.on('preview:loaded', function () {
        injectRaelButtonCSS(); 
        injectRaelButton();
        bindRaelButtonClick();
        bindRstPluginInstaller(); 
    });

    $(document).on('click', '.rael-promo-temp__close', function (e) {
        e.preventDefault();

        if (window.raelPromoDialog) {
            window.raelPromoDialog.hide();
        }
    });


})(jQuery);

