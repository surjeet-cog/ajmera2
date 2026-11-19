<?php include'../header.php' ?>




<div class="banner position-relative">
    <div class="banner-video position-relative">
        <video autoplay loop muted playsinline class="w-100" poster="">
            <source src="<?php echo $home_url; ?>images/vihara/Vihara-banner.mp4" type="video/mp4" />
            <source src="<?php echo $home_url; ?>images/vihara/Vihara-banner.ogv" type="video/ogg" />
            <source src="<?php echo $home_url; ?>images/vihara/Vihara-banner.webm" type="video/webm" />
        </video>
        <div class="overlay"></div>
    </div>

    <div class="caption d-flex align-items-center">
        <div class="container position-relative">
            <div class="m-0 ">
                <div class="project-logo">
                    <img src="<?php echo $home_url; ?>images/ajmera-vihar-loogo.png" alt="vihar logo"/>
                </div>                
            </div>
        </div>
    </div>
</div>

<section class="about-section pad80 stats-section1">
    <div class="container">

            <!-- About Content -->
            <div class="row">

               

                <!-- Right Column -->
                 <div class="col-lg-6">
                    <div class="prokect_sapce ">
                        <span class="span wow animate__animated animate__fadeInUp"> ABOUT PROJECT </span>
                        <h2 class="about-title h4 mb40 wow animate__animated animate__fadeInUp">  Every floor carries a promise.  </h2>
                        <p class="p mb-4 wow animate__animated animate__fadeInUp">
                            Ajmera Vihara is a landmark township development in Central Mumbai, bringing together smartly planned residences, landscaped spaces and a range of lifestyle amenities within a connected urban setting. 
                        </p>
                        
                         <p class="p mb-0 wow animate__animated animate__fadeInUp">
                           Located around the transforming Powai–Vikhroli corridor, Ajmera Vihara brings together everyday convenience and connectivity. With planned infrastructure, community spaces and a range of amenities, it is designed as a neighbourhood where everyday living feels complete. 
                        </p>

                        

                    </div>
                </div>
                <div class="col-lg-6">
                     <div class="loop_counter grey">
                                <div class="stat-item">
                                    <div class="stat-number1">
                                       <span class="odometer2 m-0" data-value="1">1</span>BHK
                                    </div>
                                    <div class="stat-label"> Smartly Planned Residences  </div>
                                </div>
                            </div>
                            
                            <div class="loop_counter grey">
                                <div class="stat-item">
                                    <div class="stat-number1">
                                        <span class="odometer2 m-0" data-value="40">40</span>+
                                    </div>
                                    <div class="stat-label">Lifestyle Amenities  </div>
                                </div
                </div>

            </div>

            <!-- Statistics -->
           <!-- <div class="row stats-section align-items-center g-0">

                <div class="col">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="odometer2 m-0" data-value="1">1</span>BHK
                        </div>

                        <div class="stat-label">
                           Smartly Planned Residences 
                        </div>
                    </div>
                </div>

                <div class="col d-none d-md-block">
                    <div class="stat-divider"></div>
                </div>

                <div class="col">
                    <div class="stat-item">
                        <div class="stat-number">
                            <span class="odometer2 m-0" data-value="40">40</span>+
                        </div>

                        <div class="stat-label">
                             Lifestyle Amenities 
                        </div>
                    </div>
                </div>

            </div>-->

           

        </div>
</section>


<section class="projects-section grey pad80 luxury-project-section">
    <div class="container">
        <div class="mb40">
            <span class="span wow animate__animated animate__fadeInUp">AMENITIES </span>
        <h2 class="h4 wow animate__animated animate__fadeInUp">Everything you need, within reach. 
</h2>
        </div>
        <div class="mb40 wow animate__animated animate__fadeInUp">
            <div class="slider1 slick-slider mt-5">
               <div> <img src="<?php echo $home_url; ?>images/vihara/gallery1.webp" alt="" class="w-100"/></div>
               <div> <img src="<?php echo $home_url; ?>images/vihara/gallery1.webp" alt="" class="w-100"/></div>
               <div> <img src="<?php echo $home_url; ?>images/vihara/gallery1.webp" alt="" class="w-100"/></div>
             
            </div>
        </div>
        
        <div class="animtes-list">
            
            <ul class="nav nav-tabs border-0" 
      id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" 
              id="home-tab" 
              data-bs-toggle="tab" data-bs-target="#home" 
              type="button" role="tab" 
              aria-controls="home" aria-selected="true">Active 
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" 
              id="profile-tab" 
              data-bs-toggle="tab" data-bs-target="#profile" 
              type="button" role="tab" 
              aria-controls="profile" aria-selected="false">Aqua  
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" 
              id="contact-tab" 
              data-bs-toggle="tab" data-bs-target="#contact" 
              type="button" role="tab" 
              aria-controls="contact" aria-selected="false">Kids  
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" 
              id="outdoor-tab" 
              data-bs-toggle="tab" data-bs-target="#outdoor" 
              type="button" role="tab" 
              aria-controls="outdoor" aria-selected="false">Relax   
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" 
              id="nature-tab" 
              data-bs-toggle="tab" data-bs-target="#nature" 
              type="button" role="tab" 
              aria-controls="nature" aria-selected="false">Nature    
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" 
              id="everyday-tab" 
              data-bs-toggle="tab" data-bs-target="#everyday " 
              type="button" role="tab" 
              aria-controls="everyday" aria-selected="false">Everyday    
      </button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" 
         id="home" 
         role="tabpanel" aria-labelledby="home-tab">
       <div class="row">
                <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/active/Jogging-Track.svg" class="w68" alt="Jogging Track"/>
        </div>
        <p class="mt-3">Jogging Track</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/active/Gym.svg" class="w68" alt="Gym"/>
        </div>
        <p class="mt-3">Gym</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/active/Spa.svg" class="w68" alt="Spa"/>
        </div>
        <p class="mt-3">Spa</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/active/Table-Tennis.svg" class="w68" alt="Table Tennis"/>
        </div>
        <p class="mt-3">Table Tennis</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/active/Seating-Areas.svg" class="w68" alt="Seating Areas"/>
        </div>
        <p class="mt-3">Seating Areas</p>
    </div>
</div>
        </div>
    </div>
    <div class="tab-pane fade" 
         id="profile" 
         role="tabpanel" aria-labelledby="profile-tab">
       <div class="row">
                <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/aqua/Swimming-Pool.svg" class="w68" alt="Swimming Pool"/>
        </div>
        <p class="mt-3">Swimming Pool</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/aqua/Splash-Pool.svg" class="w68" alt="Splash Pool"/>
        </div>
        <p class="mt-3">Splash Pool</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/aqua/Jacuzzi.svg" class="w68" alt="Jacuzzi"/>
        </div>
        <p class="mt-3">Jacuzzi</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/aqua/Water-Based-Recreation.svg" class="w68" alt="Water-based Recreation"/>
        </div>
        <p class="mt-3">Water-based Recreation</p>
    </div>
</div>
        </div>
    </div>
    <div class="tab-pane fade" 
         id="contact" 
         role="tabpanel" aria-labelledby="contact-tab">
      <div class="row">
                <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/kids/Kids-Play-Area.svg" class="w68" alt="Kids' Play Area"/>
        </div>
        <p class="mt-3">Kids' Play Area</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/kids/Childrens-Recreation.svg" class="w68" alt="Children's Recreation"/>
        </div>
        <p class="mt-3">Children's Recreation</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/kids/Toddler-Zone.svg" class="w68" alt="Toddler Zone"/>
        </div>
        <p class="mt-3">Toddler Zone</p>
    </div>
</div>
                
               
        </div>
    </div>
    <div class="tab-pane fade" 
         id="outdoor" 
         role="tabpanel" aria-labelledby="outdoor-tab">
     <div class="row">
                <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/relax/Yoga.svg" class="w68" alt="Yoga"/>
        </div>
        <p class="mt-3">Yoga</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/relax/Meditation.svg" class="w68" alt="Meditation"/>
        </div>
        <p class="mt-3">Meditation</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/relax/Amphitheatre.svg" class="w68" alt="Amphitheatre"/>
        </div>
        <p class="mt-3">Amphitheatre</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/relax/Seating-Areas.svg" class="w68" alt="Seating Areas"/>
        </div>
        <p class="mt-3">Seating Areas</p>
    </div>
</div>
        </div>
    </div>
    
    <div class="tab-pane fade" 
         id="nature" 
         role="tabpanel" aria-labelledby="nature-tab">
     <div class="row">
               <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/nature/Organic-Farm.svg" class="w68" alt="Organic Farm"/>
        </div>
        <p class="mt-3">Organic Farm</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/nature/Landscaped-Spaces.svg" class="w68" alt="Landscaped Spaces"/>
        </div>
        <p class="mt-3">Landscaped Spaces</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/nature/Senior-Citizen-Garden.svg" class="w68" alt="Senior Citizen Garden"/>
        </div>
        <p class="mt-3">Senior Citizen Garden</p>
    </div>
</div>
        </div>
    </div>
    <div class="tab-pane fade" 
         id="everyday" 
         role="tabpanel" aria-labelledby="everyday-tab">
     <div class="row">
               <div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/everyday/cafe.svg" class="w68" alt="Café / Convenience"/>
        </div>
        <p class="mt-3">Café / Convenience</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/everyday/Shopping.svg" class="w68" alt="Shopping"/>
        </div>
        <p class="mt-3">Shopping</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/everyday/Work-Spaces.svg" class="w68" alt="Work Spaces"/>
        </div>
        <p class="mt-3">Work Spaces</p>
    </div>
</div>

<div class="col-6 col-md-3 col-lg-2">
    <div class="aminity-point text-center">
        <div>
            <img src="<?php echo $home_url; ?>images/vihara/everyday/Community-Areas.svg" class="w68" alt="Community Areas"/>
        </div>
        <p class="mt-3">Community Areas</p>
    </div>
</div>
        </div>
    </div>
  </div>
            
            
            
           
            
        </div>
       

    </div>
</section>

<section class="news-section pad80 pb-0 overflow-hidden spaces" style="background:#CB1E28;">
    <div class="container">
       <div class="mb40">
            <span class="span wow animate__animated animate__fadeInUp text-white">CONFIGURATIONS  </span>
            <h2 class="h4 wow animate__animated animate__fadeInUp text-white">A home designed around everyday ease. </h2>
        </div>
    </div>
    
    <div class="container-fluid p-0">
        <div class="row m-0"> <!-- m-0 (margin 0) to avoid side scrolling issues -->
            
            <!-- LEFT COLUMN (IMAGE) -->
            <div class="col-md-7 col-lg-8 p-0">
                <!-- Added h-100 -->
                <div class="property-img h-100">
                    <img src="<?php echo $home_url; ?>images/vihara/vihara-spaces1.webp" alt="" class="w-100 h-100 object-fit-cover" />
                </div>
            </div>
            
            <!-- RIGHT COLUMN (TEXT BOXES) -->
            <div class="col-md-5 col-lg-4 p-0">
                <!-- Added h-100 to main wrapper -->
                <div class="d-flex flex-column property-main h-100" style="gap: 3px;">
                    
                    <!-- Replaced h-50 with flex-grow-1, d-flex, flex-column, justify-content-center, align-items-start -->
                    <div class="property-inner flex-grow-1 d-flex flex-column  align-items-start wow animate__animated animate__fadeInUp" style="background:#B1131C;">
                        <div class="property-type text-white mx-0">1 BHK </div>
                        <p class="text-white">
                           Smartly planned residences that bring together comfort, functionality and efficient use of space. 
                        </p>
                         <div class="d-flex m-0">
                            <span data-bs-toggle="modal" data-bs-target="#floorplan" class="m-0">Get Price 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </span>
                            <span data-bs-toggle="modal" data-bs-target="#floorplan" class="ms-4">Floor Plans 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </span>
                        </div>
                    </div>
                    
                   
                    
                </div>
            </div>

        </div>
    </div>
</section>

<section class="projects-section  pad80 luxury-project-section">
    <div class="container">
        <div class="mb40">
            
        </div>
        
         <div class="mb40">
            <div class="row align-items-end">
                <div class="col-md-12">
                    <span class="span wow animate__animated animate__fadeInUp">GALLERY</span>
            <h2 class="h4 wow animate__animated animate__fadeInUp">A closer look at your home. </h2>
                </div>    
               
            </div>
        </div>
        
    </div>    
        
                <div class="position-relative h-100">
                    <div class="manhattan-galley-slider1 mb-3">
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara1.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara2.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara3.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara4.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara5.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara6.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara7.webp" alt="" class="w-100"/> </div>
                        <div> <img src="<?php echo $home_url; ?>images/vihara/vihara8.webp" alt="" class="w-100"/> </div>
                       
                    </div>
                    
                   
                <!-- Custom Navigation Arrows for Left Bottom -->
                <div class="gallery-arrows gallery-arrows1 container d-flex justify-content-end gap-2">
                    <button class="gallery-prev-btn" aria-label="Previous">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </button>
                    <button class="gallery-next-btn" aria-label="Next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
                </div>
          
            
            
            
        
    
</section>


<section class="location-section pad80  grey  pb-0">
    <div class="container">
        <!-- Section Heading -->
        <div class="mb40">
            <span class="span wow animate__animated animate__fadeInUp">LOCATION</span>
            <h2 class="h4 wow animate__animated animate__fadeInUp">Where connectivity becomes everyday ease. </h2>
        </div>
    </div>
    <span class="connectivity">Connectivity <svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#fff" stroke-width="1"></path></svg></span>
    <div class="container-fluid p-0">
        <div class="row m-0">
            <!-- LEFT COLUMN: Sticky Map -->
            <div class="col-md-7 col-lg-8 p-0">
                <div class="sticky-map-wrapper wow animate__animated animate__fadeInLeft">
                    <!--<img src="<?php echo $home_url; ?>images/vihara/vihara-map.webp" alt="vihara Location Map" class="map-bg"/>-->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3769.164664674768!2d72.92588429999999!3d19.1442681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b92e18ded023%3A0x1f56458d65370d95!2sAjmera%20Vihara!5e0!3m2!1sen!2sin!4v1791438904063!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    <!-- Center Logo Marker -->
                    <!--<div class="map-center-logo map-solis-logo">
                        <img src="<?php echo $home_url; ?>images/solis-logo.svg" alt="solis Logo">
                    </div>-->
                </div>
            </div>

            <!-- RIGHT COLUMN: 5 Scrollable Content Boxes -->
            <div class="col-md-5 col-lg-4 p-0">
                <div class="overlow-scroll">
                <!-- Box 1 -->
                <div class="location-box">
                    <h3>Road Connectivity </h3>
                    <ul>
                        <li>Powai–Vikhroli Link Road </li>
                        <li>Eastern Express Highway </li>
                        <li>90-ft Road </li>
                    </ul>
                   <!-- <div class="text-end mt-4">
                        <a href="#" class="explore-loc-btn">Explore Locations <span>></span></a>
                    </div>-->
                </div>

                <!-- Box 2 -->
                <div class="location-box border-0">
                    <h3>Upcoming Infrastructure </h3>
                    <ul>
                        <li>Upcoming Metro Line 4 </li>
                        <li>Planned connectivity improvements </li>
                    </ul>
                  <!--  <div class="text-end mt-4">
                        <a href="#" class="explore-loc-btn">Explore Locations <span>></span></a>
                    </div>-->
                </div>

              </div>

            </div>
        </div>
    </div>
</section>  

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
                
                <?php include 'project_form.php'?>
               
            </div>

            <!-- RIGHT COLUMN: Image -->
            <div class="col-md-5 ps-md-5 pt-md-5 h-100 wow animate__animated animate__fadeInRight d-none d-md-block">
                <div class="enquire-image-wrapper ">
                    <img src="<?php echo $home_url; ?>images/vihara/vihara-form.webp" alt="Find your new address" />
                </div>
            </div>

        </div>
    </div>
</section>
<!-- === END ENQUIRE NOW SECTION === -->
<section class="quick-links-strip  wow animate__animated animate__fadeInUp">
    <div class="container">
        <div class="d-flex flex-md-row j align-items-center flex-wrap gap-2 gap-md-4 py-4 px-3 ">
            <a href="javascript:void()" data-bs-toggle="modal" data-bs-target="#DownloadBrochure" class="strip-link m-0">
              Download Brochure
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 4.545 7.676">
                        <path d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1.2"></path>
                    </svg>
                </span>
            </a>
            <a href="../pdf/project/Ajmera-Vihara.pdf" class="strip-link m-0" target="_blank">
                RERA CERTIFICATE 
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 4.545 7.676">
                        <path d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1.2"></path>
                    </svg>
                </span>
            </a>

          <!--  <a href="#" class="strip-link m-0">
                ENVIRONMENT CLEARANCE 
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 4.545 7.676">
                        <path d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1.2"></path>
                    </svg>
                </span>
            </a>

            <a href="#" class="strip-link m-0">
                EC COMPLIANCE REPORT 
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 4.545 7.676">
                        <path d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1.2"></path>
                    </svg>
                </span>
            </a>-->

        </div>
    </div>
</section>

<?php include'download_brochure.php'?>
<?php include'../footer.php' ?>
