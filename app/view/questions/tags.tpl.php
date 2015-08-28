<h1><?=$header?></h1>
<hr>
<div class="row">
<div class="col-md-12">


<?php if (!empty($questions)) : ?>
<?php foreach ($questions as $question) : ?>
<?php $gravatar = 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($question->email))) . '.jpg?s=60'; ?>

        <a href="<?=$this->url->create('questions/id/' . $question->id) ?>"><h3><?=$question->title?></h3></a>
        <?=$question->content?>
        <br><a href="<?=$this->url->create('users/id/' . $question->userid) ?>"><img src = "<?=$gravatar?>"> <?=$question->username?></a> - <em><?=$question->created?></em>

        <div class="tags taglist">
            <?php foreach ($question->tags as $tag) : ?>
            <a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><?=$tag->tag?></a>
            <?php endforeach; ?>
        </div>
        <hr>
    <?php endforeach; ?>

<?php else : ?>

<?php endif; ?>
</div></div>
