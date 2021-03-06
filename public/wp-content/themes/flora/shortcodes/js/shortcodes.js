(function ($) {
    "use strict";

    window.$ = $;


    /*****************************************
    Counter Box
    /*****************************************/
    $.fn.wydeCounterBox = function () {

        return this.each(function () {


            $(this).waypoint(function () {

                var $el = $(this).find(".counter-value");

                var start = parseInt($el.text());
                var value = parseInt($el.data("value"));

                $el.countTo({
                    from: start,
                    to: value
                });

                /*
                $({ counterValue: start }).animate({ counterValue: value }, {
                duration: 1500,
                easing: "easeOutCirc",
                step: function () {
                $el.text(Math.round(this.counterValue));
                }
                });
                */

            }, {
                offset: "90%",
                triggerOnce: true
            });

        });

    };

    $.fn.countTo = function (options) {

        var defaults = {
            from: 0,
            to: 100,
            speed: 1000,
            refreshInterval: (1000 / 60) * 2,
            unit: ""
        };

        var settings = $.extend({}, defaults, options || {});

        var loops = Math.ceil(settings.speed / settings.refreshInterval);
        var step = (settings.to - settings.from) / loops;

        return this.each(function () {

            var $el = $(this);
            var value = settings.from;
            var unit = settings.unit ? " " + settings.unit : "";

            var loopCount = 0;
            var interval = setInterval(updateTimer, settings.refreshInterval);

            function updateTimer() {
                value += step;
                loopCount++;
                $el.html(value.toFixed(0) + unit);
                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = settings.to;
                }
            }

        });
    };


    /*****************************************
    Link Icon
    /*****************************************/
    $.fn.wydeLinkIcon = function () {

        return this.each(function () {
            var $el = $(this);
            var color = hex2rgb($el.data("color"), "0.10");
            $el.css({ "color": color });
            $(":after", this).css({ "box-shadow-color": color });
            $el.hover(function () {
                $el.css("color", hex2rgb($el.data("color"), "1"));
            }, function () {
                $el.css("color", hex2rgb($el.data("color"), "0.10"));
            });

        });

    };


    /*****************************************
    Animated Element
    /*****************************************/
    $.fn.wydeAnimated = function () {

        return this.each(function () {

            var $el = $(this);

            $el.css("visibility", "hidden");

            var animation = $el.data("animation");
            if (!animation) return;

            var delay = $el.data("animationDelay");
            if (delay) delay = parseFloat(delay);

            if (!delay) delay = 0;

            delay = delay * 1000;

            var offset = "85%";

            if ($el.offset().top - $("#footer").offset().top < 400) {
                offset = "100%";
            }



            $el.waypoint(function () {
                setTimeout(function () {
                    $el.addClass(animation).addClass("animated").css("visibility", "");
                    setTimeout(function () {
                        $el.removeClass("animated").removeClass(animation);
                    }, 2000);
                }, delay);

            }, {
                offset: offset,
                triggerOnce: true
            });


        });

    };


    /*****************************************
    Google Maps
    /*****************************************/
    $.fn.wydeGMaps = function (options) {

        return this.each(function () {

            var $el = $(this);
            var defaults = {
                icon: $el.data("icon") || "",
                gmaps: $.parseJSON(decodeURIComponent($el.data("maps"))) || {
                    position: {},
                    address: "",
                    zoom: 12,
                    type: 2
                },
                color: $el.data("color") || "#ff0000",
                height: $el.height()
            };

            var settings = $.extend({}, defaults, options || {});

            var mapCanvas = $el.find(".w-map-canvas").height(settings.height).get(0);

            var position = new google.maps.LatLng(settings.gmaps.position.lat, settings.gmaps.position.lng);
            var address = settings.gmaps.address;
            var zoom = settings.gmaps.zoom;
            var type = settings.gmaps.type;

            var mapStyles = [
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": 50 }
                    ]
                }, {
                    "featureType": "road.highway.controlled_access",
                    "elementType": "geometry",
                    "stylers": [
                    { "color": settings.color }
                    ]
                }, {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": 38 }
                    ]
                }, {
                    "featureType": "road.highway.controlled_access",
                    "elementType": "geometry.stroke",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": -26 }
                    ]
                }, {
                    "featureType": "landscape",
                    "stylers": [
                    { "color": settings.color },
                    { "saturation": -67 },
                    { "lightness": 86 }
                    ]
                }, {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                    { "color": settings.color },
                    { "saturation": -47 },
                    { "lightness": 85 }
                    ]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                    { "color": settings.color },
                    { "saturation": -51 },
                    { "lightness": 79 }
                    ]
                }, {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": -50 }
                    ]
                }, {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                    { "color": settings.color },
                    { "saturation": -23 },
                    { "lightness": 60 },
                    { "gamma": 1.31 }
                    ]
                }, {
                    "featureType": "transit.station.airport",
                    "elementType": "geometry.fill",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": 70 }
                    ]
                }, {
                    "featureType": "administrative.locality",
                    "elementType": "labels.text.fill",
                    "stylers": [
                    { "color": settings.color },
                    { "lightness": -50 }

                    ]
                }
            ];

            var wydeMapType = new google.maps.StyledMapType(mapStyles, { name: "Wyde Map" });

            var mapType = google.maps.MapTypeId.ROADMAP;
            switch (type) {
                case "1":
                    mapType = google.maps.MapTypeId.HYBRID;
                    break;
                case "3":
                    mapType = google.maps.MapTypeId.SATELLITE;
                    break;
                case "4":
                    mapType = google.maps.MapTypeId.TERRAIN;
                    break;
                default:
                    mapType = google.maps.MapTypeId.ROADMAP;
                    break;

            }

            var mapOptions = {
                center: position,
                zoom: zoom,
                scrollwheel: false,
                draggable: !wyde.browser.touch,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                scaleControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                panControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                mapTypeControl: false,
                mapTypeControlOptions: {
                    mapTypeIds: [mapType, "wyde_map"],
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                mapTypeId: "wyde_map"
            };

            var map = new google.maps.Map(mapCanvas, mapOptions);
            map.mapTypes.set("wyde_map", wydeMapType);

            var marker = new google.maps.Marker({
                map: map,
                position: position,
                animation: google.maps.Animation.DROP,
                icon: settings.icon
            });


            if (address) {
                var infowindow = new google.maps.InfoWindow({
                    content: address
                });

                google.maps.event.addListener(marker, "click", function () {
                    infowindow.open(map, marker);
                });
            }

        });

    };


    /*****************************************
    Tabs
    /*****************************************/
    $.fn.wydeTabs = function () {

        return this.each(function () {

            var $el = $(this);
            var interval = parseInt($el.data("interval"));
            var timer = null;
            var selectedIndex = 0;
            var items = $el.find(".w-tabs-nav li").length;
            var playing = false;

            var select = function () {

                if (selectedIndex < 0 || selectedIndex >= items) selectedIndex = 0;

                $el.find(".w-tabs-nav li").removeClass("active");
                $el.find(".w-tabs-nav li").eq(selectedIndex).addClass("active");

                $el.find(".w-tab").removeClass("active");
                $el.find(".w-tab").eq(selectedIndex).addClass("active");
                $el.find(".w-tab-wrapper").css("height", $el.find(".w-tab").eq(selectedIndex).height());
            };

            var play = function () {
                if (!playing) {
                    timer = setInterval(function () {
                        selectedIndex++;
                        select();
                    }, interval * 1000);
                    playing = true;
                }
            };

            var pause = function () {
                if (timer) clearInterval(timer);
                playing = false;
            };

            $el.find(".w-tabs-nav li a").off("click").on("click", function (event) {
                event.preventDefault();
                pause();
                var $link = $(this);
                selectedIndex = $el.find(".w-tabs-nav li").index($link.parents("li"));
                select();
                return false;
            });

            select();

            if (interval > 2) {
                play();
                $el.hover(function () {
                    pause();
                }, function () {
                    play();
                });
                $(window).scroll(function () {
                    if ($el.isOnScreen()) {
                        play();
                    } else {
                        pause();
                    }
                });
            }

        });
    };


    /*****************************************
    Accordion
    /*****************************************/
    $.fn.wydeAccordion = function () {


        return this.each(function () {
            var $el = $(this);
            var color = $el.data("color");
            //if (color) color = hex2rgb(color, '0.3');

            if (color) $el.find(".acd-header").css("color", color);

            var activeTab = $el.data("active");
            if (activeTab !== "false" && activeTab != "") {
                $el.find(".acd-content").eq(parseInt(activeTab) - 1).slideDown(300, function () {
                    $(this).parent().addClass("active");
                    if (color) $(this).parent().find(".acd-header").css({ color: "", "background-color": color });
                });
            }



            var collapsible = $el.data("collapsible");

            $el.find(".acd-header").click(function (event) {
                var $parent = $(this).parents(".w-accordion-tab");

                if ($parent.hasClass("active")) {
                    return false;
                } else {
                    var $content = $parent.find(".acd-content");
                    if (!collapsible) {
                        $el.find(".acd-content").not($content).slideUp(300, function () {
                            $(this).parent().removeClass("active");
                            if (color) $(this).parent().find(".acd-header").css({ color: color, "background-color": "" });
                        });
                    }

                    $content.slideDown(300, function () {
                        $parent.addClass("active");
                        if (color) $parent.find(".acd-header").css({ color: "", "background-color": color });

                    });
                }

            });

        });
    };


    /*****************************************
    Toggle
    /*****************************************/
    $.fn.wydeToggle = function () {

        return this.each(function () {

            var $el = $(this);
            var color = $el.data("color");
            //if (color) color = hex2rgb(color, '0.3');
            $el.find(".acd-header").css("color", color);

            if (color && $el.hasClass("active")) {
                $el.find(">h4").css({ "background-color": color });
            }

            $el.on("click", function () {
                $el.find("> div").slideToggle(300, function () {
                    $el.toggleClass("active ");
                    if ($el.hasClass("active")) {
                        $el.find(">h4").css({ "background-color": color });
                    } else {
                        $el.find(">h4").css({ "background-color": "" });
                    }
                });
            });


        });
    };


    /*****************************************
    Progress Bar
    /*****************************************/
    $.fn.wydeProgressBar = function () {

        return this.each(function () {
            var $el = $(this);

            // Collect and sanitize user input
            var value = parseInt($el.data("value")) || 0;
            var unit = $el.data("unit");
            var xPos = 100 - value;
            $el.waypoint(function () {
                $el.find(".w-bar").css(wyde.browser.prefix + "transform", "translateX(-" + xPos + "%)");

                var $counter = $el.find("h4 strong");
                if ($counter.length) {

                    $({ counterValue: 0 }).animate({ counterValue: value }, {
                        duration: 1500,
                        easing: "easeOutCirc",
                        step: function () {
                            $counter.text(Math.round(this.counterValue) + " " + unit);
                        }
                    });
                }

            }, {
                offset: "90%",
                triggerOnce: true
            });

        });
    };


    /*****************************************
    Flickr Stream
    /*****************************************/
    $.fn.wydeFlickrStream = function (options, callback) {

        var defaults = {
            apiUrl: {
                user: "http://api.flickr.com/services/feeds/photos_public.gne",
                group: "https://api.flickr.com/services/feeds/groups_pool.gne"
            },
            count: 9,
            columns: 3,
            size: "_q",
            type: "user",
            lang: "en-us",
            format: "json",
            jsoncallback: "?"

        };

        var settings = $.extend({}, defaults, options || {});

        return this.each(function () {


            var $el = $(this);

            if ($el.data("type")) settings.type = $el.data("type");
            if ($el.data("count")) settings.count = parseInt($el.data("count"));
            if ($el.data("columns")) settings.columns = parseInt($el.data("columns"));
            if ($el.data("id")) settings.id = $el.data("id");

            if (!settings.id) return;

            var apiUri = settings.apiUrl.user;
            if (settings.type == "group") {
                apiUri = settings.apiUrl.group;
            }

            apiUri = String.format("{0}?lang={1}&format={2}&id={3}&jsoncallback={4}", apiUri, settings.lang, settings.format, settings.id, settings.jsoncallback);

            var colName = "";
            if (settings.columns != 5) {
                colName = "col-" + Math.abs(Math.floor(12 / settings.columns));
            } else {
                colName = "five-cols";
            }

            $.getJSON(apiUri, function (data) {
                var list = $("<ul></ul>").addClass("image-list clear");
                $el.find(".w-content").html("").append(list);
                $.each(data.items, function (i, item) {
                    if (i < settings.count) {
                        list.append(String.format("<li class=\"{0}\"><a href=\"{1}\" title=\"{2}\" target=\"_blank\"><img src=\"{3}\" alt=\"{2}\" /></a></li>", colName, item.link, item.title, item.media.m.replace("_m", settings.size)));
                    }
                });

                if (typeof callback == "function") {
                    callback();
                }

            });
        });
    };


    /*****************************************
    TwitterFeed
    /*****************************************/
    $.fn.wydeTwitterFeed = function (options) {

        var defaults = {
            username: "",
            count: 5,
            profileImage: false,
            showTime: false,
            mediaSize: ""  //values: thumb, small, medium, large 

        }

        var settings = $.extend({}, defaults, options || {});


        function xTimeAgo(time) {
            var nd = new Date();
            //var gmtDate = Date.UTC(nd.getFullYear(), nd.getMonth(), nd.getDate(), nd.getHours(), nd.getMinutes(), nd.getMilliseconds());
            var gmtDate = Date.parse(nd);
            var tweetedTime = time * 1000; //convert seconds to milliseconds
            var timeDiff = (gmtDate - tweetedTime) / 1000; //convert milliseconds to seconds

            var second = 1, minute = 60, hour = 60 * 60, day = 60 * 60 * 24, week = 60 * 60 * 24 * 7, month = 60 * 60 * 24 * 30, year = 60 * 60 * 24 * 365;

            if (timeDiff > second && timeDiff < minute) {
                return Math.round(timeDiff / second) + " seconds ago";
            } else if (timeDiff >= minute && timeDiff < hour) {
                return Math.round(timeDiff / minute) + " minutes ago";
            } else if (timeDiff >= hour && timeDiff < day) {
                return Math.round(timeDiff / hour) + " hours ago";
            } else if (timeDiff >= day && timeDiff < week) {
                return Math.round(timeDiff / day) + " days ago";
            } else if (timeDiff >= week && timeDiff < month) {
                return Math.round(timeDiff / week) + " weeks ago";
            } else if (timeDiff >= month && timeDiff < year) {
                return Math.round(timeDiff / month) + " months ago";
            } else {
                return "over a year ago";
            }

        }


        return this.each(function () {

            var $el = $(this);

            if ($el.data("username")) settings.username = $el.data("username");
            if ($el.data("count")) settings.count = parseInt($el.data("count"));
            if ($el.data("profileImage")) settings.profileImage = $el.data("profileImage");
            if ($el.data("showTime")) settings.showTime = $el.data("showTime");
            if ($el.data("mediaSize")) settings.mediaSize = $el.data("mediaSize");

            var urlpattern = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            var media = "";

            $.getJSON("https://www.api.tweecool.com/?screenname=" + settings.username + "&count=" + settings.count, function (data) {

                if (data.errors || data == null) {
                    $el.html("No tweets available.");
                    return false;
                }

                $el.html("");

                var profileImage = "";

                if (settings.profileImage) {
                    profileImage = "<a href=\"https://twitter.com/" + settings.username + "\" target=\"_blank\"><img src=\"" + data.user.profile_image_url.replace("normal", "bigger") + "\" alt=\"" + settings.username + "\" /></a>";
                } else {
                    profileImage = "<i class=\"flora-icon-twitter\"></i>";
                }

                profileImage = "<div class=\"profile-image\">" + profileImage + "</div>";

                $el.append(profileImage);

                var $list = $("<div/>").appendTo($el);

                $.each(data.tweets, function (i, field) {

                    if (settings.showTime) {
                        var timestamp = xTimeAgo(field.timestamp);
                    } else {
                        var timestamp = '';
                    }

                    if (settings.mediaSize != "" && field.media_url) {
                        media = "<a href=\"https://twitter.com/" + settings.username + "\" target=\"_blank\" class=\"tweet-media\"><img src=\"" + field.media_url + ":" + settings.mediaSize + "\" alt=\"" + settings.username + "\" /></a>";
                    } else {
                        media = "";
                    }

                    $list.append("<div class=\"tweet-item\">" + field.text.replace(urlpattern, "<a href=\"$1\" target=\"_blank\">$1</a>") + media + " <span class=\"tweet-date\">" + timestamp + "</span></div>");
                });


                $list.owlCarousel({
                    autoHeight: true,
                    autoplayHoverPause: true,
                    navText: ["", ""],
                    items: 1,
                    autoplay: 5000,
                    nav: false,
                    dots: true,
                    loop: true,
                    themeClass: "",
                    animateIn: "fadeIn",
                    animateOut: "fadeOut"
                });

            }).fail(function (jqxhr, textStatus, error) {
                $el.html("No tweets available");
            });

        });

    };


    /*****************************************
    Fade SlideShow
    /*****************************************/
    $.fn.wydeFadeSlider = function (options) {

        var defaults = {
            autoplayTimeout: 100,
            interval: 1500,
            activeClass: "active",
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoplay: false
        };

        var settings = $.extend({}, defaults, options || {});

        return this.each(function () {

            var $el = $(this);

            var $slides = $el.find("> *");

            if( $slides.length < 2){
                return;
            }

            $el.addClass("w-fadeslider");

            $slides.addClass("slide");

            var slideCount = $slides.length - 1;

            var activeSlide = 0;

            $slides.eq(activeSlide).addClass(settings.activeClass);

            var timer = false;

            var play = function () {

                var timeOut = settings.autoplayTimeout;
                timer = setTimeout(function () {

                    $slides.eq(activeSlide).removeClass(settings.activeClass).removeClass(settings.animateIn).addClass(settings.animateOut);

                    if (activeSlide >= slideCount) {
                        activeSlide = 0;
                    } else {
                        activeSlide = activeSlide + 1;
                    }

                    $slides.eq(activeSlide).removeClass(settings.animateOut).addClass(settings.animateIn).addClass(settings.activeClass);

                    timeOut = settings.interval;

                    timer = setTimeout(function () {
                        play();
                    }, timeOut);

                }, timeOut);

            }

            var stop = function () {
                clearInterval(timer);
                timer = false;
            }

            if (settings.autoplay) {
                play();
                $el.hover(function () {
                    stop();
                }, function () {
                    play();
                });
            } else {
                $el.hover(function () {
                    play();
                }, function () {
                    stop();
                });
            }
        });





    }


    /*****************************************
    Donut Chart
    /*****************************************/
    $.fn.wydeDonutChart = function (options, callback) {

        var defaults = {
            startdegree: 0,
            color: "#21242a",
            bgcolor: "#eee",
            fill: false,
            width: 15,
            dimension: 250,
            value: 50,
            animationstep: 1.0,
            border: 'default',
            complete: null
        };

        return this.each(function () {

            var settings = $.extend({}, defaults, options || {});

            var customSettings = ["color", "bgcolor", "fill", "width", "dimension", "animationstep", "endPercent", "border", "startdegree"];

            var percent;
            var endPercent = 0;
            var el = $(this);
            var fill = false;
            var type = "";

            checkDataAttributes(el);

            type = el.data("type");


            if (el.data("total") != undefined && el.data("part") != undefined) {
                var total = el.data("total") / 100;

                percent = ((el.data("part") / total) / 100).toFixed(3);
                endPercent = (el.data("part") / total).toFixed(3);
            } else {
                if (el.data("value") != undefined) {
                    percent = el.data("value") / 100;
                    endPercent = el.data("value");
                } else {
                    percent = defaults.value / 100;
                }
            }


            el.width(settings.dimension + "px");

            if (type == "half") {
                el.height(settings.dimension / 2);
            }

            var size = settings.dimension,
                canvas = $("<canvas></canvas>").attr({
                    width: size,
                    height: size
                }).appendTo(el).get(0);

            var context = canvas.getContext("2d");

            var dpr = window.devicePixelRatio;
            if (dpr) {
                var $canvas = $(canvas);
                $canvas.css("width", size);
                $canvas.css("height", size);
                $canvas.attr("width", size * dpr);
                $canvas.attr("height", size * dpr);

                context.scale(dpr, dpr);
            }

            var container = $(canvas).parent();
            var x = size / 2;
            var y = size / 2;
            var radius = size / 2.5;
            var degrees = settings.value * 360.0;
            var radians = degrees * (Math.PI / 180);
            var startAngle = 2.3 * Math.PI;
            var endAngle = 0;
            var counterClockwise = false;
            var curPerc = settings.animationstep === 0.0 ? endPercent : 0.0;
            var curStep = Math.max(settings.animationstep, 0.0);
            var circ = Math.PI * 2;
            var quart = Math.PI / 2;
            var fireCallback = true;
            var additionalAngelPI = (settings.startdegree / 180) * Math.PI;


            if (type == "half") {
                startAngle = 2.0 * Math.PI;
                endAngle = 3.13;
                circ = Math.PI;
                quart = Math.PI / 0.996;
            } else if (type == "angle") {
                startAngle = 2.25 * Math.PI;
                endAngle = 2.4;
                circ = 1.53 + Math.PI;
                quart = 0.73 + Math.PI / 0.996;
            }


            function checkDataAttributes(el) {
                $.each(customSettings, function (index, attribute) {
                    if (el.data(attribute) != undefined) {
                        settings[attribute] = el.data(attribute);
                    } else {
                        settings[attribute] = $(defaults).attr(attribute);
                    }

                    if (attribute == "fill" && el.data("fill") != undefined) {
                        fill = true;
                    }
                });
            }

            function animate(current) {

                context.clearRect(0, 0, canvas.width, canvas.height);

                context.beginPath();
                context.arc(x, y, radius, endAngle, startAngle, false);

                context.lineWidth = settings.width;

                context.strokeStyle = settings.bgcolor;
                context.stroke();

                if (fill) {
                    context.fillStyle = settings.fill;
                    context.fill();
                }

                context.beginPath();
                context.arc(x, y, radius, -(quart) + additionalAngelPI, ((circ) * current) - quart + additionalAngelPI, false);

                var borderWidth = settings.width;
                if (settings.border == "outline") {
                    borderWidth = settings.width + 13;
                } else if (settings.border == "inline") {
                    borderWidth = settings.width - 13;
                }

                context.lineWidth = borderWidth;
                context.strokeStyle = settings.color;
                context.stroke();

                if (curPerc < endPercent) {
                    curPerc += curStep;
                    window.requestAnimationFrame(function () {
                        animate(Math.min(curPerc, endPercent) / 100);
                    });
                }

                if (curPerc == endPercent && fireCallback && typeof (settings) != "undefined") {
                    if ($.isFunction(settings.complete)) {
                        settings.complete();

                        fireCallback = false;
                    }
                }
            }

            el.waypoint(function () {
                animate(curPerc / 100);
            }, {
                offset: "100%",
                triggerOnce: true
            });





        });
    };


    /*****************************************
    Scroll More
    /*****************************************/
    $.fn.wydeScrollmore = function (options) {

        var defaults = {
            autoTriggers: 0,
            nextSelector: ".w-next",
            contentSelector: ".w-item",
            callback: false
        };

        var settings = $.extend({}, defaults, options || {});

        return this.each(function () {

            var $el = $(this);
            var loading = false;
            var $next;
            var elIndex = $(".w-scrollmore").index($el);

            function loadContent() {

                if (loading == true) return;

                var $loader = $("<div class=\"post-loader la-ball-triangle-path\"><div></div><div></div><div></div></div>");

                var url = $next.attr("href");

                if (!url) return;

                $next.replaceWith($loader);

                loading = true;

                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "html",
                    success: function (response) {

                        var $data = $(response);

                        var $newItems = $data.find(".w-scrollmore:eq(" + elIndex + ") " + settings.contentSelector).css({ opacity: 0 });

                        if ($newItems.length > 0) {

                            var $parent = $el.find(settings.contentSelector).parent();
                            $parent.append($newItems);
                            $parent.waitForImages({
                                finished: function () {

                                    $newItems.css({ opacity: 1 });

                                    if (typeof settings.callback == "function") {
                                        settings.callback($newItems);
                                    }

                                    $loader.css("opacity", 0);

                                    $loader.replaceWith($data.find(".w-scrollmore:eq(" + elIndex + ") " + settings.nextSelector));

                                    loading = false;

                                    init();

                                },
                                waitForAll: true
                            });
                        }


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        loading = false;
                    }
                });



            }

            function init() {

                $next = $el.find(settings.nextSelector);

                if (settings.autoTriggers) {
                    $next.waypoint(function () {
                        loadContent();
                    }, {
                        offset: "100%"
                    });
                }

                $next.off("click").on("click", function (event) {
                    event.preventDefault();
                    loadContent();
                    return false;
                });
            }

            init();


        });
    };


    /*****************************************
    *   Initialize Shortcodes
    /*****************************************/
    /* Parallax Background */
    $.fn.wydeParallax = function (options) {

        var defaults = {
            speed: 0.3
        };

        var settings = $.extend({}, defaults, options || {});

        var $doc = $(document), $win = $(window);

        var winHeight = $win.height();
        var docHeight = $doc.height();

        return this.each(function () {

            var $el = $(this);
            var isBusy = false;

            var boxPos = $el.offset().top;
            var boxHeight = $el.height();
            var speed = settings.speed;

            var reverse = $el.hasClass("w-reverse");
            var fade = $el.hasClass("w-fadeout");

            var $bg = $el.find(".bg-image");
            var $content, contentHeight, contentTop, contentHalf, winHalf;

            function refresh() {
                boxHeight = $el.height();
                if (reverse || fade) {
                    $content = $el.find(".container");
                    contentHeight = $content.height();
                    contentTop = $content.offset().top;
                    contentHalf = contentHeight / 2;
                } else {
                    speed = 44 / (winHeight + boxHeight);
                }
            }

            function render() {

                if ($el.isOnScreen()) {

                    var scrollPos = $win.scrollTop();
                    boxPos = $el.offset().top;
                    if (reverse || fade) {

                        var yPos = Math.round((scrollPos - (boxPos)) * speed);
                        $bg.css(wyde.browser.prefix + "transform", "translate3d(0px, " + yPos + "px, 0px)");

                        if (fade) {
                            var viewport = getViewPort();
                            winHalf = winHeight / 2;
                            if (viewport.bottom > (contentTop + contentHeight - contentHalf + winHalf)) {
                                var o = 1 - ((scrollPos + contentHeight - contentTop) / contentHeight);
                                $content.css('opacity', o);
                            }

                        } else {
                            $content.css('opacity', '1');
                        }

                    } else {
                        var yPos = Math.abs(scrollPos - (boxPos - winHeight)) * speed * -1;
                        $bg.css(wyde.browser.prefix + "transform", "translate3d(0px, " + yPos + "%, 0px)");
                    }
                }

            }

            function requestRender() {
                if (!isBusy) {
                    isBusy = true;
                    window.requestAnimationFrame(function () {
                        render();
                        isBusy = false;
                    });
                }
            }

            $win.on("scroll", function () {
                requestRender();
            });

            $(window).smartresize(function () {
                winHeight = $win.height();
                docHeight = $doc.height();
                refresh();
            });

            refresh();
            requestRender();

        });

    };

    function initSectionParallax() {
        if (wyde.browser.css3 && !wyde.browser.sm) {
            setTimeout(function () {
                $(".w-section.w-parallax").wydeParallax();
            }, 500);
        }
    }

    /* Column */
    function initResponsiveColumn() {

        var clearColumnHeight = function () {
            $(".w-v-align .row .col-resp").each(function () {
                $(this).css("height", "");
            });
        };

        var setColumnHeight = function () {

            if (wyde.browser.sm) {
                clearColumnHeight();

                $(".w-v-align .row").each(function () {

                    var heights = $(this).find(".col-resp").map(function () {
                        return $(this).outerHeight();
                    }).get();

                    if (heights.length) {
                        var maxHeight = Math.max.apply(null, heights);
                        $(this).find(".col-resp").each(function () {
                            $(this).css("height", parseInt(maxHeight));
                        });
                    }

                });
            } else {
                clearColumnHeight();
            }

        };


        $(window).smartresize(function () {
            setColumnHeight();
        });

        setColumnHeight();

    }

    /* Button */
    function initButton() {
        $(".w-link-button[href^='#'], a.w-button[href^='#'], a.w-ghost-button[href^='#']").on("click", function (event) {
            var $el = $(this);
            var hash = $el.attr("href");
            if (!hash) {
                return true;
            } else if (hash == '#') {
                event.preventDefault();
                return false;
            } else {
                event.preventDefault();

                var target = hash;

                if (hash == "#nextsection") {
                    target = $el.parents(".w-section").next(".w-section");
                    if (!target.length) target = $(".w-section").first();
                } else if (hash == "#prevsection") {
                    target = $el.parents(".w-section").prev(".w-section");
                    if (!target.length) target = $(".w-section").last();
                }

                wyde.page.scrollTo(target);

                return false;
            }
        });

        $(".w-link-button").each(function () {

            var $el = $(this);

            if ($el.attr("style") || $el.data("hover-color")) {
                var color = $el.css("color");
                if (!color) color = "";

                var hoverColor = $el.data("hover-color");
                if (!hoverColor) hoverColor = "";

                $el.hover(function () {
                    $el.css("color", hoverColor);
                }, function () {
                    $el.css("color", color);
                });
            }

        });

    };

    /* Icon Block */
    function initIconBlock() {

        $(".w-icon-block:not(.w-none):not(.w-effect-none)").each(function () {
            var $el = $(this);
            var color = $el.css("border-color");
            $el.hover(function () {
                $el.css("color", color);
            }, function () {
                $el.css("color", "");
            });

        });

    }

    /* Info Box */
    function initInfoBox() {

        $(".w-info-box.w-circle").each(function () {
            var $el = $(this);
            var $icon = $el.find(".w-icon");
            if ($icon.attr("style")) {
                var color = $icon.css("border-color");
                if (!color) color = "";
                $el.hover(function () {
                    $icon.css({ "color": color, "background-color": "" });
                }, function () {
                    $icon.css({ "color": "", "background-color": color });
                });
            }

        });

    }

    /* Gallery Sliders */
    function initGalleryCarousel(p) {
        if (!p) p = document.body;
        $(p).find(".w-fadeslider").wydeFadeSlider();
    }

    /* WooCommerce Products */
    function initWooCommerceProducts() {

        $(".woocommerce .products").find(".w-item .cover-image > a").wydeFadeSlider();

        $(window).on("added_to_cart", function (event, fragments, cart_hash, button) {
            $(button).next(".added_to_cart").show();
            setTimeout(function () {
                $(button).next(".added_to_cart").addClass("active");
            }, 100);

            setTimeout(function () {
                $(button).next(".added_to_cart").removeClass("active");
            }, 5000);

            setTimeout(function () {
                $(button).next(".added_to_cart").hide();
            }, 5200);

        });

        /* Single Product */
        $(".single-product .images .thumbnails").addClass("owl-carousel").owlCarousel({
            navText: ["", ""],
            items: 3,
            nav: true,
            dots: false,
            themeClass: ""
        });

        $(".single-product.woocommerce .images .thumbnails a").on("click", function (event) {
            event.preventDefault();
            var newUrl = $(this).attr("href");
            $(".woocommerce-main-image").attr("href", newUrl);
            $(".woocommerce-main-image img").attr("src", newUrl);
            return false;
        });
    }

    /* Carousel */
    function initCarousel(p) {

        if (!p) p = document.body;

        $(p).find(".owl-carousel").not(".portfolio-slider .post-media").each(function () {

            var $el = $(this);
            $el.waitForImages({
                finished: function () {

                    var items = 1;
                    if ($el.data("items") != undefined) items = parseInt($el.data("items"));

                    var loop = false;
                    if ($el.data("loop") != undefined) loop = $el.data("loop");
                    if ($el.find(">div").length <= 1) loop = false;

                    var animateIn = false;
                    var animateOut = false;
                    if ($el.data("transition")) {
                        switch ($el.data("transition")) {
                            case "fade":
                                animateIn = "fadeIn";
                                animateOut = "fadeOut";
                                break;
                        }
                    }

                    var autoPlay = false;
                    if ($el.data("autoPlay") != undefined) autoPlay = $el.data("autoPlay");

                    var autoHeight = false;
                    if ($el.data("autoHeight") != undefined) autoHeight = $el.data("autoHeight");

                    var navigation = true;
                    if ($el.data("navigation") != undefined) navigation = $el.data("navigation");

                    var pagination = false;
                    if ($el.data("pagination") != undefined) pagination = $el.data("pagination");

                    $el.owlCarousel({
                        autoHeight: autoHeight,
                        autoplayHoverPause: true,
                        navText: ["", ""],
                        items: items,
                        slideBy: items,
                        autoplay: (autoPlay != false),
                        autoplayTimeout: autoPlay ? 4000 : false,
                        nav: navigation,
                        dots: pagination,
                        loop: loop,
                        themeClass: "",
                        animateIn: animateIn,
                        animateOut: animateOut,
                        responsive: {
                            0: {
                                items: 1,
                                slideBy: 1
                            },
                            992: {
                                items: items > 2 ? 2 : items,
                                slideBy: items > 2 ? 2 : items
                            },
                            1200: {
                                items: items,
                                slideBy: items
                            }
                        }
                    });
                },
                waitForAll: true
            });

        });

        $(p).find(".portfolio-slider .post-media").each(function () {

            var $el = $(this);
            $el.waitForImages({
                finished: function () {
                    var loop = $el.find("> div").length > 1;
                    $el.owlCarousel({
                        autoplayHoverPause: true,
                        navText: ["", ""],
                        items: 3,
                        autoplay: false,
                        nav: true,
                        dots: false,
                        loop: loop,
                        autoWidth: true,
                        center: true,
                        themeClass: "",
                        onInitialized: function () {
                            initPrettyPhoto(this.$stage.find(".cloned"));
                        }
                    });
                },
                waitForAll: true
            });

        });

    }

    /* Scroll More */
    function initScrollMore() {

        $(".w-scrollmore").each(function () {

            var $el = $(this);

            var prefix = $el.hasClass("w-portfolio-grid") ? ".w-portfolio-grid " : ".w-blog-posts ";

            var nextSelector = ".w-showmore .w-next";
            if (!$el.find(nextSelector).length) return;
            var trigger = 3;
            if ($el.data("trigger") != null) trigger = parseInt($el.data("trigger") || 0);

            var contentSelector = ".w-item";
            if ($el.data("selector") != null) contentSelector = $el.data("selector");

            $el.wydeScrollmore({
                autoTriggers: trigger,
                nextSelector: nextSelector,
                contentSelector: contentSelector,
                callback: function (newElements) {

                    // Portfolio Effect
                    if ($el.find(".w-view.w-effect-split").length) {
                        initPortfolioEffect(newElements);
                    }

                    // Isotope masonry
                    var $view = $el.find(".w-view");
                    var iso = $view.data("isotope");
                    if (iso) iso.appended(newElements);

                    // Slider
                    initCarousel(newElements);
                    initGalleryCarousel(newElements);
                    initTouchDevices();

                    // Ajax Page
                    if (typeof wyde.page.updateLink == "function") {
                        wyde.page.updateLink(newElements);
                    }

                    // PrettyPhoto
                    initPrettyPhoto(newElements);


                }
            });
        });
    }

    /* Team Carousel */
    function initTeamCarousel() {
        $(".w-team-slider").each(function () {
            var $el = $(this);
            $el.waitForImages({
                finished: function () {
                    setTimeout(function () {
                        $el.find(".member-desc").wydeScroller();
                    }, 500);
                },
                waitForAll: true
            });
        });

    }

    /* Grid View */
    function initGridView() {

        $(".w-portfolio-grid, .w-blog-posts.w-grid, .w-blog-posts.w-masonry, .w-image-gallery.w-masonry").each(function () {

            var $grid = $(this);

            $grid.waitForImages({
                finished: function () {

                    var isMasonry = $grid.hasClass("w-masonry");

                    var $view = $grid.find(".w-view");

                    $view.isotope({
                        itemSelector: ".w-item",
                        transitionDuration: '0.6s',
                        layoutMode: isMasonry ? "masonry" : "fitRows",
                        masonry: { columnWidth: isMasonry ? $view.width() / 12 : ".w-item" }
                    });


                    $(window).smartresize(function () {
                        $view.isotope({
                            layoutMode: isMasonry ? "masonry" : "fitRows",
                            masonry: { columnWidth: isMasonry ? $view.width() / 12 : ".w-item" }
                        });
                    });


                    $grid.find(".w-filter").each(function () {

                        var $el = $(this);
                        var $filters = $el.parents(".w-filterable");
                        var $p = $filters.length ? $filters : $(document.body);

                        $el.find("li a").click(function (event) {

                            event.preventDefault();
                            var hash = getHash(this.href);
                            if (hash) {
                                hash = hash.replace("#", ".");
                                if (hash == ".all") hash = "*";
                                $p.find(".w-view").isotope({ filter: hash });
                            }

                            $el.find("li").removeClass("active");
                            $(this).parent().addClass("active");
                            return false;
                        });
                    });
                },
                waitForAll: true
            });

        });


    }

    /* PrettyPhoto */
    function initPrettyPhoto(el) {

        if (!el) el = document.body;

        var $elements = $(el);

        if (!$elements.length) return;

        if ($elements.prop("tagName") != "A") $elements = $elements.find("a[data-rel^='prettyPhoto']");

        $elements.prettyPhoto({
            theme: '',
            hook: 'data-rel',
            deeplinking: false,
            social_tools: false,
            overlay_gallery: false,
            show_title: false,
            horizontal_padding: 0,
            allow_resize: true,
            default_width: 1170,
            default_height: 658,
            changepicturecallback: function () {},
            markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_content_container"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"><span class="w-loader"></span></div> \
									<div class="pp_fade"> \
										<a href="#" class="pp_expand" title="Expand the image"></a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_previous" href="#"></a> \
											<a class="pp_next" href="#"></a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
                                            <div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous"></a> \
												<span class="currentTextHolder">0/0</span> \
												<a href="#" class="pp_arrow_next"></a> \
											</div> \
										</div> \
									</div> \
								</div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>',
            inline_markup: '<div class="pp_inline">{content}</div>',
            iframe_markup: '<div class="video-wrapper"><iframe src ="{path}" width="100%" height="{height}" frameborder="no"></iframe></div>'

        });
    }

    /* Portfolio Hover Effect */
    function initPortfolioEffect(el) {

        var $elements = el ? $(el) : $(".w-portfolio-grid .w-effect-split .w-item");

        var isMasonry = $(".w-portfolio-grid").hasClass("w-layout-flora");

        var generateSplitter = function(){
            var width = $elements.not(".w-w2").not(".w-h2").first().width();

            $elements.each(function () {
                var $el = $(this);

                if (isMasonry) {    

                    $el.height(width);
                    if($el.hasClass("w-h2")){
                        $el.css("height", (width*2)+20);                              
                    }
                }

                //$el.find("figure").css({ width: $el.width(), height: $el.height() });

                var $image = $el.find(".cover-image");
                if($image.length){
                    var imageUrl = $image.prop("tagName") == "IMG" ? $image.attr("src") : $image.css("background-image");
                    if(imageUrl) imageUrl = imageUrl.replace(/^url|[\(\)]/g, '');
                
                    $image.wrap("<div class=\"cover-wrapper\"></div>");
                    $image.remove();

                    var $wrapper = $el.find(".cover-wrapper");
                    $wrapper.append("<div class=\"splitter-1\"><img src=\"" + imageUrl + "\" /></div><div class=\"splitter-2\"><img src=\"" + imageUrl + "\" /></div>");
                }
            });
        }

        $(window).smartresize(function () {            
            generateSplitter();
        });

        generateSplitter();

    }

    /* Blog Posts */
    function initBlogPost() {

        if( typeof($.fn.mediaelementplayer) == "function"){
            setTimeout(function(){
                $("audio.wp-audio-shortcode, video.wp-video-shortcode").mediaelementplayer();
            }, 500);
        }
 
        $(".post-detail .meta-comment a").on("click", function () {
            var $el = $(this);
            var hash = getHash($el.attr("href"));
            if (!hash) {
                return true;
            } else if (hash == '#') {
                event.preventDefault();
                return false;
            } else {
                event.preventDefault();
                wyde.page.scrollTo(hash);
                return false;
            }
        });
        
    }

    /* Main Slider */
    function initMainSlider() {
        $(".w-revslider").find(".w-scroll-button a").off("click").on("click", function (event) {
            event.preventDefault();
            var $nextSection = getNextSection($(this).parents(".w-section"));
            if ($nextSection.length) {
                wyde.page.scrollTo($nextSection);
            }
            return false;
        });
    }

    function getNextSection(current) {
        var $sections = $(".w-section");
        var idx = $sections.index(current);
        return $sections.eq(idx + 1);
    }

    function initTouchDevices(){
        if (wyde.browser.touch){
            $(".w-info-box.w-large, .w-blog-posts .w-item.w-h2, .w-blog-posts .w-item.w-w2, .w-blog-posts .post-share, .w-portfolio-grid .w-item figure, .w-flickr").addClass("touch-hover");
            $("html:not(.no-touch) .touch-hover").unbind("mouseenter mouseleave").hover(function() {            
                $(this).addClass("hover");
            }, function(){
                $(this).removeClass("hover");
            });
        }
    }

    /*****************************************
    Call on Wyde Page Ready event 
    /*****************************************/
    $(window).on("wyde.page.ready", function () {

        initButton();
        initIconBlock();
        initInfoBox();
        initCarousel();
        initGalleryCarousel();
        initGridView();
        initPortfolioEffect();
        initBlogPost();
        initWooCommerceProducts();
        initScrollMore();

        $("img[data-retina]").retina();
        $(".w-progress-bar").wydeProgressBar();
        $(".w-accordion").wydeAccordion();
        $(".w-toggle").wydeToggle();
        $(".w-tabs, .w-tour").wydeTabs();
        $(".w-counter-box").wydeCounterBox();
        $(".w-donut-chart").wydeDonutChart();
        $(".w-flickr").wydeFlickrStream();
        $(".w-twitter").wydeTwitterFeed();
        $(".w-gmaps").wydeGMaps();        
            
        if (wyde.browser.xs) {
            if (wyde.page.mobile_animation) {
                $("[data-animation]").wydeAnimated();
            }
        } else {
            $("[data-animation]").wydeAnimated();
        }

        initTeamCarousel();
        initPrettyPhoto();
        initMainSlider();
        initResponsiveColumn();        
        initTouchDevices();
        
        if (!wyde.browser.touch && !wyde.browser.sm) {
            initSectionParallax();
        }

    });

})(jQuery);