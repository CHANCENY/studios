<?php if(!empty($options)): ?>
<div class="row w-100 m-auto"><?php foreach ($options as $key=>$value): ?>
<div class="card bg-dark m-auto" style="width: 12rem;">
  <img src="<?php global $showImage;  echo !empty($value['epso_image']) ? $value['epso_image'] : $showImage; ?>" class="card-img-top" alt="<?php echo $value['title'] ?? null; ?>">
  <div class="card-body">
    <h5 class="card-title"><?php echo $value['title'] ?? null; ?></h5>
    <p class="card-text text-white-50"><?php $date = (new DateTime($value['air_date']))->format('m-d-Y'); echo 'Ep'.$value['epso_number']. ' ('.$date.')' ?? null; ?></p>
      <p class="card-text text-white-50"><?php echo $value['duration'].' min' ?? null; ?></p>
    <a href="watch?w=<?php echo $value['episode_uuid'] ?? null; ?>" class="btn btn-primary">Go Watch Now</a>
  </div>
</div>
<?php endforeach; ?></div>


<?php endif; ?>
