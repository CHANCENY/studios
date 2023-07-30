<?php
use GlobalsFunctions\Globals;
use Modules\Adventisement\Advertisement;

$cards = (new Advertisement())->getCards();

$images = [
        "Files/card.png",
    "Files/card2.png"
];

?>
<section class="container mt-lg-5 w-100">
    <hr>
    <p>To Create Advertisement content please make sure you have included the following requires</p>
    <ul>
        <li>HTML code with inline styles</li>
        <li>If your content requires data from database put some placeholders eg @movie-name@</li>
        <li>If not data required then make sure you have completed your advertisement.</li>
    </ul>
    <div class="row w-100">
        <div class="col-3 w-100">
            <form class="form-active" action="creating-select-action<?php echo '?destination='. Globals::url(); ?>" method="POST">
                <div class="form-group mt-lg-2">
                    <label>Card Name</label>
                    <input type="text" name="card_name" class="form-control">
                </div>
                <div class="form-group mt-lg-4">
                    <label for="editor">Editor</label>
                    <textarea name="content" class="form-control w-100" cols="50" rows="10"></textarea>
                </div>
                <div class="form-group mt-lg-4">
                    <label>Placeholders Used separated by /</label>
                    <input type="text" name="placeholders" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-light mt-lg-2" type="submit" value="cards" name="submit-cards">Create Now</button>
                </div>
            </form>
        </div>
        <div class="col-3 mt-lg-5 w-100">
            <hr>
            <div class="row w-100">
                <div class="accordion accordion-flush bg-dark" id="accordionFlushExample">
                    <div class="accordion-item bg-dark">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Advertisement Card Available.
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse bg-dark" data-bs-parent="#accordionFlushExample">
                           <div class="row m-auto mt-4 bg-dark"><?php if(!empty($cards)): ?><?php foreach ($cards as $key=>$value): ?>

                               <div class="card mx-2 bg-dark" style="width: 18rem;">
                                   <img src="<?php echo $images[random_int(0,1)];  ?>" class="card-img-top" alt="<?php echo $value['card_name'] ?? null; ?>">
                                   <div class="card-body">
                                       <h5 class="card-title"><?php echo $value['card_name'] ?? null; ?></h5>
                                       <a href="all-advertisement-cards?card=<?php echo $value['card_uuid'] ?? null; ?>" class="text-decoration-none">Advertise</a>
                                   </div>
                               </div><?php endforeach; endif; ?>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>