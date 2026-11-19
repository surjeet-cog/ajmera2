<?php

require_once 'dbc.php';
include'header.php'; 
include 'header_css.php'; ?>




<section class="financial-section pad80 top80">
    <div class="container">
        <span class="span">RESIDENTIAL PROJECTS IN AHMEDABAD</span>
        <h1 class="h4">Homes that bring comfort closer to everyday life.</h1>
    </div>
</section>


<!-- Projects Section -->
<section class="projects-section pad80 pt-0">
    <div class="container">
<div class="animtes-list">
        <!-- Filter Tabs -->
        <ul class="nav project-tabs  nav-tabs border-0 mb-5" id="projectTabs">
            <li class="nav-item">
                <button class="project-tab nav-link active" data-filter="all" type="button">
                    All
                </button>
            </li>
            <li class="nav-item">
                <button class="project-tab nav-link" data-filter="upcoming" type="button">
                    Upcoming
                </button>
            </li>
            <li class="nav-item">
                <button class="project-tab nav-link"  data-filter="ongoing" type="button">
                    Ongoing
                </button>
            </li>
            <li class="nav-item">
                <button class="project-tab nav-link" data-filter="delivered" type="button">
                    Delivered
                </button>
            </li>
        </ul>

        <!-- Projects -->
        <div class="row g-3" id="projectsGrid">
            
            <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
    <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">
<img src="images/residential-projects-bengaluru/Casa-Vyoma.webp" alt="Casa Vyoma " loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">COMMERCIAL</span>
                            <span class="ong">DELIVERED</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Nucleus </h5>

                    <div class="project-details">
                        <span>Bengaluru</span>
                         <span>2, 3 & 4 BHK</span>
             </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-nucleus-commercial.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" class="enquire-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop"  data-project="Ajmera Nucleus ">
                        Enquire Now <b>›</b>
                    </a>
                </div>

            </div>
</div>
           
        </div>
        
    </div>    
    </div>
</section>


<section class="faqs-section faqs-section-project border1   pad80">
    <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <span class="span wow animate__animated animate__fadeInUp">FAQs </span>
                <h2 class="h4 mb-4 wow animate__animated animate__fadeInUp">Still have questions? Let’s help you find the answers.</h2>
             </div>
             <div class="col-md-12">
                 <div class="accordion mt-4 impact-bootstrap-accordion h-auto overflow-visible"  id="impactAccordion">

                    


<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq1"
                aria-expanded="false"
                aria-controls="faq1">
            What should I consider when choosing a residential property in Ahmedabad?
        </button>
    </h2>
    <div id="faq1" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When choosing a residential property in Ahmedabad, consider its location, connectivity, neighbourhood infrastructure, amenities, construction quality and developer reputation. Your budget, preferred configuration and long-term needs should also guide your decision.
            </p>
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq2"
                aria-expanded="false"
                aria-controls="faq2">
            What makes Ahmedabad a good city for residential investment?
        </button>
    </h2>
    <div id="faq2" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Ahmedabad offers a growing business ecosystem, improving infrastructure and access to established educational, healthcare and lifestyle destinations. These factors make the city an important choice for homebuyers and those considering long-term residential investments.
            </p>
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq3"
                aria-expanded="false"
                aria-controls="faq3">
            How can I find a new apartment in Ahmedabad?
        </button>
    </h2>
    <div id="faq3" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Begin by identifying preferred neighbourhoods and comparing new apartments in Ahmedabad based on their location, configurations, amenities, pricing and possession timelines. Check the project's RERA registration and visit the property to assess its surroundings and specifications.
            </p>
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq4"
                aria-expanded="false"
                aria-controls="faq4">
            What should I look for when choosing the best residential property in Ahmedabad?
        </button>
    </h2>
    <div id="faq4" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                The best residential property depends on your individual requirements. Evaluate the quality of construction, layout, available amenities, connectivity, neighbourhood facilities and the developer's track record. Also consider whether the property suits your current needs and future plans.
            </p>
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq5"
                aria-expanded="false"
                aria-controls="faq5">
            What types of residential properties are available in Ahmedabad?
        </button>
    </h2>
    <div id="faq5" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Ahmedabad offers a range of residential options, from compact apartments to spacious family homes and premium residences. Buyers can compare configurations, layouts and amenities across different residential projects to find a home that suits their budget and lifestyle.
            </p>
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#faq6"
                aria-expanded="false"
                aria-controls="faq6">
            What is the process of buying a residential property in Ahmedabad?
        </button>
    </h2>
    <div id="faq6" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Start by shortlisting residential projects in Ahmedabad that match your budget and location preferences. Compare specifications, visit the development and verify its RERA registration, title documents and approvals. Before completing the purchase, review the sale agreement, payment schedule, possession terms and applicable charges.
            </p>
        </div>
    </div>
</div>


                </div>
             </div>
         </div>        
    </div>
</section>

<?php include'footer.php' ?>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".project-tab");
    const projects = document.querySelectorAll(".project-item");

    tabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
            const filter = this.dataset.filter;

            tabs.forEach(function (item) {
                item.classList.remove("active");
            });

            this.classList.add("active");

            projects.forEach(function (project) {
                const status = project.dataset.status;

                project.hidden = !(filter === "all" || status === filter);
            });
        });
    });
});
</script>