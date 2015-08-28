<?php if(isset($login)) : ?>
	<div class="row">
	<div class="col-md-6 col-md-offset-1">
	<div class="panel panel-default" style="margin-top: 20px;">
  	<div class="panel-heading">
	<h3 class="panel-title"><?=$header?></h3>
 	</div>
  	<div class="panel-body">
  	<?=$login?>
  	</div>
  	</div>
  	</div>
 
 <div class="col-md-3">
<h1>Sign up</h1>
<hr>
<p>Not a member yet? Sign up  <a href="<?=$this->url->create('users/register/') ?>"> here.</a></p>
</div>
</div>
<?php elseif(isset($register)) :?>
	<div class="row">
	<div class="col-md-6 col-md-offset-1">
	<div class="panel panel-default" style="margin-top: 20px;">
  	<div class="panel-heading">
	<h3 class="panel-title"><?=$header?></h3>
 	</div>
  	<div class="panel-body">
  	<?=$register?>
  	</div>
  	</div>
  	</div>
  	</div>

<?php else : ?>
	<p>Something went wrong</p>
<?php endif; ?>



