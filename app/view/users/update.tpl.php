<h1>Update Profile</h1>
<hr>
<? if (isset($user) && is_object($user)): ?>

        <?php $properties = $user->getProperties(); ?>
        <p> <?=$content?> </p>
        

<? else : ?>


<? endif; ?>
 
<p><a href='<?=$this->url->create('users/id/'.$properties['id'])?>'><i class="fa fa-arrow-left"></i> Back</a></p>