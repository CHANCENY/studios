<?php
use Modules\Episodes\Episode;

if(!empty($options)): ?>
    <div class="row m-auto justify-content-center my-movies"><?php foreach ($options as $key=>$value): ?>
            <div class="card bg-dark mx-1 mt-3" style="width: 18rem;">
                <p class="card-text text-white-50"><?php echo (new Episode())->getEpisodeShowTitle($value['season_id']); ?></p>
                <a href="watch?w=<?php echo $value['episode_uuid'] ?? null; ?>"><img src="<?php global $showImage;  echo !empty($value['epso_image']) ? $value['epso_image'] : $showImage; ?>" class="card-img-top zoom" alt="<?php echo $value['title'] ?? null; ?>"></a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo substr($value['title'],0, 11).'..' ?? null; ?></h5>
                    <p class="card-text text-white-50"><?php $date = (new DateTime($value['air_date']))->format('m-d-Y'); echo 'Ep'.$value['epso_number']. ' ('.$date.')' ?? null; ?></p>
                    <p class="card-text text-white-50"><?php echo $value['duration'].' min' ?? null; ?></p>
                </div>
            </div>
        <?php endforeach; ?></div>
<?php endif; ?>