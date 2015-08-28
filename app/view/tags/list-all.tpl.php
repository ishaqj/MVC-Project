<h1><?=$header?></h1>
<hr>
<?php if($tags != null) : ?>
<div class="row">
<div class="col-md-6">
<div class="ltags">
<div class="tags taglist">
   <?php  foreach ($tags as $tag) : ?>
  
            <a href="<?=$this->url->create('tags/tag/' . $tag->tag) ?>" class="tag"><?=$tag->tag?> <span class="label label-success labelbadge"> <?=$tag->count?> </span></a> 
       

        <?php endforeach; ?>
        </div>
</div>
<?php else : ?>
    <p>There are no available tags but you can <a href="<?=$this->url->create('questions/add')?>"><i class="fa fa-question"></i> ask</a> a question, and create some tags.</p>
<?php endif; ?>
</div></div>