<h1>Users</h1>
<hr>
<?php if(!empty($users)) : ?>

    <div class="row profile">
        <?php foreach ($users as $user): ?>

        <div class="col-md-3">
        
            <div style = "margin: 5px;" class="profile-sidebar">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                
                <?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '.jpg?s=170'; ?>
                <img src="<?=$gravatar?>" class="img-responsive" alt="">
               
                    
                </div>
              
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
         
                <div  class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        
                        <?php $url = $this->url->create('users/id/' . $user->id) ?>
                        <?=$user->username?>
                        </div>
                        <div class="profile-usertitle-job">
                        Member
                    </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR BUTTONS -->
                <div class="profile-userbuttons">
                    <p>  Joined: <?=$user->created?></p>
                </div>
           
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
            <div class="profile-usermenu">
                    <ul class="nav">
                        <li>
                            <a href="<?=$url?>">
                            <i class="glyphicon glyphicon-user"></i>
                            Profile </a>
                        </li>
                    </ul>
                </div> 
                 <!-- END MENU -->
                
            </div>
            </div>
                          <?php endforeach; ?>
            </div>



<?php else : ?>

    <p>No users found.</p>

<?php endif; ?>