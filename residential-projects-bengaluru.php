<?php

require_once 'dbc.php';
include'header.php';
include 'header_css.php'; ?>





<section class="financial-section pad80 top80">
    <div class="container">
        <span class="span">RESIDENTIAL PROJECTS IN BENGALURU</span>
        <h1 class="h4"> Homes shaped around the city’s evolving spirit.</h1>
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
                <img src="images/residential-projects-bengaluru/ajmera-iris.webp" alt="ajmera-iris">
                <div class="project-badges">
                    <span class="res">residential</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Iris</h5>
            <div class="project-details">
                <span> Chikkanagamangala </span>
                <span>2 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-iris.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="enquire-btn">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">
                    <img src="images/residential-projects-bengaluru/ajmera-lakeside-paradise.webp" alt="Ajmera Lakeside Paradise" loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">RESIDENTIAL</span>
                            <span class="ong">ONGOING</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Lakeside Paradise</h5>

                    <div class="project-details">
                        <span>Yelahanka New Town</span>

                                                    <span>2, 3 BHK</span>
                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-lakeside-paradise.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="enquire-btn" data-project="Ajmera Lakeside Paradise">
                        Enquire Now <b>›</b>
                    </a>
                </div>

            </div>
</div>

             <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">
                        <img src="images/residential-projects-bengaluru/Ajmera-Marina.webp" alt="Ajmera Marina" loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">RESIDENTIAL</span>
                            <span class="ong">ONGOING</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Marina</h5>

                    <div class="project-details">
                        <span>Kempanahalli</span>

                                                    <span>2, 3 BHK</span>
                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-marina.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="enquire-btn" data-project="Ajmera Marina">
                        Enquire Now <b>›</b>
                    </a>
                </div>

            </div>
</div>
             
             
             <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
   <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">
<img src="images/commercial-projects-mumbai/ajmera-annex.webp" alt="Ajmera Annex" loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">RESIDENTIAL</span>
                            <span class="ong">DELIVERED</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Annex</h5>

                    <div class="project-details">
                        <span>Electronic City, Bengaluru</span>

                                                    <span>2 &amp; 3 BHK</span>
                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-annex.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="enquire-btn" data-project="Ajmera Annex">
                        Enquire Now <b>›</b>
                    </a>
                </div>

            </div>
</div>                                       

 <div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
  <div class="project-card">

                <div class="project-image">
                    <div class="overflow-hidden position-relative">

                       <img src="images/commercial-projects-mumbai/Ajmera-Nucleus.webp" alt="Ajmera Nucleus" loading="lazy">
                        
                        <div class="project-badges">
                            <span class="res">RESIDENTIAL</span>
                            <span class="ong">DELIVERED</span>
                        </div>

                    </div>
                </div>

                <div class="project-info">
                    <h5>Ajmera Nucleus</h5>

                    <div class="project-details">
                        <span>Bengaluru</span>

                                                    <span> 2 &amp; 3 BHK</span>
                                            </div>
                </div>

                <div class="project-actions d-flex">
                    <a href="projects/ajmera-nucleus-residential.php" class="explore-btn">
                        Explore Project <b>›</b>
                    </a>

                    <a href="#enquire" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="enquire-btn" data-project="Ajmera Nucleus">
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
            What should I consider when choosing a residential property in Bengaluru?
        </button>
    </h2>
    <div id="faq1" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When choosing a residential property in Bengaluru, consider the location, connectivity, proximity to workplaces, social infrastructure, amenities and developer track record. Your budget, preferred configuration and long-term requirements should also influence your decision.
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
            Why should I consider investing in residential projects in Bengaluru?
        </button>
    </h2>
    <div id="faq2" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Bengaluru's strong IT and business ecosystem, expanding infrastructure and established educational and healthcare facilities make it an important residential market. Residential projects in Bengaluru offer options across different neighbourhoods, configurations and budgets.
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
            What types of flats for sale are available in Bengaluru?
        </button>
    </h2>
    <div id="faq3" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                The city offers a variety of residential options, including 1, 2, 3 and 4 BHK flats, apartments and premium residences. The right choice depends on your space requirements, family size, preferred location and budget.
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
            How can I find the right apartment for sale in Bengaluru?
        </button>
    </h2>
    <div id="faq4" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Start by shortlisting neighbourhoods based on your daily commute, budget and lifestyle requirements. Compare apartments in Bengaluru based on carpet area, amenities, connectivity, construction quality and possession timelines. Visiting shortlisted properties can help you make a more informed choice.
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
            What should I verify before buying an apartment in Bengaluru?
        </button>
    </h2>
    <div id="faq5" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Before you buy an apartment in Bengaluru, check the project's RERA registration, land title, statutory approvals, carpet area, payment schedule and possession date. You should also review the sale agreement and understand any additional charges.
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
            What is the process to buy a flat or apartment in Bengaluru?
        </button>
    </h2>
    <div id="faq6" class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                To buy a flat in Bengaluru, identify suitable residential developments, compare their specifications and pricing, visit the property and verify the relevant documents. Once you finalise a Bengaluru apartment, review the sale agreement, complete the required payments and proceed with registration. Whether you are considering apartments for sale in Bengaluru or exploring options across the city, thorough due diligence is essential.
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