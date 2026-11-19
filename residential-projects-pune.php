<?php

require_once 'dbc.php';
include'header.php';
include 'header_css.php'; ?>





<section class="financial-section pad80 top80">
    <div class="container">
        <span class="span">RESIDENTIAL PROJECTS IN PUNE</span>
        <h1 class="h4"> A fresh perspective on modern city living.</h1>
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
            
            <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
   <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">

                      <img src="images/residential-projects-bengaluru/aria.webp" alt="Ajmera Aria" loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">RESIDENTIAL</span>
                            <span class="ong">DELIVERED</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Aria</h5>

                    <div class="project-details">
                        <span>Koregaon Park, Pune</span>

                                                    <span>4 BHK</span>
                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-aria.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" data-bs-toggle="modal" data-bs-target="#staticBackdrop"  class="enquire-btn" data-project="Ajmera Aria">
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
            What should I consider when choosing a residential property in Pune?
        </button>
    </h2>
    <div id="faq1" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When choosing a residential property in Pune, consider its location, connectivity, proximity to workplaces, educational institutions, healthcare facilities and everyday conveniences. Also evaluate the project's amenities, construction quality, developer reputation and possession timeline.
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
            What are the benefits of investing in residential projects in Pune?
        </button>
    </h2>
    <div id="faq2" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Pune's established IT and industrial hubs, educational institutions and growing infrastructure make it an important residential market. The city offers housing options across neighbourhoods, catering to different budgets, space requirements and lifestyle preferences.
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
            How can I find new residential projects in Pune?
        </button>
    </h2>
    <div id="faq3" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Identify your preferred locations and compare new residential projects in Pune based on their configurations, pricing, amenities, connectivity and possession schedules. Check the project's RERA registration and review the developer's track record before making a decision.
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
            What should I check before buying ready-to-move-in flats in Pune?
        </button>
    </h2>
    <div id="faq4" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Inspect the apartment's construction quality, layout, fittings and amenities. Verify the occupancy certificate, title documents, RERA details where applicable, maintenance charges and outstanding dues. Visiting the property also allows you to assess the neighbourhood and its connectivity.
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
            How do I evaluate upcoming residential projects in Pune?
        </button>
    </h2>
    <div id="faq5" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When evaluating upcoming residential projects in Pune, look at the developer's credentials, project approvals, RERA registration, proposed configurations and construction schedule. Assess the location's existing infrastructure and planned connectivity rather than relying solely on future development promises.
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
            How can I identify the best residential projects in Pune?
        </button>
    </h2>
    <div id="faq6" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                The best residential projects in Pune are those that align with your budget, location preferences and living requirements. Compare available amenities, apartment layouts, construction standards, connectivity and possession timelines. Whether you are considering a residential apartment in Pune or a larger residential property, thorough research can help you make an informed choice.
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