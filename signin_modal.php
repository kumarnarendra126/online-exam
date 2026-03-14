<!--sign in modal start-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered themed-modal-dialog" role="document">
    <div class="modal-content themed-modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel"><i class="fas fa-sign-in-alt"></i> Log in</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="cursor: pointer;">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="loginError" class="alert alert-danger" style="display: none;"></div>
        <form id="loginForm" action="login.php" method="POST">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" name="email" placeholder="Enter your email-id" class="form-control" type="email" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" name="password" placeholder="Enter your Password" class="form-control" type="password" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="loginForm" class="btn btn-primary">Log in</button>
      </div>
    </div>
  </div>
</div>
<!--sign in modal closed-->