<h1><?=$title?></h1>
<hr>
<?php if (isset($question) && is_object($question)): ?>

<?php $properties = $question->getProperties(); ?>

<div class="row"><div class="col-md-1"><span class="label label-primary labelbadge"><?=$qVotes?></span> <b>Votes</b>
<a href="<?=$this->url->create('questions/vote/'.$properties['questionid'].'/upvote/')?>"<i class="fa fa-arrow-up fa-lg"></i></a>
<a href="<?=$this->url->create('questions/vote/'.$properties['questionid'].'/downvote/')?>"<i class="fa fa-arrow-down fa-lg"></i></a></div><div class="col-md-11"><?=$properties['content']?></div></div>
<div class="row"><div class="col-md-12 col-md-offset-1">
<a href="<?=$this->url->create('users/id/' . $properties['id']) ?>"><?=$properties['username']?></a> - <em><?=$properties['qcreated']?></em></div></div>
<div class="row"><div class="col-md-5 col-md-offset-1">
<div class="tags taglist">
<?php foreach ($tags as  $tag) : ?>
<a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
<?php endforeach; ?>
</div>
</div>
<br>
<div class="col-md-3"><a href="<?=$this->url->create('comments/add/1/' . $properties['questionid']) ?>"  class="fa fa-comment-o"><b> Add Comment</b></a></div></div>
<hr>
<div class="row">
<div class="col-md-11 col-md-offset-1">
	<?php foreach ($comments as  $comment) : ?>
<div class="comments">
<?=$comment->comment?> 
<a href="<?=$this->url->create('users/id/' . $comment->id) ?>"><?=$comment->username?></a> - <em><?=$comment->added?></em></div>
<?php endforeach; ?>
</div></div>

<h1><?=$totalanswers?> Answers:</h1>
  <hr>
<?php foreach ($answers as  $answer) : ?>
    <?php $answerArray = array(); ?>
  <?php foreach ($answer->svar as $value): ?>
  
<div class="row"><div class="col-md-1"><span class="label label-primary labelbadge"><?=$value->Count?></span><b> Votes</b> 
<?php endforeach ?>
<br><a href="<?=$this->url->create('questions/voteAns/'.$answer->answerID.'/upvote/')?>"<i class="fa fa-arrow-up fa-lg"></i></a>
<a href="<?=$this->url->create('questions/voteAns/'.$answer->answerID.'/downvote/')?>"<i class="fa fa-arrow-down fa-lg"></i></a></div>
<div class="col-md-11"><?=$answer->answer?></div></div>
<div class="row">
<div class="col-md-5 col-md-offset-1">
<a href="<?=$this->url->create('users/id/' . $answer->userid) ?>"><?=$answer->username?></a> - <em><?=$answer->added?></em></div>
<div class="col-md-5 col-md-offset-6"><a href="<?=$this->url->create('comments/add/2/' . $properties['questionid'] . '/' . $answer->answerID) ?>" class="fa fa-comment-o"><b> Add Comment</b></a></div></div>
<hr>
<div class="row">
<div class="col-md-11 col-md-offset-1">

<?php foreach($answercomments as $anscomment) : ?>
<?php if($anscomment->caID == $answer->answerID) : ?>

<?php $answerArray[] = $anscomment; ?>

<?php endif; ?>
<?php endforeach; ?>

<?php $answer->ans = $answerArray; ?>

<?php foreach($answer->ans as $svar) : ?>

<div class="comments">
<?=$svar->comment?>
<a href="<?=$this->url->create('users/id/' . $svar->userid) ?>"><?=$svar->username?></a> - <em><?=$svar->added?></em>
</div>

<?php endforeach; ?>
</div></div><br>
<?php endforeach; ?>
<div class="row">
<div class="col-md-12">
<?php if($loggedIn): ?>

 <div class="form-group"> <?=$content?> </div>
<?php else : ?>

        <p style="text-align: center;">
            <b>You must be logged in to post your answer</b><br>
            <a href="<?=$this->url->create('users/login') ?>"><i class="fa fa-sign-in"></i> Login</a> or 
            <a href="<?=$this->url->create('users/register') ?>"><i class="fa fa-pencil-square-o"></i> Register</a>.
        </p>
 

<?php endif; ?>
<?php endif; ?>
</div>
</div>
 