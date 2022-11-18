<?php
    $this->theme->add( $this->url. 'assets/css/select2.min.css', '', 'select_css');
    $this->theme->add( $this->url. 'assets/css/select2_custom.css', '', 'select2_custom_css');
    $this->theme->add( $this->url. 'assets/font/fontawesome/css/fontawesome.min.css', '', 'fontawesome_css');
    $this->theme->add( $this->url. 'assets/font/fontawesome/css/all.css', '', 'all_css');
    $this->theme->add( $this->url. 'assets/css/bootstrap5.min.css', '', 'bootstrap_min_css');
    $this->theme->add( $this->url. 'assets/sitemap/css/adminlte.min.css', '', 'adminlte_min_css');
    $this->theme->add( $this->url. 'assets/sitemap/css/blocks.css', '', 'blocks_css');

    $this->theme->add( $this->url. 'assets/js/jquery-3.6.0.min.js', '', 'jquery' , 'top');
    $this->theme->add( $this->url. 'assets/js/select2.full.min.js', '', 'select_js' , 'top');
    $this->theme->add( $this->url. 'assets/js/bootstrap.bundle.min.js', '', 'bootstrap');
    $this->theme->add( $this->url. 'assets/sitemap/js/main.js', '', 'main_js' , '');
?>
<!-- Main content -->
<section class="content p-0">
    <!-- Default box -->
    <div class="card no_shadow">
        <div class="card-header">
            <article id="post-598"
                class="post-598 post type-post status-publish format-standard hentry category-sitemap-app">
                <div class="entry-content">
                    <div class="main_navbar topHeader d-flex align-items-center flex-wrap mb-3 px-1">
                        <p class="title_header m-0 px-1 "><strong><?php $this->echo('Sitemap Management'); ?></strong></p>
                    </div>
                    <div class="row px-2 m-0">
                        <?php $this->render('message') ?>
                    </div>
                    <div class="wp-block-columns alignwide row m-0">
                        <div class="col-12">
                            <div class="mb-3">
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                        <div>
                                            <!-- fiter -->
                                            <?php $this->render('backend.sitemap.list.filter') ;?>
                                        </div>
                                        <div class="row m-0 list-view">
                                            <div
                                                class="col-sm-12 table-responsive table-responsive-md tbl_respon p-0">
                                                <table
                                                    class="table table-bordered table-striped  table-hover dataTable dtr-inline tb-min-550 tableFixHead"
                                                    role="grid" aria-describedby="example1_info">
                                                    <thead>
                                                        <tr class="">
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Title</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Plugin</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Slug</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Permission</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Object</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Object ID</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Published</th>
                                                            <th class="sorting no-wrap" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                                Language</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="listMedia">
                                                        <!-- get row -->
                                                        <?php while($this->list->hasRow()) $this->render('backend.sitemap.list.row'); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- pagination -->
                                        <div class="row m-0 list-view m-0">
                                            <?php $this->render('paginationSitemap') ;?>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
    <!-- /.card -->

</section>
<!-- /.content -->
<?php $this->render('backend.sitemap.list.javascript') ;?>