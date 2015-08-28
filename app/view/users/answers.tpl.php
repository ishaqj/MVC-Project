<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg?s=127'; ?>
    <div class="row profile">
            <a href='<?=$this->url->create('users')?>'><i class="fa fa-arrow-left"></i> Back</a></p>

        <div class="col-md-3">
            <div class="profile-sidebar">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <img src="<?=$gravatar?>" class="img-responsive" alt="">
                </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <?=$username?>
                    </div>
                    <div class="profile-usertitle-job">
                        Member
                    </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR BUTTONS -->
                <div class="profile-userbuttons">
           <a href='<?=$this->url->create('questions/findQuestions/'.$loggedInId)?>'><button type="button" class="btn btn-success btn-sm">All Questions</button></a>
                      <a href='<?=$this->url->create('questions/findAnswers/'.$loggedInId)?>'><button type="button" class="btn btn-danger btn-sm">All Answers</button></a>
                </div>
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
                <?php if(isset($this->di->session->get('user')[0]->id)) : ?>
                <?php if($loggedInId == $this->di->session->get('user')[0]->id) : ?>
                <div class="profile-usermenu">
                    <ul class="nav">
                        <li>
                            <a href="<?=$this->url->create('users/id/'.$loggedInId)?>">
                            <i class="glyphicon glyphicon-home"></i>
                            Overview </a>
                        </li>
                        <li><a href='<?=$this->url->create('users/update')?>'>
                            <i class="glyphicon glyphicon-user"></i>
                            Account Settings </a>
                        </li>
                    </ul>
                </div>
           <?php endif; ?>
            <?php else :?>
            <div class="profile-usermenu">
                    <ul class="nav">
                        <li>
                            <a href="<?=$this->url->create('users/id/'.$loggedInId)?>">
                            <i class="glyphicon glyphicon-home"></i>
                            Overview </a>
                        </li>
                    </ul>
                </div> 
            <?php endif; ?>
                 <!-- END MENU -->
            </div>

        </div>
<div class="col-md-7">
<div class="profile-content">
<h1><?=$title?></h1>
<hr>
<?php if(isset($question)) : ?>
<?php if (!empty($question)) : ?>
<?php foreach ($question as $questions) : ?>
<a href='<?=$this->url->create('questions/id/'.$questions->qId)?>'><h3><?=$questions->title?></h3></a>
<?=$questions->content?>
<em><?=$questions->created?></em>
<hr>
 <?php endforeach; ?>

 <?php else : ?>
  <p>User have not posted any questions </p>  
 <?php endif; ?>
<?php else :?>
<?php endif;?>
<?php if(isset($answer)) : ?>
<?php if (!empty($answer)) : ?>
<?php foreach ($answer as $answers) : ?>
<a href='<?=$this->url->create('questions/id/'.$answers->questionid)?>'><h3><?=$answers->title?></h3></a>
<?=$answers->answer?>
<em><?=$answers->added?></em>
<hr>
 <?php endforeach; ?>

 <?php else : ?>
  <p>User have not posted any answers </p>  
 <?php endif; ?>
<?php else :?>
<?php endif; ?>
 </div>
 </div>
 </div>