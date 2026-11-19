$(document).ready(function () {
    
    $(".slick-slider").slick({
        slidesToShow: 1,
        infinite:false,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        dots: false, 
        fade:true,
        arrows: false, 
  });
  
 
    $(".connectivity").on("click", function () {
      $(".location-section .col-lg-4").toggleClass('active')
  });
  $(".cross_x").on("click", function () {
      $(".tradingview-widget-container").hide()
  });
      

    $('.banner-slider').slick({
        dots: false,
        infinite: true,
        speed: 1000,
        autoplay: true,
        autoplaySpeed: 5000,
        slidesToShow: 1,
        fade:true,
        arrows: false, 
        adaptiveHeight: true
    });

  
  
  $('.final_toggle').hide();

$(".seo_sugg").on("click", function () {

    $(".final_toggle").stop(true, true).slideToggle(400, function () {

        if ($(this).is(":visible")) {

            $('html, body').animate({
                scrollTop: $('.final_toggle').offset().top - 100
            }, 500);

        }

    });

});
  
  


$("#projectEnquiryForm").validate({

    rules: {
        first_name: { required: true },
        last_name: { required: true },
        email: {
            required: true,
            email: true
        },
        phone: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
        },
        project: { required: true },
        message: { required: true }
    },

    messages: {
        first_name: "Please enter your first name",
        last_name: "Please enter your last name",
        email: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
        },
        phone: {
            required: "Please enter your phone number",
            digits: "Please enter numbers only",
            minlength: "Phone number must be 10 digits",
            maxlength: "Phone number must be 10 digits"
        },
        project: "Please select a project",
        message: "Please enter your message"
    },

    errorElement: "span",
    errorClass: "form-error",

    errorPlacement: function(error, element) {
        error.insertAfter(element);
    },

    highlight: function(element) {
        $(element).addClass("input-error");
    },

    unhighlight: function(element) {
        $(element).removeClass("input-error");
    },

   
submitHandler: function(form) {

    var $form = $(form);
    var $button = $form.find('button[type="submit"]');
    var $loader = $('#projectsLoader');

    $button.prop('disabled', true);

    // Show existing loader
    $loader.show();

    $.ajax({

        url: 'project_form_backend.php',
        type: 'POST',
        data: $form.serialize(),
        dataType: 'json',

        success: function(response) {

            console.log("Backend Response:", response);

            if (response.status === 'success') {
                // window.location.href = '../thankyou.php';
                return;
            }

            alert(response.message || 'Something went wrong. Please try again.');

        },

        error: function(xhr, status, error) {

            console.log("AJAX Error:", error);
            console.log("Response:", xhr.responseText);

            alert('Unable to submit the form. Please try again.');

        },

        complete: function() {

            // Hide loader
            $loader.hide();

            // Enable submit button
            $button.prop('disabled', false);

        }

    });

    return false;
}


});



    
  
  if ($(window).width() < 991) {
        $(".navbar-toggler-icon").click(function () {
            $(this).toggleClass("cross");
        });

       $(".navbar-expand-lg .navbar-nav .nav-link2").click(function () {
            $(".navbar-toggler-icon").removeClass("cross");
            $(".navbar-collapse").removeClass("show");
        });
        
    };
        
$(".type-hide").on("change", function () {

    let fileName = this.files[0].name;

    $(".file-name").text(fileName);

}); 
    
   // Left Slider Initialization
   $('.manhattan-galley-slider1').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 5000,
    speed: 600,
    prevArrow: '.gallery-prev-btn',
    nextArrow: '.gallery-next-btn',
    centerMode: true,
    centerPadding: '200px',
    responsive: [
        {
            breakpoint: 992,
            settings: {
                centerPadding: '100px'
            }
        },
        {
            breakpoint: 767,
            settings: {
                centerPadding: '40px',
                 centerMode: true,
            }
        }
    ]
});

    // Right Slider Initialization
    // $('.manhattan-galley-slider2').slick({
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     asNavFor: '.manhattan-galley-slider1', // Left slider se sync
    //     dots: false,
    //     arrows: false, // Default right me arrow nahi chahiye
    //     focusOnSelect: true
    // });
    
    
$(".leader-plus-btn2").on("click", function (e) {

        e.preventDefault();

        var title = $(this).data("title");
        var designation = $(this).data("designation");
        var content = $(this).data("content");

        $("#modalTitle").text(title);
        $("#modalDesignation").text(designation);
        $("#modalContent").text(content);

    });
    
     
    
  
    
   
    
    $(".testimonial-slider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 5000,
    speed: 600,
    arrows: false,
    dots: true,
    pauseOnHover: true,
    pauseOnFocus: true
});
    
    var $achievementCarousel = $(".achievement-carousel");

    $achievementCarousel.slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: false,
        dots: false,
        pauseOnHover: true,
        pauseOnFocus: true,

        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });


    // Custom Previous Button
    $(".achievement-prev").on("click", function () {
        $achievementCarousel.slick("slickPrev");
    });


    // Custom Next Button
    $(".achievement-next").on("click", function () {
        $achievementCarousel.slick("slickNext");
    });
    
    $(".vision-slider").slick({
    slidesToShow: 1,
    infinite: true,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 5000,
    arrows: true,
    prevArrow: $(".vision-prev"),
    nextArrow: $(".vision-next"),
    dots: false
});


if ($(window).width() > 767) {
         $(".launch-slider").slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: false,
        
            prevArrow: $(".launch-prev"),
            nextArrow: $(".launch-next")
        });
         
         $(".launch-prev").on("click", function () {
    $achievementCarousel.slick("slickPrev");
});

$(".launch-next").on("click", function () {
    $achievementCarousel.slick("slickNext");
});
    };
    
    
    if ($(window).width() < 767) {
         $(".launch-slider, .mobile_slider").slick({
            slidesToShow: 1,
            infinite:true,
            slidesToScroll: 1,
            autoplay: true,
            loop:true,
            arrows:false,
            autoplaySpeed: 5000,
            dots: false,
            centerMode: true,
            centerPadding: '40px',
         });
    };




 // Loader animation
    setTimeout(function () {

        var $loader = $(".loader");

        if ($loader.length) {
            $loader.addClass("active");
        }

    }, 800);



    // Changing words animation
    var $words = $(".changing-words span");

    if ($words.length) {

        var current = 0;

        $words.eq(0).addClass("active");

        var wordInterval = setInterval(function () {

            // Last word par animation stop
            if (current >= $words.length - 1) {
                clearInterval(wordInterval);
                return;
            }

            var $currentWord = $words.eq(current);

            current++;

            var $nextWord = $words.eq(current);

            $currentWord
                .removeClass("active")
                .addClass("exit");

            $nextWord
                .removeClass("exit")
                .addClass("active");

            setTimeout(function () {
                $currentWord.removeClass("exit");
            }, 500);

        }, 2500);

    }
    
    
    
    
    wow = new WOW({
        boxClass: "wow",
        animateClass: "animated",
        offset: 0,
        mobile: true,
        live: true
    }); wow.init();
    
    
      // cusrot button
    
    let lastScrollTop = 0;

$(window).on("scroll", function () {

    let scrollTop = $(this).scrollTop();

    if (scrollTop > lastScrollTop) {

        // Scroll Down
        $("header").removeClass("nav-up ").addClass("nav-down");

    } else {

        // Scroll Up
        $("header").removeClass("nav-down").addClass("nav-up");

    }

    lastScrollTop = scrollTop;

});
    
   $(window).on('scroll', function () {
        if ($(this).scrollTop() > 50) {
            $('.hero-header').addClass('scrolled');
        } else {
            $('.hero-header').removeClass('scrolled');
        }
    });
    
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 250) {
            $('.enquire_btn').addClass('active');
        } else {
            $('.enquire_btn').removeClass('active');
        }
    });
    
    
    
 
let statsSection = document.querySelector(".stats-section1");
    let odometerStarted = false;

    if (!statsSection) return;

    const observer = new IntersectionObserver(function (entries) {

        entries.forEach(function (entry) {

            if (entry.isIntersecting && !odometerStarted) {

                odometerStarted = true;

                $(".odometer2").each(function () {

                    let element = this;
                    let value = parseInt($(element).attr("data-value"), 10);

                    let odometer = new Odometer({
                        el: element,
                        value: 0,
                        theme: "minimal",
                        duration: 2000
                    });

                    odometer.update(value);

                });

                observer.disconnect();
            }

        });

    }, {
        threshold: 0.3
    });

    observer.observe(statsSection);
    
    
    
   
    
    
    // Initialize all sliders

    $('.luxury-project-slider').each(function () {

        $(this).slick({

            slidesToShow: 3.5,
            slidesToScroll: 1,
            infinite: false,
            arrows: false,
            dots: false,
            speed: 500,
            adaptiveHeight: false,
            responsive: [
                {
                    breakpoint: 1400,
                    settings: {
                        slidesToShow: 3
                    }
                },

                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },

                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        centerMode: true,
                        centerPadding: '20px',
                    }
                }

            ]

        });

    });


    // Bootstrap Tab Event

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (event) {

        const target = $(event.target).data('bs-target');

        const slider = $(target).find('.luxury-project-slider');

        if (slider.hasClass('slick-initialized')) {

            slider.slick('setPosition');

            slider.slick('slickGoTo', 0, true);

        }

    });
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
 // Careers js
 
 $.validator.addMethod("resumeFile", function (value, element) {

    if (element.files.length === 0) {
        return false;
    }

    var file = element.files[0];

    var fileName = file.name.toLowerCase();

    var allowedExtensions = [
        ".pdf",
        ".doc",
        ".docx"
    ];

    var validExtension = allowedExtensions.some(function (extension) {
        return fileName.endsWith(extension);
    });

    if (!validExtension) {
        return false;
    }

    if (file.size > 5 * 1024 * 1024) {
        return false;
    }

    return true;

}, "Please upload a valid PDF, DOC or DOCX file (max 5 MB).");


// Download BRoucher



    $('#brochure_phone').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    $.validator.addMethod("validBrochurePhone", function (value, element) {
        return this.optional(element) || /^[0-9]{10}$/.test(value);
    }, "Please enter a valid 10-digit phone number.");

    $('#brochureEnquiryForm').validate({

        rules: {
            first_name: {
                required: true,
               // minlength: 2,
                maxlength: 100
            },
            email: {
                required: true,
                email: true,
                maxlength: 150
            },
            phone: {
                required: true,
                validBrochurePhone: true
            }
        },

        messages: {
            first_name: {
                required: "Please enter your first name.",
                minlength: "Name must be at least 2 characters."
            },
            email: {
                required: "Please enter your email.",
                email: "Please enter a valid email address."
            },
            phone: {
                required: "Please enter your phone number.",
                validBrochurePhone: "Please enter a valid 10-digit phone number."
            }
        },

        errorElement: "span",
        errorClass: "error",

        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },

        submitHandler: function (form) {

            $('#projectsLoader').show();

            // $('#brochureEnquiryForm .row').hide();

            $('#brochureSubmitBtn')
                .prop('disabled', true)
                .find('span')
                .text('Submitting...');

            form.submit();
            
             return false;
        }
    });


   
});







document.addEventListener("DOMContentLoaded", function () {

    const accordion = document.getElementById("impactAccordion");
    if (!accordion) return;

    const images = document.querySelectorAll(".impact-photo-box");

    accordion.addEventListener("show.bs.collapse", function (event) {

        images.forEach(function (image) {
            image.classList.add("d-none");
        });

        const imageId = event.target.id + "Image";
        const activeImage = document.getElementById(imageId);

        if (activeImage) {
            activeImage.classList.remove("d-none");
        }
    });

});




// Header Search 
document.addEventListener("DOMContentLoaded", function () {

    const overlay = document.getElementById("ajmeraSearchOverlay");
    const openButton = document.getElementById("openSearch");
    const closeButton = document.getElementById("closeAjmeraSearch");

    if (!overlay || !openButton || !closeButton) return;

    // Open search overlay
    function openSearch() {
        overlay.classList.add("is-open");
        document.body.classList.add("ajmera-search-open");

        openButton.setAttribute("aria-expanded", "true");
    }

    // Close search overlay
    function closeSearch() {
        overlay.classList.remove("is-open");
        document.body.classList.remove("ajmera-search-open");

        openButton.setAttribute("aria-expanded", "false");
    }

    openButton.addEventListener("click", openSearch);
    closeButton.addEventListener("click", closeSearch);

    // Close with Escape key
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeSearch();
        }
    });

});

function filterYear(year) {
    console.log("Selected Year:", year);

    const url = new URL(window.location.href);
    url.searchParams.set("financial_year", year);

    window.location.href = url.toString();
}












// Newsletter Investor Financial Year Filter


$(document)
    .off('change.newsletter', '#cgrFinancialYear')
    .on('change.newsletter', '#cgrFinancialYear', function () {

        const financialYear = $(this).val();

        const $tableBody = $('#cgrTableBody');

        console.log('Selected Year:', financialYear);
        console.log('Dropdown count:', $('#cgrFinancialYear').length);
        console.log('Table body count:', $tableBody.length);

        if ($tableBody.length !== 1) {
            console.error('cgrTableBody missing or duplicated!');
            return;
        }

        $.ajax({
            url: 'investor_data/newsletter-filter.php',
            method: 'POST',
            data: { financial_year: financialYear },
            cache: false,

            success: function (response) {

                console.log('Server response:', response);

                $tableBody.empty().append(response);

                console.log(
                    'Updated table rows:',
                    $tableBody.find('tr').length
                );

                console.log(
                    'Updated table HTML:',
                    $tableBody.html()
                );
            },

            error: function (xhr) {
                console.error('AJAX Error:', xhr.status, xhr.responseText);
            }
        });
    });



