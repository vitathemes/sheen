/*--------------------------------------*\
  #Start jQuery
\*--------------------------------------*/
jQuery(function ($) {
    $(".js-main__filter-items").on("click", function () {
        $(".js-main__filter-wrapper").toggleClass("is-open");

        $(".js-filter").slideToggle();
    });

    /*--------------------------------------*\
      #Category Filter  
    \*--------------------------------------*/
    $(".js-filter__list").on("change", function () {
        $("#filter").submit();
    });

    $("#filter").submit(function () {
        var filter = $("#filter");
        $.ajax({
            url: filter.attr("action"),
            data: filter.serialize(), // form data
            type: filter.attr("method"), // POST
            beforeSend: function (xhr) {},
            success: function (data) {
                filter.find("button").text("Apply filter"); // changing the button label back
                $("#response").html(data); // insert data

                setTimeout(function () {
                    $(".js-main__body-has-masonry").masonry("reloadItems");
                    $(".js-main__body-has-masonry").masonry();
                }, 10);
            },
        });
        return false;
    });

    $("#filter > .js-filter__cat").on("click", function () {
        $("#filter .js-filter__cat").removeClass("active");
        $(this).addClass("active");
    });

    /*--------------------------------------*\
      Masonry Grids
    \*--------------------------------------*/
    $(".js-main__body-has-masonry").masonry({
        // options
        itemSelector: ".js-post-has-masonry",
        gutter: 48,
        fitWidth: true,
        horizontalOrder: true,
    });

    /*--------------------------------------*\
      #Scroll to top & Focus on logo
    \*--------------------------------------*/
    $(".js-footer__to-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 1000);

        setTimeout(() => {
            if (brilliance_childFinder("body", "custom-logo-link")) {
                $(".custom-logo-link").focus();
            } else {
                $(".c-header__title__anchor").focus();
            }
        }, 1000);
    });

    /*------------------------------------*\
      #Handle Load More button
    \*------------------------------------*/
    $(document).ready(function () {
        const brilliance_loadMoreButton = $(".js-pagination__load-more__btn");
        $(".js-pagination__load-more").click(function () {
            // setTimeout(function () {
            //     brilliance_lazyLoadInstance.update();
            // }, 1000);

            var loadMore = $(this),
                data = {
                    action: "loadmore",
                    query: loadmore_params.posts,
                    page: loadmore_params.current_page,
                };
            $.ajax({
                url: loadmore_params.ajaxurl,
                data: data,
                type: "POST",
                beforeSend: function (xhr) {
                    brilliance_loadMoreButton.text("Loading . . . ");
                },
                success: function (data) {
                    setTimeout(function () {
                        $(".js-main__body-has-masonry").masonry("reloadItems");
                        $(".js-main__body-has-masonry").masonry();
                    }, 1);

                    if (data) {
                        loadMore.prev().after(data);
                        brilliance_loadMoreButton.text("Load More");
                        loadmore_params.current_page++;
                        if (loadmore_params.current_page == loadmore_params.max_page)
                            loadMore.remove();
                    } else {
                        loadMore.remove();
                    }
                },
            });
        });
    });
});
/*--------------------------------------*\
  #END jQuery
\*--------------------------------------*/

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

/*--------------------------------------*\
  #Filter Button
\*--------------------------------------*/
// if (brilliance_childFinder("body", "js-filter__items")) {
//     const filterComponent = document.querySelector(".js-filter");
//     const filterBtn = document.querySelector(".js-filter__items");

//     filterBtn.addEventListener("click", function () {
//         filterComponent.classList.toggle("is-open");
//     });
// }
