/*--------------------------------------*\
  #Detect screen size
\*--------------------------------------*/
let brilliance_clientWindowSize = window.matchMedia("(max-width: 979px)");
function brilliance_isMobile(brilliance_clientWindowSize) {
    if (brilliance_clientWindowSize.matches) {
        // If media query matches
        return true;
    } else {
        return false;
    }
}

brilliance_isMobile(brilliance_clientWindowSize); // Call listener function at run time
brilliance_clientWindowSize.addListener(brilliance_isMobile); // Attach listener function on state changes

/*--------------------------------------*\
  #Detect Element inside other element
\*--------------------------------------*/
function brilliance_childFinder(parentElement, childElement) {
    let brilliance_result = document
        .querySelector(parentElement)
        .getElementsByClassName(childElement)[0]
        ? true
        : false;
    return brilliance_result;
}

/*--------------------------------------*\
  Masonry Grids
\*--------------------------------------*/
if (brilliance_childFinder("body", "js-main__body-has-masonry")) {
    const brillianceMainGrid = document.querySelector(".js-main__body-has-masonry");
    const brillianceMainGridMasonry = new Masonry(brillianceMainGrid, {
        // options
        itemSelector: ".js-post-has-masonry",
        gutter: 48,
        fitWidth: true,
        horizontalOrder: true,
    });
}

if (brilliance_childFinder("body", "js-single__masonry")) {
    const brillianceMainGrid = document.querySelector(".js-single__masonry");
    const brillianceMainGridMasonry = new Masonry(brillianceMainGrid, {
        // options
        itemSelector: ".js-single__masonry-image__wrapper",
        resize: true,
        gutter: 50,
        fitWidth: true,
        horizontalOrder: true,
    });
}

/*--------------------------------------*\
  Images LazyLoad initialization 
\*--------------------------------------*/
if (
    brilliance_childFinder("body", "js-single__masonry-img") ||
    brilliance_childFinder("body", "js-single__carousel-img")
) {
    const brilliance_lazyLoadInstance = new LazyLoad({
        elements_selector: [".js-single__masonry-img", ".js-single__carousel-img"],
    });
}

/*--------------------------------------*\
  #Carousel - Single Page
\*--------------------------------------*/
if (brilliance_childFinder("body", "js-single__carousel-slider")) {
    let carouselSingle = document.querySelector(".js-single__carousel-slider");

    let flCarouselSingle = new Flickity(carouselSingle, {
        setGallerySize: false,
        imagesLoaded: true,
        cellAlign: "left",
        prevNextButtons: false,
        lazyLoad: true,
    });
}
