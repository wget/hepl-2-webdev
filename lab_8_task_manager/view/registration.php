<?php ob_start(); ?>

<form class="needs-validation" id="registration-form" novalidate>
  <h2 class="mb-3">Registration process</h4>
  <div class="feedback"></div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="username">Username<span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="username" name="username" placeholder="" value="" autocomplete="username" required>
      <div class="invalid-feedback"></div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="password-1">Password<span class="text-muted">*</span></label>
      <input type="password" class="form-control" id="password" name="password" placeholder="" value="" autocomplete="new-password" required>
      <div class="invalid-feedback"></div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="password">Confirm password<span class="text-muted">*</span></label>
      <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="" value="" autocomplete="new-password" required>
      <div class="invalid-feedback"></div>
    </div>
  </div>

  <hr class="mb-4">
  <p class="text-muted">Note: Wrt. GDPR agreement, you need to be at least 13 years old to register.</p>
  <button class="btn btn-primary btn-lg float-right col-6 col-md-3" type="submit">Register</button>
  <button class="btn btn-secondary col-4 col-md-3" type="reset">Reset</button>
</form>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
