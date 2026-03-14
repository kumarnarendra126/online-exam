<!--Modal for admin login-->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered themed-modal-dialog" role="document">
    <div class="modal-content themed-modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel"><i class="fas fa-lock"></i> Admin Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if(isset($_GET['e'])) { echo '<div class="alert alert-danger" role="alert">'.$_GET['e'].'</div>'; } ?>
        <div id="adminLoginError" class="alert alert-danger" style="display: none;"></div>
        <form id="adminLoginForm" method="post" action="admin.php">
          <div class="form-group">
            <label for="uname">Admin User ID</label>
            <input id="uname" name="uname" placeholder="Admin User ID" class="form-control" type="text" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input id="password" name="password" placeholder="Enter your Password" class="form-control" type="password" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="adminLoginForm" class="btn btn-primary">Log in</button>
      </div>
    </div>
  </div>
</div>
<!--Modal for admin login closed-->