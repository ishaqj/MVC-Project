<div class="col-md-4">
<div class="line">
<?php if(isset($users)) : ?>

        <h1>Most Active Users</h1>
        <hr>
        <div class="tags taglist">
            <?php foreach ($users as $user) : ?>
            <a href="<?=$this->url->create('users/id/' . $user->uid) ?>"><p><?=$user->username?></a> <span class="label label-primary labelbadge"><?=$user->count?></span></p>
            <?php endforeach; ?>
        </div>
<br>

<?php else : ?>
    <p>There are no registered users yet. <a href="<?=$this->url->create('users/register')?>"><i class="fa fa-key"></i> Register</a> Account.</p>
<?php endif; ?>
</div></div>
