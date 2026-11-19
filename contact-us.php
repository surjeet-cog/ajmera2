<?php include'header.php' ?>

<div class="banner position-relative">
    <div class="banner-inner position-relative" style="background:url('images/contact.webp') no-repeat">
        <div class="overlay"></div>
    </div>

    <div class="caption d-flex align-items-center">
        <div class="container position-relative">
            <div class="m-0 ">
                <h1 class="wow animate__fadeInUp animate__animated m-0 ">
                     Contact Us
                </h1>
                <p class="wow animate__fadeInUp animate__animated">
                    We’re here to answer your questions, understand your needs, and help you find the right space for what comes next.
                </p>
               
                
            </div>
        </div>
    </div>
</div>


<section class="enquire-now-section pad80 overflow-hidden">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- LEFT COLUMN: Form & Text -->
            <div class="col-md-7 pe-md-5">
                <!-- Headings -->
                <span class="span wow animate__animated animate__fadeInUp">ENQUIRE NOW</span>
                <h2 class="h4 mb-4 wow animate__animated animate__fadeInUp">Find your new address.</h2>
                
                <div class="mb-4 wow animate__animated animate__fadeInUp">
                    <p class="enquire-subtext-bold">Interested in this project?</p>
                    <p class="enquire-subtext">Share your details and our property advisor will get in touch with you.</p>
                </div>
                
               
<form id="customEnquireForm" class="custom-enquire-form wow animate__animated animate__fadeInUp">

    <div class="row gx-4 gy-5">

        <div class="col-md-6">
            <input type="text" name="first_name" id="first_name"
                class="form-control custom-input"
                placeholder="First Name" required>
        </div>

        <div class="col-md-6">
            <input type="text" name="last_name" id="last_name"
                class="form-control custom-input"
                placeholder="Last Name" required>
        </div>

        <div class="col-md-6">
            <input type="email" name="email" id="email"
                class="form-control custom-input"
                placeholder="Email" required>
        </div>

        <div class="col-md-6">
            <input type="tel" name="phone" id="phone"
                class="form-control custom-input"
                placeholder="Phone Number"
                maxlength="10"
                pattern="[0-9]{10}" required>
        </div>

        <div class="col-12 mt-5">
            <textarea name="message" id="message"
                class="form-control custom-input"
                placeholder="Message" rows="4" required></textarea>
        </div>

        <div class="col-12 mt-5">
            <button class="discover-btn" type="submit">
                <span>Discover More</span>
            </button>
        </div>

    </div>
</form>

<div id="formMessage"></div>

               
            </div>

            <!-- RIGHT COLUMN: Image -->
            <div class="col-md-5 ps-md-5 pt-md-5 h-100 wow animate__animated animate__fadeInRight d-none d-md-block">
                <div class="enquire-image-wrapper ">
                  
                    <img src="images/contact-form.webp" alt="Find your new address" />
                </div>
            </div>

        </div>
    </div>
</section>









<div class="google_map position-relative pad80 pt-0">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3769.292204018269!2d72.8314231!3d19.138682499999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b63cd099ad09%3A0x4eb3497426278a69!2sCiti%20Mall%2C%204%2C%20Oshiwara%20Link%20Rd%2C%20Phase%20D%2C%20Shastri%20Nagar%2C%20Andheri%20West%2C%20Mumbai%2C%20Maharashtra%20400053!5e0!3m2!1sen!2sin!4v1790686329779!5m2!1sen!2sin"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    
    <div class="container">
    <div class="office-location-box">

    <!-- COUNTRYWIDE OFFICES -->
    <span class="span">OUR LOCATIONS</span>

    <h2>Countrywide offices</h2>

    <div class="location-divider"></div>

    <div class="accordion location-accordion" id="countrywideAccordion">

        <!-- Mumbai -->
        <div class="accordion-item">

            <h2 class="accordion-header">
                <button class="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#mumbai"
                        aria-expanded="true"
                        aria-controls="mumbai">
                    Mumbai
                </button>
            </h2>

            <div id="mumbai"
                 class="accordion-collapse collapse show"
                 data-bs-parent="#countrywideAccordion">

                <div class="accordion-body">

                    <div class="office-detail">
                        <span class="detail-icon"><img src="images/location.svg"></span>

                        <p> Head Office - Ajmera Head Office, 2nd Floor, City Mall,<br> New
Link Road. </p>
                    </div>

                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/tel.svg"></span>
                        <p><a href="tel:912266984000">+91 22 66984000</a></p>
                    </div>

                    <div class="office-detail">
                        <span class="detail-icon"><img src="images/mail.svg"></span>
                        <p><a href="mailto:info@ajmera.com">info@ajmera.com</a></p>
                    </div>

                </div>

            </div>

        </div>


        <!-- Pune -->
        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#pune"
                        aria-expanded="false"
                        aria-controls="pune">

                    Pune

                </button>

            </h2>

            <div id="pune"
                 class="accordion-collapse collapse"
                 data-bs-parent="#countrywideAccordion">
                
                <div class="accordion-body">
                    <div class="office-detail">
                        <span class="detail-icon"><img src="images/location.svg"></span>

                        <p>  Ajmera Housing Corporation Akshardham 12/A North Main Road, <br>Koregaon Park, Pune - 411001, India </p>
                            
                           
                    </div>

                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/tel.svg"></span>
                        <p><a href="tel:91 20 26121253">+91 20 26121253</a>,
                        <a href="tel:918055082082">+91 8055082082</a>
                        </p>
                    </div>

                 </div>   

            </div>

        </div>


        <!-- Bengaluru -->
        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#bengaluru"
                        aria-expanded="false">

                    Bengaluru

                </button>

            </h2>

            <div id="bengaluru"
                 class="accordion-collapse collapse"
                 data-bs-parent="#countrywideAccordion">

                <div class="accordion-body">

                   <div class="office-detail">
                        <span class="detail-icon"><img src="images/location.svg"></span>
                        <p> Ajmera Housing Corporation, Ground Floor, Ajmera Nucleus Commercial, Sy. No. 13/5, Next to Tech Mahindra, Doddanagamangala Main Road, Electronic City Phase 2, Bangalore 560100, Karnataka</p>
                    </div>

                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/tel.svg"></span>
                       
                         <p><a href="tel:91 80 40483000">+91 80 40483000</a>,
                        <a href="tel:8088887755">+91 8088887755</a>
                        </p>
                    </div>

                </div>

            </div>

        </div>


        <!-- Ahmedabad -->
        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ahmedabad"
                        aria-expanded="false">

                    Ahmedabad

                </button>

            </h2>

            <div id="ahmedabad"
                 class="accordion-collapse collapse"
                 data-bs-parent="#countrywideAccordion">

                <div class="accordion-body">

                    <div class="office-detail">
                        <span class="detail-icon"><img src="images/location.svg"></span>
                        <p>C-1 St Floor, Sahajanand Shopping Center, Opp Swaminarayan Temple, Shahibaug Road, Ahmedabad, India</p>
                    </div>

                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/tel.svg"></span>
                       
                        
                        <p><a href="tel:7925622883">+91 7925622883</a>,
                        <a href="tel:8000088048">+91 8000088048</a>
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- WORLDWIDE OFFICES -->

    <h2 class="worldwide-title">
        Worldwide offices
    </h2>

    <div class="location-divider"></div>

    <div class="accordion location-accordion"
         id="countrywideAccordion">

        <div class="accordion-item">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#uk"
                        aria-expanded="false">

                    United Kingdom

                </button>

            </h2>

            <div id="uk"
                 class="accordion-collapse collapse"
                 data-bs-parent="#countrywideAccordion">

                <div class="accordion-body">

                    <div class="office-detail">
                        <span class="detail-icon"><img src="images/location.svg"></span>
                        <p>Representative Office Registered In United Kingdom, Ajmera Realty Infra (UK) Ltd. 165 Chaplin Road, Wembley, Middlesex, London HAO 4UN, United Kingdom</p>
                    </div>

                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/tel.svg"></span>
                        <p><a href="tel:447773777371">+44 777 377 7371</a></p>
                    </div>
                    <div class="office-detail">
                       <span class="detail-icon"><img src="images/mail.svg"></span>
                        <p><a href="mailto:tilesh.p@ajmera.com">tilesh.p@ajmera.com</a></p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
</div>
</div>
     

<?php include'footer.php' ?>

<script>


$("#customEnquireForm").validate({

    rules: {

        first_name: {
            required: true
        },

        last_name: {
            required: true
        },

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

        message: {
            required: true
        }
    },

    messages: {

        first_name: {
            required: "Please enter your first name"
        },

        last_name: {
            required: "Please enter your last name"
        },

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

        message: {
            required: "Please enter your message"
        }
    },

    errorElement: "span",
    errorClass: "form-error",

    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },

    highlight: function (element) {
        $(element).addClass("input-error");
    },

    unhighlight: function (element) {
        $(element).removeClass("input-error");
    },

    submitHandler: function (form) {

        var $form = $(form);
        var $button = $form.find('button[type="submit"]');
        var originalText = $button.find('span').text();

        $button.prop('disabled', true);
        $button.find('span').text('Submitting...');

        $.ajax({

            url: 'contact_form_backend.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',

            success: function (response) {

                console.log("Backend Response:", response);

                if (response.status === 'success') {

                    window.location.href = 'thankyou.php';

                } else {

                    alert(response.message);

                    $button.prop('disabled', false);
                    $button.find('span').text(originalText);
                }
            },

            error: function (xhr, status, error) {

    console.log("HTTP Status:", xhr.status);
    console.log("AJAX Error:", error);
    console.log("Response:", xhr.responseText);

    var response = xhr.responseJSON;

    alert(
        response && response.message
        ? response.message
        : "Server Error: " + xhr.status
    );

    $button.prop('disabled', false);
    $button.find('span').text(originalText);
}
        });

        return false;
    }

});

</script>
