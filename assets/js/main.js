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

    $(".js-filter__cat").on("keyup", function (e) {
        if (event.key === "Enter") {
            const sheen_currentAttr = $(this).attr("for");
            const sheen_selectedItem = $(".c-filter__list[id='" + sheen_currentAttr + "']");
            $(sheen_selectedItem).prop("checked", true);
            $("#filter").submit();
        }
    });

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

    /*--------------------------------------*\
      #Add active class on click 
    \*--------------------------------------*/
    $("#filter > .js-filter__cat").on("click", function () {
        $("#filter .js-filter__cat").removeClass("active");
        $(this).addClass("active");
    });

    /*--------------------------------------*\
      #Add active class on enter key 
    \*--------------------------------------*/
    $(".js-filter__cat").on("keyup", function () {
        if (event.key === "Enter") {
            $("#filter .js-filter__cat").removeClass("active");
            $(this).addClass("active");
        }
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

    if (sheen_childFinder("body", "blocks-gallery-grid")) {
        $(".is-style-masonry-grid > .blocks-gallery-grid").masonry({
            // options
            itemSelector: ".blocks-gallery-item",
            gutter: 48,
            fitWidth: true,
            horizontalOrder: true,
        });
    } else {
        $(".is-style-masonry-grid").masonry({
            // options
            itemSelector: ".wp-block-image",
            gutter: 48,
            fitWidth: true,
            horizontalOrder: true,
        });
    }

    /*--------------------------------------*\
      #Scroll to top & Focus on logo
    \*--------------------------------------*/
    $(".js-footer__to-top").click(function () {
        $("html, body").animate({ scrollTop: 0 }, 1000);

        setTimeout(() => {
            if (sheen_childFinder("body", "custom-logo-link")) {
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
        const sheen_loadMoreButton = $(".js-pagination__load-more__btn");
        $(".js-pagination__load-more").click(function () {
            // setTimeout(function () {
            //     sheen_lazyLoadInstance.update();
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
                    sheen_loadMoreButton.text("Loading . . . ");
                },
                success: function (data) {
                    setTimeout(function () {
                        $(".js-main__body-has-masonry").masonry("reloadItems");
                        $(".js-main__body-has-masonry").masonry();
                    }, 1);

                    if (data) {
                        loadMore.prev().after(data);
                        sheen_loadMoreButton.text("Load More");
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
let sheen_clientWindowSize = window.matchMedia("(max-width: 979px)");
function sheen_isMobile(sheen_clientWindowSize) {
    if (sheen_clientWindowSize.matches) {
        // If media query matches
        return true;
    } else {
        return false;
    }
}

sheen_isMobile(sheen_clientWindowSize); // Call listener function at run time
sheen_clientWindowSize.addListener(sheen_isMobile); // Attach listener function on state changes

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
  Images LazyLoad initialization 
\*--------------------------------------*/
if (
    sheen_childFinder("body", "js-single__masonry-img") ||
    sheen_childFinder("body", "js-single__carousel-img")
) {
    const sheen_lazyLoadInstance = new LazyLoad({
        elements_selector: [".js-single__masonry-img", ".js-single__carousel-img"],
    });
}

/*------------------------------------*\
  #Fade Out Vanilla JS
\*------------------------------------*/
function sheen_fadeOut(el) {
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
function sheen_fadeIn(el, display) {
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
if (sheen_childFinder("body", "js-nav__search-button")) {
    const sheen_searchBtn = document.querySelector(".js-nav__search-button");
    const sheen_seacrhClose = document.querySelector(".js-btn-seacrh-close");
    const sheen_searchOverlay = document.querySelector(".js-header__search-overlay");

    const sheen_navList = document.querySelector(".js-nav__list");
    const sheen_primaryNav = document.querySelector(".js-nav__toggle");

    // Search Button Clicked
    sheen_searchBtn.addEventListener("click", function () {
        sheen_searchBtn.classList.toggle("is-toggled");

        // Search form trap focus
        if (sheen_searchBtn.classList.contains("is-toggled")) {
            setTimeout(() => {
                sheen_seacrhClose.focus();
            }, 10);

            // Backward
            const sheen_searchFieldx = document.querySelector(".search-field");
            sheen_seacrhClose.addEventListener("blur", function (e) {
                if (sheen_IsBackward) {
                    sheen_searchFieldx.focus();
                }
            });
            // Forward
            const sheen_headerSearchButton = document.querySelector(".c-search-form__submit");
            sheen_headerSearchButton.addEventListener("blur", function (e) {
                if (sheen_IsBackward === false) {
                    sheen_seacrhClose.focus();
                }
            });
        }

        /* Trap Focus */
        sheen_fadeIn(sheen_searchOverlay, "flex");
        sheen_navList.classList.add("is-hidden");
        sheen_primaryNav.classList.add("is-hidden");
    });

    sheen_seacrhClose.addEventListener("click", function () {
        sheen_fadeOut(sheen_searchOverlay);
        sheen_navList.classList.remove("is-hidden");
        sheen_primaryNav.classList.remove("is-hidden");
    });
}

/*--------------------------------------*\
  #Detect keyboard navigation action
\*--------------------------------------*/
let sheen_IsBackward;
document.addEventListener("keydown", function (e) {
    if (e.shiftKey && e.keyCode == 9) {
        // Shift + tab
        sheen_IsBackward = true;
    } else {
        // Tab
        sheen_IsBackward = false;
    }
});

/*--------------------------------------*\
  #Menu Trap Focus
\*--------------------------------------*/
if (sheen_childFinder("body", "js-nav__toggle")) {
    const sheen_menuBtn = document.querySelector(".js-nav__toggle");
    const sheen_menuSerach = document.querySelector(".js-nav__search-button");

    sheen_menuBtn.addEventListener("click", function () {
        sheen_menuBtn.classList.toggle("is-open");

        if (sheen_menuBtn.classList.contains("is-open")) {
            sheen_menuBtn.addEventListener("blur", function () {
                if (sheen_IsBackward) {
                    sheen_menuSerach.focus();
                }
            });

            sheen_menuSerach.addEventListener("blur", function () {
                if (!sheen_IsBackward) {
                    sheen_menuBtn.focus();
                }
            });
        }
    });
}

/*----------------------------------------------*\
  #Filter Label selectable for (accessibility)
\*----------------------------------------------*/
// if (sheen_childFinder("body", "c-filter__cat")) {
//     const sheen_filterItems = document.querySelectorAll(".");
//     for (let i = 0; i < sheen_filterItems.length; i++) {
//         sheen_filterItems[i].addEventListener("", function () {});
//     }
// }
