<div class="row">
<div class="col-md-6">
<h1>Latest Questions</h1>
</div>
<div class="col-md-3 col-md-offset-2 label label-primary labelbadge" style="margin-top: 10px;" >
<a href="<?=$this->url->create('questions/add') ?>"><span><h3><i class="fa fa-plus"></i> Ask Question</span></h3></a>
</div>
</div>
<hr>
<div class="row">
<div class="col-md-12">
<?php if (!empty($questions)) : ?>
<?php foreach ($questions as $question) : ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->email))) . '.jpg?s=60'; ?>

        <a href="<?=$this->url->create('questions/id/' . $question->qid) ?>"><h3><?=$question->title?></h3></a>
        <?=$question->content?>
        <br><a href="<?=$this->url->create('users/id/' . $question->userid) ?>"><img src = "<?=$gravatar?>"> <?=$question->username?></a> - <em><?=$question->qcreated?></em>
            <?php if(!$question->svar) : ?>
            <br><span class="label label-success labelbadge">Answers: 0</span> <br>
            <?php else : ?>
            <?php foreach ($question->svar as $svar): ?>
            <br> <span class="label label-success labelbadge">Answers: <?=$svar->Count ?></span>
            <?php endforeach ?>
            <?php endif; ?>

        <div class="tags taglist fl">
            <?php foreach ($question->tags as $tag) : ?>
            <a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
            <?php endforeach; ?>
        </div>
<hr>

        <?php endforeach; ?>

<?php else : ?>
    <p>No questions have been added... <a href="<?=$this->url->create('questions/add')?>"><i class="fa fa-question"></i> Ask</a> a question.</p>
<?php endif; ?>
</div></div>