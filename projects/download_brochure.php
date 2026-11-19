
<?php
require_once __DIR__ . '/brochure_config.php';
?>

<!-- Download Brochure Modal -->
<div class="modal fade custom_modal custom_modal1"
     id="DownloadBrochure"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1"
     aria-labelledby="DownloadBrochureLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- Loader -->
                <div id="projectsLoader"
                     style="display:none; text-align:center; padding:20px;">
                    <div class="loader_2"></div>
                </div>

                <h4 class="h4 text-center" id="DownloadBrochureLabel">
                    Download Brochure
                </h4>

                <?php if (!empty($currentBrochure)) { ?>

                    <form id="brochureEnquiryForm"
                          class="custom-enquire-form"
                          method="POST"
                         action="/ajmera/projects/brochure_backend.php">

                        <!-- Current Project -->
                        <input type="hidden"
                               name="project"
                               value="<?php echo htmlspecialchars($currentBrochure['slug'], ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="row gx-4 gy-5" id="brochureFormFields">

                            <!-- First Name -->
                            <div class="col-md-6">
                                <input type="text"
                                       name="first_name"
                                       id="brochure_first_name"
                                       class="form-control custom-input"
                                       placeholder="First Name"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <input type="email"
                                       name="email"
                                       id="brochure_email"
                                       class="form-control custom-input"
                                       placeholder="Email"
                                       required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-12">
                                <input type="tel"
                                       name="phone"
                                       id="brochure_phone"
                                       class="form-control custom-input"
                                       placeholder="Phone Number"
                                       maxlength="10"
                                       minlength="10"
                                       pattern="[0-9]{10}"
                                       inputmode="numeric"
                                       required>
                            </div>

                            <!-- Message -->
                            <div class="col-12 mt-5">
                                <textarea name="message"
                                          id="brochure_message"
                                          class="form-control custom-input"
                                          placeholder="Message"
                                          rows="3"></textarea>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-5">
                                <button class="discover-btn"
                                        id="brochureSubmitBtn"
                                        type="submit">
                                    <span>Submit</span>
                                </button>
                            </div>

                        </div>
                    </form>

                <?php } else { ?>

                    <p class="text-center">
                        Brochure is not configured for this project.
                    </p>

                <?php } ?>

            </div>
        </div>
    </div>
</div>
