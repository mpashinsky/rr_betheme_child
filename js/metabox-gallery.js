(function($) {
    $(function() {
        // The click event for the gallery manage button
        $('#manage_gallery').click(function() {
            // Create the shortcode from the current ids in the hidden field
            var gallerysc = '[gallery ids="' + $('#product_gallery_ids').val() + '"]';
            // Open the gallery with the shortcode and bind to the update event
            wp.media.gallery.edit(gallerysc).on('update', function(g) {
                // We fill the array with all ids from the images in the gallery
                var id_array = [];
                $.each(g.models, function(id, img) { id_array.push(img.id); });
                // Make comma separated list from array and set the hidden value
                $('#product_gallery_ids').val(id_array.join(","));
                // On the next post this field will be send to the save hook in WP
            });
        });
    });
})(jQuery);