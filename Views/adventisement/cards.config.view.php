<?php use Alerts\Alerts;
use Datainterface\mysql\TablesLayer;
use Datainterface\Query;
use GlobalsFunctions\Globals;
use Modules\Adventisement\Advertisement;
use Sessions\SessionManager;

@session_start();

if(Globals::method() === "POST" && !empty(Globals::post("add-card-now")))
{
    $tempData = SessionManager::getSession("card-session") ?? [];
    $placeHolders = $tempData['placeholders'] ;
    $mapped = [];

    $columns = "";
    foreach ($placeHolders as $key=>$value){
        $mapped[$value] = Globals::post($value);
        $columns .= Globals::post($value).", ";
    }

    $columns = substr($columns, 0, strlen($columns) - 2);
    $id = Globals::post("id");
    $source = Globals::post("datasource");
    $columnID = SessionManager::getSession("id-column");
    $query = "SELECT $columns FROM $source WHERE $columnID = :id";

    $result = Query::query($query, ["id"=>$id]);
    if(!empty($result)){
        $finalCopy = [];
      foreach ($mapped as $item=>$value){
          $finalCopy[$item] = $result[0][$value] ?? null;
      }

      if(!empty($finalCopy) && count($finalCopy) === count($mapped)){
          $output = (new Advertisement())->create($tempData['name'], $tempData['html'], $finalCopy);
          if($output){
              SessionManager::clearSession("card-session");
              SessionManager::clearSession("id-column");
              Globals::redirect(Globals::get("destination"));
              exit;
          }
      }else{
          echo Alerts::alert("warning", "Failed to map data correctly.");
      }
    }else{
        echo Alerts::alert("danger", "Your ID result empty results please use Different ID column used ($columnID)");
    }

}


if(!empty(Globals::post("content")))
{
    $content = [
            "placeholders"=>explode('/', Globals::post("placeholders")),
        "html"=>$_POST['content'],
        "name"=>Globals::post("card_name")
    ];
    SessionManager::setSession("card-session", $content);

}

$tempData = SessionManager::getSession("card-session");

$selected = null;
$fields = "";

$ids = [];
$allColumns = [];
if(Globals::method() === "GET" && !empty(Globals::get('t'))){
    $t = Globals::get("t");
    $selected = "<input type='text' name='datasource' value='$t' id='datasource' readonly class='form-control'>";

    $tables = (new TablesLayer())->getSchemas()->schema();
    $sourceSchema = $tables[$t];
    foreach ($sourceSchema as $key=>$value){
        if(isset($value['Key']) && !empty($value['Key']) && isset($value['Extra']) && !empty($value['Extra']))
        {
            SessionManager::setSession("id-column", $value['Field']);
        }
        $name = str_replace("_"," ", $value['Field']);
        $name = ucfirst($name);
        $fields .= "<option value='{$value['Field']}'>$name</option>";
    }

    $ids = \Datainterface\Selection::selectAll($t);
    $allColumns = array_keys($ids[0] ?? []);
}

if(empty($selected)){
   $tables = (new TablesLayer())->getTables()->tables();
   $selected = "<select class='form-select' name='datasource' id='datasource' required>";
   foreach ($tables as $key=>$value){
       $selected .= "<option value='$value'>$value</option>";
   }
   $selected .="</select>";
}
?>
<section class="container mt-lg-5" id="destination" data="<?php echo Globals::get('destination') ?? null; ?>">
    <hr>
    <div class="mt-lg-2 m-auto w-75">
        <form class="form" action="<?php echo Globals::uri(); ?>" method="POST">
            <div class="form-group">
                <label for="datasource">Data Source</label><?php  echo $selected; ?>
            </div>
            <div class="form-group border rounded mt-lg-4">
                <label for="datasource">Column/Placeholder Mapper</label><?php if(!empty($tempData["placeholders"])): ?>
                <?php foreach ($tempData['placeholders'] as $key=>$value): ?><select class="form-select mt-2 pe-auto" name="<?php echo $value; ?>" required>
                    <option>Map for <?php echo $value; ?></option><?php echo $fields; ?>
                </select><?php endforeach; ?>
            <?php endif; ?></div>
            <div class="form-group mt-lg-4">
                <label for="idsource">ID</label>
                <input type="number" name="id" class="form-control" required>
                <span>You dont know the ID of content you want then sign in as Admin and use Admin search bar to finf the ID of content</span>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-light mt-lg-4" type="submit" name="add-card-now" value="adding">Save Now</button>
            </div>
        </form>
    </div>
</section>


<div class="mt-lg-5 accordion accordion-flush" id="accordionFlushExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Data To Pick From (ID). This data is coming from data source you entered
            </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
           <table class="table">
               <thead>
               <tr>
                  <?php if(!empty($allColumns)): ?>
                      <?php foreach ($allColumns as $key=>$value): ?>
                        <?php echo "<th>$value</th>"; ?>
                      <?php endforeach; ?>
                  <?php endif; ?>
               </tr>
               </thead>
               <tbody>
                 <?php if(!empty($ids)): ?>
                   <?php foreach ($ids as $key=>$value): ?>
                     <tr>
                         <?php for ($i = 0; $i < count($value); $i++): ?>
                          <?php echo "<td>{$value[$allColumns[$i]]}</td>"; ?>
                         <?php endfor; ?>
                     </tr>
                   <?php endforeach; ?>
                 <?php endif; ?>
               </tbody>
           </table>
        </div>
    </div>
</div>




















<div>
    <script type="application/javascript">
        document.getElementById("datasource").addEventListener("change", (e)=>{
            const value = e.target.value;
            const des = document.getElementById("destination").getAttribute("data");
            const params = new URLSearchParams({t:value, destination: des});
            const url = "creating-select-action?"+params;
            window.location.replace(url);
        })
    </script>
</div>
