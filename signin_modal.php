<!--sign in modal start-->
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content themed-modal">
      <div class="modal-header">
        <h4 class="modal-title"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;Log in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php if(isset($_GET['w']))
        {
          echo'<div class="alert alert-danger">'.htmlspecialchars($_GET['w']).'</div>';
        }?>
        <form class="form-horizontal" action="login.php?q=index.php" method="POST">
          <fieldset>
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="email">Email Address</label>  
              <div class="col-md-6">
                <input id="email" name="email" placeholder="Enter your email-id" class="form-control input-md" type="email">
              </div>
            </div>
            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="password">Password</label>
              <div class="col-md-6">
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
<!--sign in modal closed-->