/*--------------------------------------*\
  #Start jQuery
\*--------------------------------------*/
jQuery(function ($) {
    /*--------------------------------------*\
      #Category Filter Toggle
    \*--------------------------------------*/
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

    $(".is-style-masonry-grid").masonry({
        // options
        itemSelector: ".wp-block-image",
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

    /*--------------------------------------*\
      #Carousel - Single Page
    \*--------------------------------------*/
    $(".is-style-carousel-layout").flickity({
        setGallerySize: false,
        imagesLoaded: true,
        cellAlign: "left",
        prevNextButtons: false,
        lazyLoad: true,
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

/*------------------------------------*\
  #Fade Out Vanilla JS
\*------------------------------------*/
function brilliance_fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        if ((el.style.opacity -= 0.1) < 0) {
            el.style.display = "none";
        } else {
            requestAnimationFrame(fade);
        }
    })();
}

/*------------------------------------*\
  #Fade In Vanilla JS
\*------------------------------------*/
function brilliance_fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || "block";
    (function fade() {
        let val = parseFloat(el.style.opacity);
        if (!((val += 0.1) > 1)) {
            el.style.opacity = val;
            requestAnimationFrame(fade);
        }
    })();
}

/*------------------------------------*\
  #Search Box toggle
\*------------------------------------*/
if (brilliance_childFinder("body", "js-nav__search-button")) {
    const brilliance_searchBtn = document.querySelector(".js-nav__search-button");
    const brilliance_seacrhClose = document.querySelector(".js-btn-seacrh-close");
    const brilliance_searchOverlay = document.querySelector(".js-header__search-overlay");

    const brilliance_navList = document.querySelector(".js-nav__list");
    const brilliance_primaryNav = document.querySelector(".js-nav__toggle");

    // Search Button Clicked
    brilliance_searchBtn.addEventListener("click", function () {
        brilliance_searchBtn.classList.toggle("is-toggled");

        // Search form trap focus
        if (brilliance_searchBtn.classList.contains("is-toggled")) {
            setTimeout(() => {
                brilliance_seacrhClose.focus();
            }, 10);

            // Backward
            const brilliance_searchFieldx = document.querySelector(".search-field");
            brilliance_seacrhClose.addEventListener("blur", function (e) {
                if (brilliance_IsBackward) {
                    brilliance_searchFieldx.focus();
                }
            });
            // Forward
            const brilliance_headerSearchButton = document.querySelector(".c-search-form__submit");
            brilliance_headerSearchButton.addEventListener("blur", function (e) {
                if (brilliance_IsBackward === false) {
                    brilliance_seacrhClose.focus();
                }
            });
        }

        /* Trap Focus */
        brilliance_fadeIn(brilliance_searchOverlay, "flex");
        brilliance_navList.classList.add("is-hidden");
        brilliance_primaryNav.classList.add("is-hidden");
    });

    brilliance_seacrhClose.addEventListener("click", function () {
        brilliance_fadeOut(brilliance_searchOverlay);
        brilliance_navList.classList.remove("is-hidden");
        brilliance_primaryNav.classList.remove("is-hidden");
    });
}

/*--------------------------------------*\
  #Detect keyboard navigation action
\*--------------------------------------*/
let brilliance_IsBackward;
document.addEventListener("keydown", function (e) {
    if (e.shiftKey && e.keyCode == 9) {
        // Shift + tab
        brilliance_IsBackward = true;
    } else {
        // Tab
        brilliance_IsBackward = false;
    }
});

/*--------------------------------------*\
  #Menu Trap Focus
\*--------------------------------------*/

if (brilliance_childFinder("body", "js-nav__toggle")) {
    const brilliance_menuBtn = document.querySelector(".js-nav__toggle");
    const brilliance_menuSerach = document.querySelector(".js-nav__search-button");

    brilliance_menuBtn.addEventListener("click", function () {
        brilliance_menuBtn.classList.toggle("is-open");

        if (brilliance_menuBtn.classList.contains("is-open")) {
            brilliance_menuBtn.addEventListener("blur", function () {
                if (brilliance_IsBackward) {
                    brilliance_menuSerach.focus();
                }
            });

            brilliance_menuSerach.addEventListener("blur", function () {
                if (!brilliance_IsBackward) {
                    brilliance_menuBtn.focus();
                }
            });
        }
    });
}
