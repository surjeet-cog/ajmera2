
<?php
$currentProject = 'Ajmera Elevate';
?>

<form id="projectEnquiryForm2"
      class="custom-enquire-form wow animate__animated animate__fadeInUp"
      method="POST"
      action="project_form_backend.php">

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

        <!-- Phone -->
        <div class="col-md-6">
            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-control custom-input"
                   placeholder="Phone Number"
                   maxlength="10">
        </div>

        <!-- Project -->
        <div class="col-md-6">

            <select name="project_display"
                    id="project"
                    class="form-control custom-input custom-select"
                    disabled>

                <option value="Ajmera Elevate" selected>
                    Ajmera Elevate
                </option>

            </select>

            <!-- Hidden input submits the project value -->
            <input type="hidden"
                   name="project"
                   value="<?php echo htmlspecialchars($currentProject, ENT_QUOTES, 'UTF-8'); ?>">

        </div>

        <!-- Message -->
        <div class="col-12 mt-5">
            <textarea name="message"
                      id="message"
                      class="form-control custom-input"
                      placeholder="Message"
                      rows="4"></textarea>
        </div>

        <!-- Submit -->
        <div class="col-12 mt-5">

            <button class="discover-btn"
                    id="projectSubmitBtn"
                    type="submit">

                <span>Submit</span>

            </button>

        </div>

    </div>

</form>
