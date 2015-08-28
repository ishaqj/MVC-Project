<div class="row">
<div class="col-md-4">
<div class="line">
<h1>Latest Questions</h1>
<hr>
<?php if (!empty($questions)) : ?>
<?php foreach ($questions as $question) : 
?>

<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->email))) . '.jpg?s=60'; ?>

        <a href="<?=$this->url->create('questions/id/' . $question->qid) ?>"><h3><?=$question->title?></h3></a>

        <?php if(strlen($question->content) > 30) : ?>
            <p><?=substr($question->content,0, 32) . '...' ?></p>
        <?php else : ?>
            <?=$question->content?>
        <?php endif; ?>
        Asked by <a href="<?=$this->url->create('users/id/' . $question->userid) ?>" ><?=$question->username?></a> - <em><?=$question->qcreated?></em>
        <br>
        <?php if(!$question->svar) : ?>
        <span class="label label-success labelbadge">Answers: 0</span> </b><br>
        <?php else : ?>
        <?php foreach ($question->svar as $svar): ?>
        <span class="label label-success labelbadge">Answers: <?=$svar->Count ?></span></b><br>
        <?php endforeach ?>
        <?php endif; ?>
            

        <div class="tags taglist">
            <?php foreach ($question->tags as $tag) : ?>
            <a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
            <?php endforeach; ?>
            <hr>
        </div>
        <?php endforeach; ?>
        <?php elseif(!$sql) : ?>
    <p><h1>Welcome!</h1> Click <a href="<?=$this->url->create('setup')?>">Here</a> to get started!</p>
    <?php else :?>
    <p>No questions have been added... <a href="<?=$this->url->create('questions/add')?>"><i class="fa fa-question"></i> Ask</a> a question.</p>
    <?php endif; ?>


</div>
</div>