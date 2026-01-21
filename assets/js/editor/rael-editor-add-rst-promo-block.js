(function ($) {
    'use strict';
    
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
                background-size: cover !important;
                background-color: #a392ad !important;
                border-radius: 50%;
                cursor: pointer;
            }
                .rael-promo-temp-wrap {
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: rgba(0,0,0,0.6);
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
    max-width: 95%;
    border-radius: 8px;
    display: flex;
    overflow: hidden;
}
.rael-promo-subheading{
      font-family: 'Inter', sans-serif;
font-weight: 700;
font-style: normal;
font-size: 30px;
}

.rael-promo-temp--left {
     -webkit-box-flex: 0;
    -ms-flex: 0 0 calc(100% - 755px);
    flex: 0 0 calc(100% - 755px);
    padding: 45px 30px 30px;
    width: 310px;
    text-align: left;
    font-family: 'Inter', sans-serif;
font-weight: 500;
font-style: normal;
font-size: 18px;

}
.rael-promo-temp__feature__list{
    text-align:left;
    padding-left: 20px;
}
.rael-promo-temp__feature__list li{
list-style: circle;
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
    }
    .subhead-text{
        font-size: 40px;
    }
        `;

        // Check if editor iframe exists
        if (elementor && elementor.$previewContents && elementor.$previewContents[0]) {
            const head = elementor.$previewContents[0].head || elementor.$previewContents[0].querySelector('head');
            if (head && !head.querySelector('#rael-editor-ral-button-css')) {
                const style = document.createElement('style');
                style.id = 'rael-editor-ral-button-css';
                style.innerHTML = css;
                head.appendChild(style);
                console.log('RAEL button CSS injected into editor iframe head');
            }
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

        console.log('RAEL button injected into template');
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

        console.log('RAEL button click event bound');
    }
    function openRstPromoPopup() {
        if (!elementor || !elementor.$previewContents) return;

        const iframeBody = elementor.$previewContents[0].body;
        let $popup = $(iframeBody).find('#rael-promo-temp-wrap');

        // If popup HTML exists in parent but not iframe → move it
        if (!$popup.length && $('#rael-promo-temp-wrap').length) {
            $popup = $('#rael-promo-temp-wrap').appendTo(iframeBody);
        }

        if ($popup.length) {
            $popup.fadeIn(200);
        }
    }

    function bindPopupCloseEvents() {
        if (!elementor || !elementor.$previewContents) return;

        const iframeBody = elementor.$previewContents[0].body;

        $(iframeBody)
            .on('click', '.rael-promo-temp__close', function (e) {
                e.preventDefault();
                $('#rael-promo-temp-wrap', iframeBody).fadeOut(200);
            })
            .on('click', '#rael-promo-temp-wrap', function (e) {
                if (e.target === this) {
                    $(this).fadeOut(200);
                }
            });
    }

    function bindRaelButtonClick() {
        if (!elementor || !elementor.$previewContents) return;

        $(elementor.$previewContents[0].body)
            .off('click', '.elementor-add-rael-rst-button')
            .on('click', '.elementor-add-rael-rst-button', function (e) {
                e.preventDefault();
                e.stopPropagation();
                openRstPromoPopup();
            });

        console.log('RAEL button click event bound');
    }
    function bindRstPluginInstaller() {
    if (!elementor || !elementor.$previewContents) return;

    const iframeBody = elementor.$previewContents[0].body;

    $(iframeBody)
        .off('click', '.rael-rst-plugin-installer')
        .on('click', '.rael-rst-plugin-installer', function (e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('RAEL RST installer clicked');

            const $btn = $(this);
            if ($btn.hasClass('is-processing')) return;

            $btn
                .addClass('is-processing')
                .text('Processing...')
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
                        $btn.text(res.data.message);
                        // Small delay so WP finishes activation
                        setTimeout(function () {
                            if (window.elementor && elementor.reloadPreview) {
                                elementor.reloadPreview();
                            }
                        }, 1200);
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

    console.log('RAEL RST installer click bound');
}
    // Wait for Elementor preview to load
    elementor.on('preview:loaded', function () {
        injectRaelButtonCSS(); // inject CSS first
        injectRaelButton();
        bindRaelButtonClick();
        bindPopupCloseEvents();
        bindRstPluginInstaller(); 
    });

})(jQuery);

