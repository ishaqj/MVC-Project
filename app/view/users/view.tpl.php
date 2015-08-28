<?php if (!empty($user)) : ?>
<?php $properties = $user->getProperties(); ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($properties['email']))) . '.jpg?s=127'; ?>
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
                        <?=$properties['username']?>
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
                        <li class="active">
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
                        <li class="active">
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
            <h1>Recent Questions</h1>
            <hr>
            <?php if (!empty($question)) : ?>
            <?php foreach ($question as $value): ?>
    <a href='<?=$this->url->create('questions/id/'.$value->id)?>'><h3><?=$value->title?></h3></a>
    <?=$value->content?>
    <?php foreach ($value->svar as $svar): ?>
    <?php endforeach ?>
    <em><?=$value->created?></em>
    <br><span class="label label-success labelbadge">Answers: <?=$svar->Count?></span>
    <hr>
<?php endforeach ?>
<?php else : ?>
    <p> This user has not asked questions yet. </p>
<?php endif; ?>
<h1>Recent Answers</h1>
<hr>
<?php if (!empty($answer)) : ?>
<?php foreach ($answer as $answers): ?>
    <a href='<?=$this->url->create('questions/id/'.$answers->id)?>'><h3><?=$answers->title?></h3></a>
    <?=$answers->answer?>
    <?php foreach ($answers->svar as $svar): ?>
    
<?php endforeach ?>
    <em><?=$answers->added?></em>
    <br><span class="label label-success labelbadge">Answers: <?=$svar->Count?></span>
    <hr>
<?php endforeach ?>
<?php else : ?>
    <p> This user has not answers. </p>
<?php endif; ?>
<?php else : ?>
    <p> There is no user with such id. </p>
     <a href='<?=$this->url->create('')?>'><i class="fa fa-arrow-left"></i> Back</a></p>
<?php endif; ?>

            </div>
            
        </div>
    </div>




 