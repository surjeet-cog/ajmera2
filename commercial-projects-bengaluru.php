<?php

require_once 'dbc.php';
include'header.php';
include 'header_css.php'; ?>





<section class="financial-section pad80 top80">
    <div class="container">
        <span class="span">COMMERCIAL PROJECTS IN BENGALURU</span>
        <h1 class="h4"> Workspaces for a city on the move.</h1>
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
<img src="images/residential-projects-bengaluru/ajmera-nucleus.webp" alt="Ajmera Nucleus " loading="lazy">
                        
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

                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-nucleus-commercial.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a data-bs-toggle="modal" data-bs-target="#staticBackdrop"  href="#enquire" class="enquire-btn" data-project="Ajmera Nucleus ">
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
            What should I consider when choosing a commercial property in Bengaluru?
        </button>
    </h2>
    <div id="faq1" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When selecting a commercial property in Bengaluru, consider its location, connectivity, visibility, surrounding business ecosystem, infrastructure and accessibility. Also evaluate whether the property aligns with your business requirements or investment objectives.
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
            Why is Bengaluru a preferred destination for commercial real estate investment?
        </button>
    </h2>
    <div id="faq2" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Bengaluru has a strong IT and technology ecosystem, established business districts and a growing network of commercial hubs. Its diverse business environment creates demand for office spaces, retail developments and other commercial properties.
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
            What types of commercial properties are available in Bengaluru?
        </button>
    </h2>
    <div id="faq3" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                The city offers office spaces, retail units, showrooms, commercial buildings and commercial land. Buyers can explore options based on their intended use, preferred location, space requirements and budget.
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
            What should I check before purchasing commercial property for sale in Bengaluru?
        </button>
    </h2>
    <div id="faq4" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Before purchasing, verify the property's title, approvals, permitted usage, RERA registration where applicable, carpet area, payment terms and maintenance charges. It is also important to assess parking, accessibility and the surrounding infrastructure.
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
            How can I choose the right commercial space for sale in Bengaluru?
        </button>
    </h2>
    <div id="faq5" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Define your business requirements, budget and preferred location before comparing available properties. Evaluate factors such as road connectivity, visibility, customer access, nearby businesses and future infrastructure. A site visit can help you assess whether the commercial space meets your operational needs.
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
            What is the process to buy commercial property in Bengaluru?
        </button>
    </h2>
    <div id="faq6" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                To buy commercial property in Bengaluru, shortlist suitable developments, compare specifications and pricing, and conduct thorough legal due diligence. Whether you plan to buy commercial space in Bengaluru, invest in a commercial building or consider commercial land for sale, verify the documentation, permitted usage, payment schedule and registration requirements before proceeding.
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