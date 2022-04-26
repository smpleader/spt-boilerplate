<?php
/**
 * SPT software - Layout html
 *
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Display a layout
 *
 */

if(!$this->total) return;

if($this->totalPage > 4)
{

    if($this->page == 1)
    {
        $array = [1, 2, 0];
    }
    elseif($this->page == 2)
    {
        $array = [1, 2, 3, 0];
    }
    elseif($this->page == ($this->totalPage - 1))
    {
        $array = [0, $this->page-1, $this->page, $this->totalPage];
    }
    elseif($this->page == $this->totalPage)
    {
        $array = [0, $this->page-1, $this->page];
    }
    else
    {
        $array = [0, $this->page-1, $this->page, $this->page+1, 0];
    }
}
else
{
    $array = range(1, $this->totalPage);
}

?>
<div class="col-sm-12 col-md-5 center-768 d-flex align-items-center pl-0">
    <div class="dataTables_info" id="example1_info"
        role="status" aria-live="polite">Showing <?php echo $this->start + 1;
        ?> to
        <span id="number_entrie"><?php echo ($this->page)*$this->limit <= $this->total ? ($this->page)*$this->limit : $this->total; ?></span>
        of <span id="total_entrie"><?php echo $this->total; ?></span> entries</div>
    </div>
<div class="col-sm-12 col-md-7 center-768 p-0">
    <div class="dataTables_paginate paging_simple_numbers ">
        <ul class="pagination d-flex justify-content-end mg-0">
            <li style="margin: 0;" class="page-item <?php echo ($this->page == 1)? 'disabled':''; ?>">
                <a class="page-link" href="<?php echo $this->path_current. '?page=1'?>">First</a>
            </li>
            <li style="margin: 0;" class="page-item <?php echo ($this->page == 1)? 'disabled':''; ?>">
                <a class="page-link" href="<?php echo $this->path_current. '?page='. ($this->page-1);?>">Previous</a>
            </li>
            <?php foreach( $array as $p) {
                $class = '';
                if(0 === $p)
                {
                    $class = 'disabled';
                }
                elseif($this->page == $p)
                {
                    $class = 'active';
                }  ?>
            <li style="margin: 0;" class="page-item hide_576 <?php echo $class;?>">
                <a class="page-link " href="<?php echo $this->path_current. '?page='. $p;?>">
                    <?php echo 0 === $p ? '...' : $p?>
                </a>
            </li>
            <?php }?>
            <li style="margin: 0;" class="page-item  <?php echo ($this->page == $this->totalPage)? 'disabled':''  ?>">
                <a class="page-link" href="<?php echo $this->path_current. '?page='. ($this->page+1);?>">Next</a>
            </li>
            <li style="margin: 0;" class="page-item  <?php echo ($this->page == $this->totalPage)? 'disabled':''  ?>">
                <a class="page-link" href="<?php echo $this->path_current. '?page='. $this->totalPage;?>">Last</a>
            </li>
        </ul>
    </div>
</div>
