<style>
/* Close / Cross Button */
.ajmera-search-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ajmera-search-top button {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 42px;
    height: 42px;

    position: fixed;
    top: 22px;
    right: 35px;
    z-index: 1000001;

    background: transparent;
    border: 1px solid #514b40;
    border-radius: 50%;

    color: #ffffff;
    font-size: 30px;
    font-weight: 300;
    line-height: 1;

    cursor: pointer;
    transition: all 0.3s ease;
}

.ajmera-search-top button:hover {
    background: #b59459;
    border-color: #b59459;
    color: #13120f;
    transform: rotate(90deg);
}

@media (max-width: 767px) {
    .ajmera-search-top button {
        top: 15px;
        right: 15px;
        width: 36px;
        height: 36px;
        font-size: 26px;
    }
}
/* ==============================
   AJMERA SEARCH OVERLAY
============================== */

.ajmera-search-overlay,
.ajmera-search-overlay * {
    box-sizing: border-box;
}

.ajmera-search-overlay {
        align-items: center;
    display: flex;
    flex-wrap: wrap;
    
    position: fixed;
    inset: 0;
    z-index: 999999;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    overflow-y: auto;
    overscroll-behavior: contain;
    background: #fff;
    color: #f4f1eb;

    transform: translateY(-102%);
    visibility: hidden;
    pointer-events: none;

    transition:
        transform 0.55s cubic-bezier(.77, 0, .18, 1),
        visibility 0s linear 0.55s;
}

.ajmera-search-overlay.is-open {
    transform: translateY(0);
    visibility: visible;
    pointer-events: auto;

    transition:
        transform 0.55s cubic-bezier(.77, 0, .18, 1),
        visibility 0s;
}

body.ajmera-search-open {
    overflow: hidden;
}

.ajmera-search-container {
    width: min(100% - 80px, 1240px);
    margin: 0 auto;
    padding: 32px 0 50px;
}

/* Top label and close button */

.ajmera-search-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ajmera-search-top label,
.ajmera-search-section h4,
.ajmera-search-right > h4,
.ajmera-search-results > h4 {
    margin: 0;
    color: #000;
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 1.8px;
}
.ajmera-quick-grid a {
    width: 100%;
}
.ajmera-quick-grid a span {
    margin:0;
}
.ajmera-search-top button {
    display: none;
    border: 0;
    background: transparent;
    color: #fff;
    font-size: 30px;
    cursor: pointer;
}

/* Search field */

.ajmera-search-form {
    display: flex;
    align-items: center;
    height: 62px;
    border-bottom: 1px solid #29272024;
    margin-bottom: 0;
}

.ajmera-search-form input {
    width: 100%;
    height: 100%;
    padding: 0;
    border: 0;
    outline: none;
    color: #CB1E28;
    background: transparent;
    font-size: 24px;
}

.ajmera-search-form input::-webkit-search-cancel-button {
    cursor: pointer;
}

.ajmera-search-form button {
    flex: 0 0 38px;
    padding: 7px;
    border: 0;
    color: #a98b58;
    background: transparent;
    cursor: pointer;
}

.ajmera-search-form svg {
    display: block;
    width: 23px;
    fill: none;
    stroke: #CB1E28;
    stroke-width: 1.8;
}

/* Tabs */

.ajmera-search-tabs {
    display: flex;
    align-items: center;
    gap: 28px;
    min-height: 42px;
    border-bottom: 1px solid #292720;
    margin-bottom: 29px;
}

.ajmera-search-tabs button {
    position: relative;
    align-self: stretch;
    padding: 0;
    border: 0;
    background: transparent;
    color: #8b8982;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;
    cursor: pointer;
}

.ajmera-search-tabs button::after {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 0;
    height: 2px;
    content: "";
    background: #b59459;
    transition: width .25s ease;
}

.ajmera-search-tabs button:hover,
.ajmera-search-tabs button.active {
    color: #d4b477;
}

.ajmera-search-tabs button.active::after {
    width: 100%;
}

/* Two-column layout */

.ajmera-search-content {
    display: grid;
    grid-template-columns: 1.05fr 1fr;
    gap: 48px;
    align-items: start;
}

.ajmera-search-section {
    margin-bottom: 30px;
}

.ajmera-search-section h4,
.ajmera-search-right > h4,
.ajmera-search-results > h4 {
    margin-bottom: 14px;
}

/* Popular searches */

.ajmera-popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 7px 6px;
}

.ajmera-popular-tags a {
    display: inline-flex;
    align-items: center;
    min-height: 29px;
    padding: 5px 13px;
    border: 1px solid #3a3730;
    border-radius: 30px;
    color: #e1ded7;
    font-size: 11px;
    text-decoration: none;
    transition: background .25s, border-color .25s;
}

.ajmera-popular-tags a:hover {
    border-color: #a98b58;
    background: #28251e;
}

/* Quick links */

.ajmera-quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 25px;
}

.ajmera-quick-grid a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 36px;
    gap: 8px;
    border-bottom:1px solid #29272024;
    color: #000;
    font-size: 12px;
    text-decoration: none;
    transition: color .2s;
}

.ajmera-quick-grid a span {
    color: #b59459;
    font-size: 15px;
}

.ajmera-quick-grid a:hover {
    color: #CB1E28;
}

/* Featured projects */

.ajmera-featured-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 16px;
}

.ajmera-featured-card {
    min-width: 0;
    color: #fff;
    text-decoration: none;
}

.ajmera-featured-image {
    width: 100%;
    aspect-ratio: 1.45 / 1;
    overflow: hidden;
    background: repeating-linear-gradient(
        135deg,
        #292823 0,
        #292823 12px,
        #22211d 12px,
        #22211d 24px
    );
}

.ajmera-featured-image img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
}

.ajmera-featured-card:hover .ajmera-featured-image img {
    transform: scale(1.045);
}

.ajmera-featured-card h3 {
    margin: 9px 0 3px;
    color: #000;
    font-family: Georgia, serif;
    font-size: 16px;
    font-weight: 400;
}

.ajmera-featured-card p {
    margin: 0;
    color: #99958b;
    font-size: 10px;
    line-height: 1.5;
}

/* Search results */

.ajmera-search-results {
    grid-column: 1 / -1;
}

.ajmera-search-results-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 30px;
}

.ajmera-search-result-link {
    display: block;
    padding: 14px 0;
    border-bottom: 1px solid #302d26;
    color: #eee9df;
    font-size: 14px;
    text-decoration: none;
}

.ajmera-search-result-link:hover {
    color: #c5a36a;
}

.ajmera-search-result-link small {
    display: block;
    margin-top: 5px;
    color: #969087;
    font-size: 11px;
}

.ajmera-no-results {
    color: #aaa49a;
    font-size: 13px;
}

#closeAjmeraSearch {
    position: absolute;
    right: 0;
    top: 30px;
    border: none;
    background: transparent;
    color: #000;
    font-size: 30px;
    right: 70px;
}

</style>



<!-- Search Overlay -->
<div class="ajmera-search-overlay" id="ajmeraSearchOverlay">
<button
    type="button"
    id="closeAjmeraSearch"
    aria-label="Close search">
    &times;
</button>
    <div class="container">

        <!-- Search Header -->
        <div class="ajmera-search-top">
            <!--<label for="ajmeraSearchInput">SEARCH AJMERA</label>-->

            <button type="button" id="closeAjmeraSearch" aria-label="Close search">
                &times;
            </button>
        </div>

        <!-- Search Input -->
        <form action="/ajmera/search.php" method="GET"
      class="ajmera-search-form">

    <input type="search"
           name="q"
           id="ajmeraSearchInput"
           placeholder="Search here..."
           autocomplete="off">

    <button type="submit"><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><style>.cls-1{fill:none;stroke:#000;stroke-miterlimit:10;stroke-width:10px;}</style></defs><circle class="cls-1" cx="71.68" cy="73.23" r="63.62"/><line class="cls-1" x1="119.79" y1="118.23" x2="191.94" y2="190.39"/></svg></button>

</form>

        <!-- Search Tabs -->
        <!--<nav class="ajmera-search-tabs" aria-label="Search categories">
            <button class="active" type="button" data-search-tab="all">ALL</button>
            <button type="button" data-search-tab="projects">PROJECTS</button>
            <button type="button" data-search-tab="news">NEWS &amp; BLOGS</button>
            <button type="button" data-search-tab="faqs">FAQS</button>
            <button type="button" data-search-tab="pages">PAGES</button>
        </nav>-->

        <!-- Search Content -->
        <div class="ajmera-search-content mt-5">

            <!-- Left Column -->
            <div class="ajmera-search-left w-100">

           <!--     <section class="ajmera-search-section" data-search-category="all">
                    <h4>POPULAR SEARCHES</h4>

                    <div class="ajmera-popular-tags">
                        <a href="/ajmera/projects/ajmera-manhattan/">Ajmera Manhattan</a>
                        <a href="/ajmera/projects/ajmera-vann/">Vann</a>
                        <a href="/ajmera/projects/">2 BHK Mumbai</a>
                        <a href="/ajmera/projects/">Bengaluru</a>
                        <a href="/ajmera/projects/">Ongoing projects</a>
                        <a href="/ajmera/projects/">Commercial</a>
                        <a href="/ajmera/financial-results/">Financial Results</a>
                        <a href="/ajmera/careers/">Careers</a>
                        <a href="/ajmera/nri-corner/">NRI</a>
                    </div>
                </section>-->

                <section class="ajmera-search-section ajmera-quick-links"
                         data-search-category="all">
                    <h4>QUICK LINKS</h4>

                    <div class="ajmera-quick-grid">
                        <a href="<?php echo $home_url; ?>about-us.php">Our Story <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                        <a href="<?php echo $home_url; ?>by-ajmera-the-luxury-collective.php">Luxury Collective <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                        <a href="<?php echo $home_url; ?>nri-corner.php">NRI Corner <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                        <a href="<?php echo $home_url; ?>nri-corner.php#emi">EMI Calculator <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                        <a href="<?php echo $home_url; ?>careers.php">Careers <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                        <a href="<?php echo $home_url; ?>media.php">Media <span><svg xmlns="http://www.w3.org/2000/svg" width="4.545" height="7.676" viewBox="0 0 4.545 7.676">   <path id="Path_460" data-name="Path 460" d="M2926.232,1229.357l3.484,3.484-3.484,3.485" transform="translate(-2925.878 -1229.004)" fill="none" stroke="#cb1e28" stroke-width="1"></path></svg></span></a>
                    </div>
                </section>

            </div>

            <!-- Right Column: Featured Projects -->
            <div class="ajmera-search-right" data-search-category="all projects">

                <h4>FEATURED PROJECTS</h4>

                <div class="ajmera-featured-grid">

                    <a href="<?php echo $home_url; ?>projects/ajmera-manhattan.php/"
                       class="ajmera-featured-card"
                       data-project-name="Ajmera Manhattan Manhattan">

                        <div class="ajmera-featured-image">
                            <img src="<?php echo $home_url; ?>images/Ajmera-Manhattan-search.webp"
                                 alt="Ajmera Manhattan"
                                 loading="lazy">
                        </div>

                        <h3>Ajmera Manhattan</h3>
                        <p>Wadala, Mumbai</p>
                    </a>

                    <a href="<?php echo $home_url; ?>projects/ajmera-solis.php"
                       class="ajmera-featured-card"
                       data-project-name="Ajmera Vann Mumbai">

                        <div class="ajmera-featured-image">
                            <img src="<?php echo $home_url; ?>images/Ajmera-Solis-search.webp"
                                 alt="Ajmera solis"
                                 loading="lazy">
                        </div>

                        <h3>Ajmera Solis</h3>
                        <p>Vikhroli East, Mumbai</p>
                    </a>

                   

                </div>
            </div>

            <!-- Search Results -->
            <div class="ajmera-search-results" id="ajmeraSearchResults" hidden>
                <h4>SEARCH RESULTS</h4>
                <div class="ajmera-search-results-list"></div>
                <p class="ajmera-no-results" hidden>No matching links found.</p>
            </div>

        </div>
    </div>
</div>
