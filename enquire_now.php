
<!-- Modal -->
<div class="modal fade custom_modal enquiremodal custom_modal1" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <h4 class="h4 text-center">Enquire Now</h4>
        <form id="enquiryForm"
      class="custom-enquire-form"
      method="POST"
      action="/ajmera/enquire_backend.php">

    <!-- Loader -->
    <div id="projectsLoader" style="display:none; text-align:center; padding:20px;">
        <div class="loader_2"></div>
        <p>Please wait, submitting your enquiry...</p>
    </div>

    <div class="row gx-4 gy-5">

        <div class="col-md-6">
            <input type="text" name="first_name" id="first_name"
                   class="form-control custom-input"
                   placeholder="First Name">
        </div>

        <div class="col-md-6">
            <input type="email" name="email" id="email"
                   class="form-control custom-input"
                   placeholder="Email">
        </div>

        <div class="col-md-12">
            <input type="tel" name="phone" id="phone"
                   class="form-control custom-input"
                   placeholder="Phone Number" maxlength="10">
        </div>

        <div class="col-12 mt-5">
            <textarea name="message" id="message"
                      class="form-control custom-input"
                      placeholder="Message" rows="3"></textarea>
        </div>

        <div class="col-12 mt-5">
            <button class="discover-btn" id="projectSubmitBtn" type="submit">
                <span>Submit</span>
            </button>
        </div>

    </div>
</form>
      </div>
      
    </div>
  </div>
</div>

