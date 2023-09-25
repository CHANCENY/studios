<?php
@session_start();
use GlobalsFunctions\Globals;
use Modules\Imports\CronManuallyRunner;

if(Globals::method() === "POST" && !empty(Globals::post("cron_runner")))
{
    $cronID = Globals::post('run_cron');
    if(is_numeric($cronID))
    {
        (new CronManuallyRunner(intval($cronID)));
    }
}

?>
<section class="container mt-lg-5">
    <div class="w-50 m-auto">
        <div>
            <p>
                These crons espicially the following need to be run in order listed below
            </p>
            <ul>
                <li>Imagecreation</li>
                <li>TransformImageLinks</li>
                <li>Imagestransfer</li>
                <li>Additionalcron</li>
            </ul>
            <p>
                These top listed cron need to be run every time you have uploaded new show, movie, episodes
                failing to do so will result to delay in publishing your content since these crons runs once a week on
                every tuesdays
            </p>
        </div>
        <div class="mt-lg-4">
            <form class="form" method="POST" action="<?php echo Globals::uri(); ?>">
                <div class="form-group mb-lg-4 float-end">
                    <button type="submit" class="btn btn-outline-light mt-4" name="cron_runner" value="runs">Submit Cron To Run</button>
                </div>
                <div class="form-group">
                    <label for="run-cron">
                        Select cron to run here
                    </label>
                    <select id="run-cron" name="run_cron" class="form-control form-select">
                        <option value="">--Choose Cron--</option>
                        <?php $crons = (new CronManuallyRunner())->crons(); ?>
                        <?php foreach ($crons as $key=>$value): ?>
                          <option value="<?php echo $value['id'] ?? null; ?>"><?php echo $value['name'] ?? $value['location'] ?? null; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
</section>
