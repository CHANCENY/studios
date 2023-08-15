<?php

 $genres = (new \Modules\Modals\Filters())->genres();

?>

<!-- filter -->
<div class="filter">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="filter__content">
                    <div class="filter__items">
                        <!-- filter item -->
                        <div class="filter__item" id="filter__genre">
                            <span class="filter__item-label">GENRE:</span>

                            <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-genre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <input type="button" id="genre" value="Action/Adventure">
                                <span></span>
                            </div>

                            <ul class="filter__item-menu dropdown-menu scrollbar-dropdown" aria-labelledby="filter-genre">
                                <li>Action/Adventure</li><?php if(!empty($genres)): foreach ($genres as $key=>$value): ?>
                                <li><?php echo $value; ?></li>
                            <?php endforeach; endif; ?></ul>
                        </div>
                        <!-- end filter item -->

                        <!-- filter item -->
                        <div class="filter__item" id="filter__rate">
                            <span class="filter__item-label">Rating:</span>

                            <div class="filter__item-btn dropdown-toggle" role="button" id="filter-rate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="filter__range">
                                    <div id="filter__imbd-start" id="start-rate"></div>
                                    <div id="filter__imbd-end" id="end-rate"></div>
                                </div>
                                <span></span>
                            </div>

                            <div class="filter__item-menu filter__item-menu--range dropdown-menu" aria-labelledby="filter-rate">
                                <div id="filter__imbd"></div>
                            </div>
                        </div>
                        <!-- end filter item -->

                        <!-- filter item -->
                        <div class="filter__item" id="filter__year">
                            <span class="filter__item-label">RELEASE YEAR:</span>

                            <div class="filter__item-btn dropdown-toggle" role="button" id="filter-year" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="filter__range">
                                    <div id="filter__years-start" id="start-yrs"></div>
                                    <div id="filter__years-end" id="end-yrs"></div>
                                </div>
                                <span></span>
                            </div>

                            <div class="filter__item-menu filter__item-menu--range dropdown-menu" aria-labelledby="filter-year">
                                <div id="filter__years"></div>
                            </div>
                        </div>
                        <!-- end filter item -->
                    </div>

                    <!-- filter btn -->
                    <button class="filter__btn" id="filter-butn" type="button">apply filter</button>
                    <!-- end filter btn -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end filter -->
<script src="assets/main/js/filters.js"></script>