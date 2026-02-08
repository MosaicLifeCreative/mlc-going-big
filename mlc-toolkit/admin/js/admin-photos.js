/**
 * MLC Toolkit — Admin Photos Management
 * Handles media library integration, sortable drag-and-drop, add/remove
 */
(function ($) {
    'use strict';

    var $list = $('#mlc-photo-list');

    // Initialize sortable
    $list.sortable({
        handle: '.mlc-photo-item__drag',
        placeholder: 'mlc-photo-item ui-sortable-placeholder',
        tolerance: 'pointer',
        update: function () {
            reindexPhotos();
        }
    });

    // Open media library to select/change a photo
    function openMediaPicker($item) {
        var frame = wp.media({
            title: 'Select Photo',
            multiple: false,
            library: { type: 'image' }
        });

        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();

            $item.find('.mlc-photo-attachment-id').val(attachment.id);
            $item.find('.mlc-photo-url').val(attachment.url);
            $item.find('.mlc-photo-item__img').attr('src', attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url);

            // Auto-fill caption from attachment title if empty
            var $caption = $item.find('.mlc-photo-caption');
            if (!$caption.val()) {
                $caption.val(attachment.title || '');
            }
        });

        frame.open();
    }

    // Change photo button
    $list.on('click', '.mlc-photo-change', function (e) {
        e.preventDefault();
        openMediaPicker($(this).closest('.mlc-photo-item'));
    });

    // Remove photo button
    $list.on('click', '.mlc-photo-remove', function (e) {
        e.preventDefault();
        $(this).closest('.mlc-photo-item').fadeOut(200, function () {
            $(this).remove();
            reindexPhotos();
        });
    });

    // Add new photo
    $('#mlc-add-photo').on('click', function (e) {
        e.preventDefault();

        var index = $list.find('.mlc-photo-item').length;
        var template = $('#tmpl-mlc-photo-item').html();
        var html = template.replace(/\{\{INDEX\}\}/g, index);

        var $newItem = $(html);
        $list.append($newItem);

        // Immediately open media picker for the new item
        openMediaPicker($newItem);
    });

    // Re-index photo array keys after sort or removal
    function reindexPhotos() {
        $list.find('.mlc-photo-item').each(function (i) {
            $(this).attr('data-index', i);
            $(this).find('.mlc-photo-attachment-id').attr('name', 'photos[' + i + '][attachment_id]');
            $(this).find('.mlc-photo-url').attr('name', 'photos[' + i + '][url]');
            $(this).find('.mlc-photo-caption').attr('name', 'photos[' + i + '][caption]');
            $(this).find('.mlc-photo-credit').attr('name', 'photos[' + i + '][credit]');
        });
    }

})(jQuery);
