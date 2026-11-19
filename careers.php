<?php

include 'header.php';
require_once 'dbc.php';

$result = $conn->query("
    SELECT *
    FROM career_jobs
    WHERE status = 1
    ORDER BY id DESC
");

?>

<div class="banner position-relative">
    <div class="banner-inner position-relative" style="background:url('images/career.webp') no-repeat">
        <div class="overlay2"></div>
    </div>

    <div class="caption d-flex align-items-center">
        <div class="container position-relative">
            <div class="m-0 ">
                <h1 class="wow animate__fadeInUp animate__animated m-0 ">
                     Careers
                </h1>
                <p class="wow animate__fadeInUp animate__animated">
                   Work with people who build with purpose, precision and pride.
                </p>
               
                
            </div>
        </div>
    </div>
</div>

<section class="news-section pad80 about2-head">
    <div class="container">
         <span class="span wow animate__animated animate__fadeInUp">COME, GROW WITH US </span>
        <div class="row ">
            <div class="col-lg-5">
                <h2 class="h4 wow animate__animated animate__fadeInUp">You can have the career of your dreams</h2>
            </div>
            
            <div class="col-lg-7">
                <p class="p wow animate__animated animate__fadeInUp mt-3">Ajmera is constantly looking for new talent and seasoned professionals. Becoming a member of the Ajmera Group means being a part of India’s most prestigious real estate company. We seek excellent individuals who are prepared to advance their careers at the forefront of the industry as we continue to widen our reach.</p>

                 <div class="wow animate__animated animate__fadeInUp">
                     <a href="#apply" class="text-decoration-none">
                    <button class="discover-btn" type="button">
                        <span>Career Opportunity</span>
                    </button>
                </a>
                 </div>
                </div>
         </div>        

    </div>
</section>

<section class="about-section grey pad80  values-wrapper">
    <div class="container">
        <span class="span wow animate__animated animate__fadeInUp"> EXPLORE THE OPPORTUNITIES </span>
        <div class="row">
            <div class="col-lg-5">
                <h2 class="about-title h4 wow animate__animated animate__fadeInUp">
                  Why join Ajmera?
                </h2>
            </div>
            <div class="col-lg-7">
                <div class="about-description ">

                    <p class="p wow animate__animated animate__fadeInUp">
                       Ajmera offers tools and opportunities for success, recognizing performance and facilitating growth. Our professional culture prioritizes caring, sharing, and rewarding, fostering diversity and inclusivity.
                    </p>

                   

                </div>
            </div>
        </div>
        
    <div class="values_tabs">
        


    <div class="people-image">
        <!--<img src="images/people-development.webp"  id="peopleMainImage" alt="People Development">-->
        
       <!-- Images -->
<img src="images/culture.webp" id="culture" class="w-100 d-none" alt="Culture">

<img src="images/people-development.webp" id="people-development" class="w-100 d-block" alt="People Development">

<img src="images/rewards-and-recognisition.webp" id="rewards-recognitions" class="w-100 d-none" alt="Rewards & Recognitions">

<img src="images/incentives.webp" id="Great-incentives" class="w-100 d-none" alt="Great Incentives">


<!-- Tabs -->
<div class="people-tabs">

    <button class="people-tab" data-index="0">
        <h4>Culture</h4>
        <p>Ajmera Group values equality, excellence, and diversity in its workplace culture, ensuring top-notch customer service while adhering to all legal compliances.</p>
    </button>

    <button class="people-tab active" data-index="1">
        <h4>People Development</h4>
        <p>Ajmera Group provides a supportive environment for employees to reach their full potential, offering extensive training, career advancement opportunities, and opportunities to migrate across various verticals.</p>
    </button>

    <button class="people-tab" data-index="2">
        <h4>Rewards &amp; Recognitions</h4>
        <p>Ajmera acknowledges the dedication of its exceptional staff through Prizes and Recognitions programme, offering monetary and non-monetary rewards, competitive wages, and comprehensive benefits.</p>
    </button>

    <button class="people-tab" data-index="3">
        <h4>Great Incentives</h4>
        <p>At Ajmera, we emphasize abilities and provide various remuneration, incentives, and perks, including but not restricted to competitive salaries.</p>
    </button>

</div>


    </div>


    </div>    
    
 </div>
</section>


<section class="works-section pad80  position-relative">
    <div class="container">
         <span class="span wow animate__animated animate__fadeInUp">
                            SCOUTING THE BEST TALENT
                        </span>
       <div class="row">
                <!-- Left Column -->
                <div class="col-lg-7">
                    <div class="h-100 position-relative ">
                        <h2 class="about-title h4 wow animate__animated animate__fadeInUp">
                           Our recruitment process
                        </h2>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-5">
                    <div class="">
                        <p class="p mt-3 wow animate__animated animate__fadeInUp">Three phases comprise our recruitment process, and each is carefully followed to ensure that the greatest talent in the field is hired.</p>
                    </div>
                </div>

            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                   <img src="images/our-recruitment-process.webp" class="w-100">
                </div>
                <div class="col-md-6">
                    <div class="accordion  impact-bootstrap-accordion h-auto overflow-visible bg-transparent"  id="impactAccordion">
    
                        <!-- Education -->
                        <div class="accordion-item mb-1">
    
                            <h2 class="accordion-header">
                                <button class="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#impactEducation"
                                        aria-expanded="true"
                                        aria-controls="impactEducation">
                                    Screening Process
    
                                </button>
                            </h2>
    
                            <div id="impactEducation"
                                 class="accordion-collapse collapse show"
                                 data-bs-parent="#impactAccordion">
    
                                <div class="accordion-body">
                                    <p class="mb-0">We evaluate your calibre in light of the necessary work skills concerning your education and abilities to meet the demand.
                                    </p>
    
                                </div>
    
                            </div>
                        </div>
    
                        <!-- Healthcare -->
                        <div class="accordion-item mb-1">
    
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#impactHealthcare">
                                  Interviews & Assessment
                                </button>
                            </h2>
    
                            <div id="impactHealthcare"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#impactAccordion">
    
                                <div class="accordion-body">
                                    <p class="mb-0">
                                      Following the screening, you are invited to an interview phase where you are evaluated on several competencies necessary for the job.
                                    </p>
    
                                    <!--<a href="#" class="impact-know-more">
                                        Know More <span>›</span>
                                    </a>-->
                                </div>
    
                            </div>
                        </div>
    
                        <!-- Flood Relief -->
                        <div class="accordion-item mb-1">
    
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#impactFlood">
                                   Offer & Compensation
                                </button>
                            </h2>
    
                            <div id="impactFlood"
                                 class="accordion-collapse collapse"
                                 data-bs-parent="#impactAccordion">
    
                                <div class="accordion-body">
                                    <p class="mb-0">
                                      We extend the offer letter and further discuss the salary and other compensation if we discover that your skills meet our needs.
                                    </p>
    
                                    <!--<a href="#" class="impact-know-more">
                                        Know More <span>›</span>
                                    </a>-->
                                </div>
    
                            </div>
                        </div>
    
                       
    
                    </div>  
                </div>
            </div>
 </div>
</section>



<section class="job-section pad80 grey position-relative" id="apply">
    <div class="container">
        
         <span class="span wow animate__animated animate__fadeInUp">OPPORTUNITIES</span>
    
        <h2 class="about-title h4 wow animate__animated animate__fadeInUp">Craft your career with us</h2>
                        
                       
                        <div class="accordion impact-bootstrap-accordion h-auto overflow-visible" id="impactAccordion">

                  
                    <div class="accordion" id="careerAccordion">

    <?php if ($result && $result->num_rows > 0): ?>

        <?php $count = 0; ?>

        <?php while ($job = $result->fetch_assoc()): ?>

            <?php
                $count++;

                $collapseId = 'careerJob' . (int)$job['id'];

                /*
                 * Job Description:
                 * TinyMCE se HTML aa raha hai, isliye
                 * htmlspecialchars() use nahi karna hai.
                 */
                $jobDescription = trim(
                    $job['job_description'] ?? ''
                );

                /*
                 * Old data compatibility
                 */
                $responsibilities = preg_split(
                    "/\r\n|\n|\r/",
                    $job['responsibilities'] ?? ''
                );

                $qualifications = preg_split(
                    "/\r\n|\n|\r/",
                    $job['qualifications'] ?? ''
                );
            ?>


            <div class="accordion-item">

                <!-- =========================
                     JOB TITLE
                ========================== -->

                <h2 class="accordion-header">

                    <button
                        class="accordion-button <?php echo $count > 1 ? 'collapsed' : ''; ?>"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?php echo $collapseId; ?>"
                        aria-expanded="<?php echo $count === 1 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo $collapseId; ?>"
                    >

                        <?php
                        echo htmlspecialchars(
                            $job['job_title'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>

                    </button>

                </h2>


                <!-- =========================
                     ACCORDION CONTENT
                ========================== -->

                <div
                    id="<?php echo $collapseId; ?>"
                    class="accordion-collapse collapse <?php echo $count === 1 ? 'show' : ''; ?>"
                    data-bs-parent="#careerAccordion"
                >

                    <div class="accordion-body carrer-job">


                        <!-- =========================
                             JOB DESCRIPTION
                        ========================== -->

                        <?php if ($jobDescription !== ''): ?>

                            <h4 class="job_d">
                                Job Description:
                            </h4>

                            <div class="job-description-content">

                                <?php

                                /*
                                 * Agar TinyMCE HTML hai to direct render.
                                 *
                                 * Agar plain text hai to safely
                                 * htmlspecialchars + nl2br.
                                 */

                                if (
                                    $jobDescription !==
                                    strip_tags($jobDescription)
                                ) {

                                    echo $jobDescription;

                                } else {

                                    echo nl2br(
                                        htmlspecialchars(
                                            $jobDescription,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )
                                    );

                                }

                                ?>

                            </div>

                        <?php endif; ?>


                        <!-- =========================
                             OLD RESPONSIBILITIES
                             Only show if existing
                             old data available
                        ========================== -->

                        <?php if (
                            !empty($job['responsibilities'])
                        ): ?>

                            <h3>
                                Key Responsibilities:
                            </h3>

                            <ul>

                                <?php foreach (
                                    $responsibilities
                                    as $responsibility
                                ): ?>

                                    <?php
                                    $responsibility =
                                        trim($responsibility);
                                    ?>

                                    <?php if (
                                        $responsibility !== ''
                                    ): ?>

                                        <li>
                                            <?php
                                            echo htmlspecialchars(
                                                $responsibility,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </li>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </ul>

                        <?php endif; ?>


                        <!-- =========================
                             OLD QUALIFICATIONS
                             Only show if existing
                             old data available
                        ========================== -->

                        <?php if (
                            !empty($job['qualifications'])
                        ): ?>

                            <h3>
                                Qualifications:
                            </h3>

                            <ul>

                                <?php foreach (
                                    $qualifications
                                    as $qualification
                                ): ?>

                                    <?php
                                    $qualification =
                                        trim($qualification);
                                    ?>

                                    <?php if (
                                        $qualification !== ''
                                    ): ?>

                                        <li>
                                            <?php
                                            echo htmlspecialchars(
                                                $qualification,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </li>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </ul>

                        <?php endif; ?>


                        <!-- =========================
                             POSITION
                        ========================== -->

                        <?php if (
                            !empty($job['position'])
                        ): ?>

                            <h3>
                                Position:
                            </h3>

                            <ul>

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $job['position'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </li>

                            </ul>

                        <?php endif; ?>


                        <!-- =========================
                             LOCATION
                        ========================== -->

                        <?php if (
                            !empty($job['location'])
                        ): ?>

                            <h3>
                                Location:
                            </h3>

                            <ul>

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $job['location'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </li>

                            </ul>

                        <?php endif; ?>


                        <!-- =========================
                             EXPERIENCE
                        ========================== -->

                        <?php if (
                            !empty($job['experience'])
                        ): ?>

                            <h3>
                                Experience:
                            </h3>

                            <ul>

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $job['experience'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </li>

                            </ul>

                        <?php endif; ?>


                        <!-- =========================
                             APPLY BUTTON
                        ========================== -->

                        <div class="wow animate__animated animate__fadeInUp">

                            <div class="text-decoration-none">

                                <a
                                    href="apply.php?job_id=<?php echo (int)$job['id']; ?>"
                                    class="discover-btn text-decoration-none"
                                >

                                    <span>
                                        Apply Now
                                    </span>

                                </a>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="no-jobs">

            <p>
                No current job openings available.
            </p>

        </div>

    <?php endif; ?>

</div>
                </div>

    </div>
</section>



<section class="enquire-now-section pad80 overflow-hidden">
    <div class="container">

                <!-- Headings -->
                <span class="span wow animate__animated animate__fadeInUp">Apply  NOW</span>
                <h2 class="h4 mb-4 wow animate__animated animate__fadeInUp"> We’ll be in touch soon!</h2>
                
                 <form id="careerForm"
      method="POST"
      action="career_form_backend.php"
      class="custom-enquire-form wow animate__animated animate__fadeInUp"
      enctype="multipart/form-data">

    <div class="row gx-4 gy-5">

        <!-- First Name -->
        <div class="col-md-6">
            <input type="text"
                   name="first_name"
                   id="first_name"
                   class="form-control custom-input"
                   placeholder="First Name">
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control custom-input"
                   placeholder="Email">
        </div>


        <!-- Department -->
        <div class="col-md-6">

            <select name="department"
                    id="department"
                    class="form-control custom-input custom-select">

                <option value="">Select Department</option>

                <option value="Human Resource">
                    Human Resource
                </option>

                <option value="Billing & Estimation">
                    Billing & Estimation
                </option>

                <option value="Quality">
                    Quality
                </option>

                <option value="Marketing & Communications">
                    Marketing & Communications
                </option>

                <option value="Architect & Planning">
                    Architect & Planning
                </option>

                <option value="Sales & CRM">
                    Sales & CRM
                </option>

                <option value="Administration & Stores">
                    Administration & Stores
                </option>

                <option value="Security & Safety">
                    Security & Safety
                </option>

                <option value="Company Secretary">
                    Company Secretary
                </option>

                <option value="Technical">
                    Technical
                </option>

                <option value="Purchase">
                    Purchase
                </option>

                <option value="Electrical">
                    Electrical
                </option>

                <option value="Accounts & Finance">
                    Accounts & Finance
                </option>

                <option value="Tele Sales Executive">
                    Tele Sales Executive
                </option>

            </select>

        </div>


        <!-- Experience -->
        <div class="col-md-6">

            <select name="experience"
                    id="experience"
                    class="form-control custom-input custom-select">

                <option value="">Select Experience</option>

                <option value="0-1 years">0-1 years</option>
                <option value="2-3 years">2-3 years</option>
                <option value="4-5 years">4-5 years</option>
                <option value="6-7 years">6-7 years</option>
                <option value="8-9 years">8-9 years</option>
                <option value="10 or more years">
                    10 or more years
                </option>

            </select>

        </div>


        <!-- Location -->
        <div class="col-md-6">

            <select name="location"
                    id="location"
                    class="form-control custom-input custom-select">

                <option value="">Select Location</option>

                <option value="Mumbai">Mumbai</option>
                <option value="Bangalore">Bangalore</option>
                <option value="Pune">Pune</option>
                <option value="Ahmedabad">Ahmedabad</option>

            </select>

        </div>


        <!-- Phone -->
        <div class="col-md-6">

            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-control custom-input"
                   placeholder="Phone Number"
                   maxlength="10">

        </div>


        <!-- Message -->
        <div class="col-12 mt-5">

            <textarea name="message"
                      id="message"
                      class="form-control custom-input"
                      placeholder="Message"
                      rows="4"></textarea>

        </div>


        <!-- Resume -->
        <div class="col-md-12">

    <div class="formupload">

        <label for="file"
               class="btn btn-dark position-relative overflow-hidden border-0">

            Upload Resume

            <input type="file"
                   id="file"
                   name="resume"
                   class="type-hide position-absolute top-0 start-0 w-100 h-100 opacity-0"
                  >

        </label>

        <span class="file-name ms-2">
            No file chosen
        </span>

        <div id="resume_error"></div>

    </div>

</div>


        <!-- Submit -->
        <div class="col-12 mt-5">

            <button class="discover-btn"
                    type="submit">

                <span>Submit</span>

            </button>

        </div>

    </div>

</form>
               
            
    </div>
</section>


<?php include'footer.php' ?>


<script>
    
document.addEventListener("DOMContentLoaded", function () {

    const peopleTabs = document.querySelectorAll(".people-tab");

    const peopleImages = [
        document.getElementById("culture"),
        document.getElementById("people-development"),
        document.getElementById("rewards-recognitions"),
        document.getElementById("Great-incentives")
    ];

    peopleTabs.forEach((tab, index) => {
        tab.addEventListener("click", function () {

            // Remove active class from all tabs
            peopleTabs.forEach(item => item.classList.remove("active"));

            // Add active class to clicked tab
            this.classList.add("active");

            // Hide all images
            peopleImages.forEach(img => {
                img.classList.remove("d-block");
                img.classList.add("d-none");
            });

            // Show selected image
            peopleImages[index].classList.remove("d-none");
            peopleImages[index].classList.add("d-block");

        });
    });

});



$("#careerForm").validate({

    rules: {

        first_name: {
            required: true
        },

        email: {
            required: true,
            email: true
        },

        department: {
            required: true
        },

        experience: {
            required: true
        },

        location: {
            required: true
        },

        phone: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
        },

        message: {
            required: true
        },

        resume: {
            required: true,
            resumeFile: true
        }

    },

    messages: {

        first_name: "Please enter your name",

        email: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
        },

        department: "Please select a department",

        experience: "Please select your experience",

        location: "Please select your preferred location",

        phone: {
            required: "Please enter your phone number",
            digits: "Please enter numbers only",
            minlength: "Phone number must be 10 digits",
            maxlength: "Phone number must be 10 digits"
        },

        message: "Please enter your message",

        resume: {
            required: "Please upload your resume",
            resumeFile: "Only PDF, DOC or DOCX files up to 5 MB are allowed"
        }

    },

    errorElement: "span",

    errorClass: "form-error",

    errorPlacement: function (error, element) {

        if (element.attr("name") === "resume") {

            error.appendTo("#resume_error");

        } else {

            error.insertAfter(element);

        }

    },

    highlight: function (element) {
        $(element).addClass("input-error");
    },

    unhighlight: function (element) {
        $(element).removeClass("input-error");
    },

    submitHandler: function (form) {

        var formData = new FormData(form);

        $.ajax({

            url: "career_form_backend.php",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                response = $.trim(response);

                console.log(response);

                if (response === "success") {

                    window.location.href = "thankyou.php";

                } else {

                    alert(response);

                }

            },

            error: function (xhr) {

                console.log(xhr.responseText);

                alert("Unable to submit application.");

            }

        });

        return false;
    }

});
</script>