<form action="<?php echo $this->link_sitemap_list; ?>" method="post" class="filterForm">

        <div class="row mb-3 border rounded m-0 d-flex">
            <div class="col-sm-12 col-md-12 d-flex justify-content-between wrap_740">
                <div class="dt-buttons btn-group flex-wrap align-items-center">
                    <div class="mr-2 py-2 order-2-475" style="width: 150px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter by plugin">
                        <?php $this->field('plugin'); ?>
                    </div>

                    <div class="dropdown mr-2 py-2 w_full_475">
                        <?php $this->field('search'); ?>    
                    </div>
                    <div class="d-flex order-2-475">
                    <div class="dropdown mr-2 py-2">
                        <button class="btn infor-btn input_common fz-16"
                            type="submit" aria-expanded="false"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Apply filter">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <div class="dropdown py-2 mr-2">
                        <button class="btn infor-btn input_common fz-16"
                            type="button" aria-expanded="false"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            id="clear_filter"
                            title="Clear filter">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                </div>
                <div class="d-flex">
                    <div class="mr-2 py-2" style="width: 130px;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Filter by status">
                        <?php $this->field('published'); ?>
                    </div>
                    <div class="py-2" style="width: 100px;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Limit record">
                        <?php $this->field('limit'); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>