/*--------------------------------------*\
  #Detect Element inside other element
\*--------------------------------------*/
function sheen_childFinder(parentElement, childElement) {
    let sheen_result = document.querySelector(parentElement).getElementsByClassName(childElement)[0]
        ? true
        : false;
    return sheen_result;
}

/*--------------------------------------*\
  #Register Block style
\*--------------------------------------*/
window.addEventListener("load", function () {
    if (sheen_childFinder("body", "block-editor__container")) {
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

            wp.blocks.registerBlockStyle("core/paragraph", [
                {
                    name: "paragraph-no-margin",
                    label: "No Paragraph Margin",
                },
            ]);

            wp.blocks.registerBlockStyle("core/heading", [
                {
                    name: "heading-no-margin",
                    label: "No Heading Margin",
                },
            ]);
        });
    }
});
