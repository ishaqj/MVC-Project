  	<div class="col-md-4">
 	<div class="line">
 	 <div class="tags taglist">
 <?php if(isset($tags)) : ?>
        <h1>Most Used Tags</h1>
        <hr>
            <?php foreach ($tags as $tag) : ?>
            <a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><p><?=$tag->tag?> <span class="label label-success labelbadge"><?=$tag->tagCount?></span></p></a>
            <?php endforeach; ?>
            </div>

<?php else : ?>
    <p>There are no most used tags at the moment... <a href="<?=$this->url->create('questions/add')?>"><i class="fa fa-question"></i> ask</a> a question.</p>
<?php endif; ?>
</div></div></div>