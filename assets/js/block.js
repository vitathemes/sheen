/*--------------------------------------*\
  #Register Block style
\*--------------------------------------*/
window.addEventListener("load", function () {
    wp.domReady(() => {
        /*
         * Register Blocks Styles for gallery
         */
        wp.blocks.registerBlockStyle("core/gallery", [
            {
                name: "masonry-grid",
                label: "Masonry Grid Layout",
                inline_style: ".wp-block-gallery.is-style-masonry-grid.js-has-masonry",
            },
            {
                name: "carousel-layout",
                label: "Carousel Layout",
                inline_style: ".wp-block-gallery.has-carousel-style",
            },
        ]);
    });
});
