/*--------------------------------------*\
  Masonry Grid
\*--------------------------------------*/
const brillianceMainGrid = document.querySelector(".js-main__body-has-masonry");
const brillianceMainGridMasonry = new Masonry(brillianceMainGrid, {
    // options
    itemSelector: ".js-post-has-masonry",
    columnWidth: 200,
});
