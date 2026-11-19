<?php

require_once 'dbc.php';
include'header.php';
include 'header_css.php';

?>



<section class="financial-section pad80 top80">
    <div class="container">
        <span class="span">RESIDENTIAL PROJECTS IN MUMBAI </span>
        <h1 class="h4"> Homes crafted for the spirit of Mumbai. </h1>
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
                <img src="images/residential-projects-mumbai/33Fifteen.webp" alt="33fifteen">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>33fifteen</h5>
            <div class="project-details">
                <span class="border-0">Bandra West, Mumbai</span>
                <!--<span>2 and 3 BHK</span>-->
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/33fifteen.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

           <!-- 1. Ajmera Manhattan -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/manhattan.webp" alt="Ajmera Manhattan">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Manhattan</h5>
            <div class="project-details">
                <span>Wadala (E), Mumbai</span>
                <span>2 and 3 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-manhattan.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 2. Ajmera Greenfinity -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/greenfinity.webp" alt="Ajmera Greenfinity">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">DELIVERED</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Greenfinity</h5>
            <div class="project-details">
                <span>Chembur, Mumbai</span>
                <span>2 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-greenfinity.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 3. Ajmera Prive -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/prive.webp" alt="Ajmera Prive">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">DELIVERED</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Prive</h5>
            <div class="project-details">
                <span>Juhu, Mumbai</span>
                <span>3 and 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-prive.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>



<!-- 5. Vann By Ajmera -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/vaan.webp" alt="Vann By Ajmera">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Vann By Ajmera</h5>
            <div class="project-details">
                <span>Andheri West, Mumbai</span>
                <span>4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/vann-by-ajmera.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 6. Ajmera Solis -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/solis.webp" alt="Ajmera Solis">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Solis</h5>
            <div class="project-details">
                <span>Vikhroli East, Mumbai</span>
                <span>1, 2 and 3 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-solis.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 7. Ajmera Shikhar -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/shikhar.webp" alt="Ajmera Shikhar">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Shikhar</h5>
            <div class="project-details">
                <span>Malad West, Mumbai</span>
                <span>1 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-shikhar.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 8. Ajmera Vihara -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/vihara.webp" alt="Ajmera Vihara">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Vihara</h5>
            <div class="project-details">
                <span>Wembley, Mumbai</span>
                <span>1 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-vihara.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 9. Ajmera Arham -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/arham.webp" alt="Ajmera Arham">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Arham</h5>
            <div class="project-details">
                <span>Malad West, Mumbai</span>
                <span>2, 3 and 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-arham.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 10. Ajmera i-Land Aeon -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/i-land-aeon.webp" alt="Ajmera i-Land Aeon">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">DELIVERED</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera i-Land Aeon</h5>
            <div class="project-details">
                <span>Wadala East, Mumbai</span>
                <span>2, 3 and 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-i-land-aeon.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 11. Ajmera i-Land Zeon -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/i-land-zeon.webp" alt="Ajmera i-Land Zeon">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">DELIVERED</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera i-Land Zeon</h5>
            <div class="project-details">
                <span>Wadala East, Mumbai</span>
                <span>2, 3 and 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-i-land-zeon.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>

<!-- 12. Ajmera i-Land Treon -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="delivered">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/i-land-treon.webp" alt="Ajmera i-Land Treon">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">DELIVERED</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera i-Land Treon</h5>
            <div class="project-details">
                <span>Wadala East, Mumbai</span>
                <span>2, 3 and 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-i-land-treon.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
        </div>
    </div>
</div>


<!-- 4. Ajmera Eden -->
<div class="col-xl-3 col-lg-4 col-md-6 project-item" data-status="ongoing">
    <div class="project-card">
        <div class="project-image">
            <div class="overflow-hidden position-relative">
                <img src="images/residential-projects-mumbai/eden.webp" alt="Ajmera Eden">
                <div class="project-badges">
                    <span class="res">RESIDENTIAL</span>
                    <span class="ong">ONGOING</span>
                </div>
            </div>
        </div>
        <div class="project-info">
            <h5>Ajmera Eden</h5>
            <div class="project-details">
                <span>Ghatkopar East, Mumbai</span>
                <span>2, 3 & 4 BHK</span>
            </div>
        </div>
        <div class="project-actions d-flex">
            <a href="projects/ajmera-eden.php" class="explore-btn">Explore Project <b>›</b></a>
            <a href="#" class="enquire-btn"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">Enquire Now <b>›</b></a>
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
            What should I consider when choosing a residential property in Mumbai?
        </button>
    </h2>
    <div id="faq1"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                When selecting a residential property in Mumbai, consider the location, connectivity, neighbourhood infrastructure, configuration, amenities, construction quality and developer track record. Your budget and long-term requirements should also guide the decision.
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
            What are the benefits of investing in residential projects in Mumbai?
        </button>
    </h2>
    <div id="faq2"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Mumbai offers access to established business districts, educational institutions, healthcare facilities and lifestyle destinations. Its diverse residential market offers options across locations and budgets, making it relevant for both end users and long-term investors.
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
            How can I find new residential projects in Mumbai?
        </button>
    </h2>
    <div id="faq3"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Start by identifying preferred locations and comparing new residential projects in Mumbai based on their configurations, amenities, connectivity, possession timelines and RERA registration. Reviewing the developer's completed projects can also help you make an informed choice.
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
            What types of flats in Mumbai can I choose from?
        </button>
    </h2>
    <div id="faq4"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                The city offers a range of options, including 1, 2, 3 and 4 BHK homes, as well as premium and luxury residences. The choice depends on your family size, space requirements, preferred location and budget.
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
            What should I check before purchasing an apartment for sale in Mumbai?
        </button>
    </h2>
    <div id="faq5"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                Before purchasing an apartment in Mumbai, verify the project's RERA registration, title and approvals, carpet area, payment schedule, possession timeline and applicable charges. It is also important to review the amenities and connectivity offered by the development.
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
            What is the process to buy a flat or apartment in Mumbai?
        </button>
    </h2>
    <div id="faq6"
         class="accordion-collapse collapse"
         data-bs-parent="#impactAccordion">
        <div class="accordion-body">
            <p>
                To buy a flat in Mumbai, shortlist suitable properties, compare their specifications and pricing, visit the project and verify all legal documents. Once you finalise an apartment in Mumbai, review the sale agreement, understand the payment terms and complete the registration process. A careful approach to flat purchase in Mumbai can help ensure a more informed decision.
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