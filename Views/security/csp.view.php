<?php use GlobalsFunctions\Globals;
use Modules\Security\CSP;

@session_start();

$old = (new CSP())->cspGet();
$_SESSION['csp'] = $old['defaultKey'] ?? null;

if(Globals::method() === "POST" && !empty(Globals::post('csp')))
{
    $values = Globals::post('values');
    if(!empty($_SESSION['csp'])){
        if((new CSP())->cspUpdate(['values'=>$values],$_SESSION['csp']))
        {
            echo \Alerts\Alerts::alert('warning', "Updated CSP values");
        }
    }else{
        if(empty($values)){
            if((new CSP())->remove())
            {
                echo \Alerts\Alerts::alert('danger', "Delete CSP values");
            }
        }else{
            if((new CSP())->cspSave(['values'=>$values]))
            {
                echo \Alerts\Alerts::alert('info', "Save CSP values");
            }
        }
    }
    unset($_SESSION['csp']);
}
$old = (new CSP())->cspGet();
?>
<section class="container mt-lg-5 w-100">
    <div class="m-auto w-75">
        <form class="form" method="POST" action="<?php echo Globals::uri(); ?>">
            <div class="form-group">
                <label for="values">
                    <textarea class="form-control" name="values" cols="100" rows="10"><?php echo $old['values'] ?? null; ?></textarea>
                </label>
            </div>
            <div class="form-group">
                <button name="csp" type="submit" value="csp" class="btn btn-secondary bg-primary">CSP SAVE</button>
            </div>
        </form>
    </div>
</section>
