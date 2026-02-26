/**
 * MLC Toolkit — Admin Proposals
 * Service toggles, media picker, color preview, custom items, PIN regeneration
 */
(function ($) {
    'use strict';

    var catalog = (window.mlcProposalAdmin && window.mlcProposalAdmin.serviceCatalog) || {};

    // ─── SERVICE TOGGLES ─────────────────────────────────────
    $(document).on('change', '.mlc-service-toggle', function () {
        var $row  = $(this).closest('.mlc-service-row');
        var $body = $row.find('.mlc-service-row__body');

        if (this.checked) {
            $row.addClass('mlc-service-row--active');
            $body.slideDown(200);
            // Enable the inputs so they submit
            $body.find('textarea, input, select').prop('disabled', false);
        } else {
            $row.removeClass('mlc-service-row--active');
            $body.slideUp(200);
            // Disable inputs so they don't submit
            $body.find('textarea, input, select').prop('disabled', true);
        }
    });

    // On load, disable inputs in unchecked services
    $('.mlc-service-row').each(function () {
        var $row = $(this);
        if (!$row.find('.mlc-service-toggle').is(':checked')) {
            $row.find('.mlc-service-row__body textarea, .mlc-service-row__body input, .mlc-service-row__body select').prop('disabled', true);
        }
    });

    // Reset service description to catalog default
    $(document).on('click', '.mlc-service-reset', function () {
        var slug = $(this).data('slug');
        if (catalog[slug]) {
            $(this).closest('.mlc-service-row').find('.mlc-service-desc').val(catalog[slug].description);
        }
    });

    // ─── CLIENT LOGO PICKER ──────────────────────────────────
    $('#mlc-logo-upload').on('click', function (e) {
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Client Logo',
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;

            $('#mlc-logo-id').val(attachment.id);
            $('#mlc-logo-img').attr('src', url);
            $('#mlc-logo-preview').show();
            $('#mlc-logo-upload').text('Change Logo');
            $('#mlc-logo-remove').show();
        });

        frame.open();
    });

    $('#mlc-logo-remove').on('click', function (e) {
        e.preventDefault();
        $('#mlc-logo-id').val('');
        $('#mlc-logo-img').attr('src', '');
        $('#mlc-logo-preview').hide();
        $('#mlc-logo-upload').text('Upload Logo');
        $(this).hide();
    });

    // ─── COLOR SWATCH PREVIEW ────────────────────────────────
    $('#accent_color').on('input', function () {
        var color = $(this).val();
        if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
            $('#mlc-color-swatch').css('background', color);
        }
    });

    // ─── CUSTOM LINE ITEMS ───────────────────────────────────
    $('#mlc-add-custom-item').on('click', function (e) {
        e.preventDefault();
        var index    = $('#mlc-custom-items .mlc-custom-item').length;
        var template = $('#tmpl-mlc-custom-item').html();
        var html     = template.replace(/\{\{INDEX\}\}/g, index);
        $('#mlc-custom-items').append(html);
    });

    $(document).on('click', '.mlc-custom-remove', function (e) {
        e.preventDefault();
        $(this).closest('.mlc-custom-item').fadeOut(200, function () {
            $(this).remove();
            reindexCustomItems();
        });
    });

    function reindexCustomItems() {
        $('#mlc-custom-items .mlc-custom-item').each(function (i) {
            $(this).attr('data-index', i);
            $(this).find('[name]').each(function () {
                var name = $(this).attr('name');
                $(this).attr('name', name.replace(/custom_items\[\d+\]/, 'custom_items[' + i + ']'));
            });
        });
    }

    // ─── PIN REGENERATION ────────────────────────────────────
    $('#mlc-regen-pin').on('click', function (e) {
        e.preventDefault();
        var pin = String(Math.floor(1000 + Math.random() * 9000));
        $('#pin').val(pin);
    });

})(jQuery);
