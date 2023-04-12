<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title"><?= $title; ?> Change Name Website</h3>
        </div>
        <div class="card-body">
          File in directory application/config/constants.php. Search site name and changed it.
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title"><?= $title; ?> CRUD</h3>
        </div>
        <div class="card-body">
          Viewable in the controller group
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center w-100">
          <h3 class="card-title">Data <?= $title; ?> Library</h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="table-library">
              <thead>
                <tr>
                  <th class="text-center" style="width: 5%;">No</th>
                  <th>Syntax</th>
                  <th>Param</th>
                  <th>Return</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center">1</td>
                  <td>$this->ion_auth->logged_in()</td>
                  <td>-</td>
                  <td>boolean</td>
                  <td>TRUE if the user is logged in FALSE if the user is not logged in.</td>
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td>$this->ion_auth->is_admin()</td>
                  <td>-</td>
                  <td>boolean</td>
                  <td>TRUE if the user is an admin FALSE if the user is not an admin.</td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td>$this->ion_auth->in_group($group)</td>
                  <td>
                    <table class="table table-bordered">
                      <tr>
                        <td>single group (by name)</td>
                        <td>$group = 'admin';</td>
                      </tr>
                      <tr>
                        <td>single group (by id)</td>
                        <td>$group = 1;</td>
                      </tr>
                      <tr>
                        <td>multiple groups (by name)</td>
                        <td>$group = array('admin', 'pengguna');</td>
                      </tr>
                      <tr>
                        <td>multiple groups (by id)</td>
                        <td>$group = array(1, 2)</td>
                      </tr>
                      <tr>
                        <td>multiple groups (by id and name)</td>
                        <td>$group = array('gangstas', 2);</td>
                      </tr>
                    </table>
                  </td>
                  <td>boolean</td>
                  <td>TRUE if the user is in all or any (based on passed param), FALSE otherwise.</td>
                </tr>
                <tr>
                  <td class="text-center">4</td>
                  <td>$user = $this->ion_auth->user()->row();</td>
                  <td>
                    'Id' - integer OPTIONAL. If a user id is not passed the id of the currently logged in user will be used. example:
                    <table class="table table-bordered">
                      <tr>
                        <td>$user = $this->ion_auth->user(1)->row();</td>
                      </tr>
                    </table>
                  </td>
                  <td>stdClass Object</td>
                  <td>Getting data user</td>
                </tr>
                <tr>
                  <td class="text-center">5</td>
                  <td>$users = $this->ion_auth->users()->result()</td>
                  <td>
                    'Group IDs, group names, or group IDs and names' - array OPTIONAL. If an array of group ids, of group names, or of group ids and names are passed (or a single group id or name) this will return the users in those groups. example
                    <table class="table table-bordered">
                      <tr>
                        <td>$users = $this->ion_auth->users(1)->result()</td>
                      </tr>
                      <tr>
                        <td>$users = $this->ion_auth->users([1,2])->result()</td>
                      </tr>
                      <tr>
                        <td>$users = $this->ion_auth->users('admin')->result()</td>
                      </tr>
                      <tr>
                        <td>$users = $this->ion_auth->users(['admin','pengguna'])->result()</td>
                      </tr>
                    </table>
                  </td>
                  <td>model object</td>
                  <td>Getting data all user</td>
                </tr>
                <tr>
                  <td class="text-center">6</td>
                  <td>$user_groups = $this->ion_auth->get_users_groups($user->id)->result();</td>
                  <td>'Id' - integer OPTIONAL. If a user id is not passed the id of the currently logged in user will be used.</td>
                  <td>stdClass Objec</td>
                  <td>Get all groups a user is part of.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Data <?= $title; ?> Config</h3>
          <br /><small>file in dir application/config/ion_auth.php</small>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="table-config">
              <thead>
                <tr>
                  <th class="text-center" style="width: 5%;">No</th>
                  <th>Syntax</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
              <tr>
                  <td class="text-center">1</td>
                  <td>$config['site_title'];</td>
                  <td>Site Title, example.com</td>
                </tr>
                <tr>
                  <td class="text-center">2</td>
                  <td>$config['min_password_length'];</td>
                  <td>Minimum Required Length of Password (not enforced by lib - see note above)</td>
                </tr>
                <tr>
                  <td class="text-center">3</td>
                  <td>$config['email_activation'];</td>
                  <td>Email Activation for registration</td>
                </tr>
                <tr>
                  <td class="text-center">4</td>
                  <td>$config['manual_activation'];</td>
                  <td>Manual Activation for registration</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var table;
  $(document).ready(function() {
    $('#table-library').DataTable()
    $('#table-config').DataTable()
  })
</script>