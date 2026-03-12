<!--Modal for admin login-->
<div class="modal fade" id="login">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content themed-modal">
      <div class="modal-header">
        <h4 class="modal-title"><i class="glyphicon glyphicon-lock"></i> Admin Login</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if(isset($_GET['e'])) { echo '<div style="color: red; text-align: center; margin-bottom: 15px; font-weight: bold;">'.$_GET['e'].'</div>'; } ?>
        <form id="adminLoginForm" class="form-horizontal" method="post" action="admin.php?q=dash.php">
          <fieldset>
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-12 control-label" for="uname">Admin User ID</label>  
              <div class="col-md-12">
              <input id="uname" name="uname" placeholder="Admin User ID" class="form-control input-md" type="text">
                
              </div>
            </div>
            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-12 control-label" for="password">Password</label>
              <div class="col-md-12">
                <input id="password" name="password" placeholder="Enter your Password" class="form-control input-md" type="password">
                
              </div>
            </div>
            
          </fieldset>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Log in</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Modal for admin login closed-->