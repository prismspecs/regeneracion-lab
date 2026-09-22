// Appearance > Hero Photos: pick/remove Media Library images for the homepage pool.
jQuery(function ($) {
    var $input = $('#regen-hero-photos-input');
    var $list = $('#regen-hero-photos-list');
    var $empty = $('#regen-hero-photos-empty');
    var frame;

    function sync() {
        var ids = $list.children('li').map(function () { return $(this).data('id'); }).get();
        $input.val(ids.join(','));
        $empty.prop('hidden', ids.length > 0);
    }

    $list.on('click', '.regen-hero-remove', function () {
        $(this).closest('li').remove();
        sync();
    });

    $('#regen-hero-photos-add').on('click', function (e) {
        e.preventDefault();
        if (!frame) {
            frame = wp.media({
                title: 'Choose hero photos',
                button: { text: 'Add to hero photos' },
                library: { type: 'image' },
                multiple: 'add'
            });
            frame.on('select', function () {
                frame.state().get('selection').each(function (att) {
                    var data = att.toJSON();
                    if ($list.children('li[data-id="' + data.id + '"]').length) return;
                    var thumb = (data.sizes && data.sizes.medium) ? data.sizes.medium.url : data.url;
                    var $li = $('<li style="position:relative;width:180px;"></li>').attr('data-id', data.id).data('id', data.id);
                    $li.append($('<img alt="" style="width:180px;height:120px;object-fit:cover;display:block;">').attr('src', thumb));
                    $li.append('<button type="button" class="button-link-delete regen-hero-remove" style="position:absolute;top:4px;right:4px;background:#fff;padding:2px 8px;border:1px solid #c3c4c7;cursor:pointer;">&times;</button>');
                    $list.append($li);
                });
                sync();
            });
        }
        frame.open();
    });
});
