<nav class="main-header navbar navbar-expand navbar-white navbar-light m-0 mb-3" style="margin-left: 0 !important;">
  <!-- Left navbar links -->
  <ul class="navbar-nav navbar__left">
    <li class="nav-item d-sm-inline-block">
      <a href="#" class="nav-link m-0 p-0">
        <p class="title_header"><strong></strong></p>
      </a>
    </li>
  </ul>

  <ul class="navbar-nav navbar__right ml-auto">
    <!-- Navbar Search -->
    <li class="nav-item" style="position: relative;">
      <a class="nav-link toggle_submenu" data-widget="fullscreen" role="button">
        <i class="fas fa-user  mr-2"></i>
        User Management
        <i class="fas fa-caret-down"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right show d-none" style="left: inherit; right: 0px;">

      <?php
        $menu = [
            ['user' , 'users', 'User', '<i class="fas fa-user mr-2"></i>'],
            ['userGroup' , 'userGroups', 'User Group', '<i class="nav-icon fas fa-sitemap mr-2"></i>'],
        ];

        foreach($menu as $row)
        {
            list($single, $plural, $name , $icon) = $row;
            preg_match('/^(\/admin\/'.$plural.')(|\/)$/', $this->path_current, $match);
            if( is_array($match) && count($match) ) echo '<a  href="'. $this->link_admin. $plural. '" class="nav-link active">';
            else
            {
                preg_match('/^(\/admin\/'.$single.')(|\/([0-9]*?))$/', $this->path_current, $match);
                if( is_array($match) && count($match) )  echo '<a  href="'. $this->link_admin. $plural. '" class="nav-link active">';
                else echo '<a  href="'. $this->link_admin. $plural. '" class="nav-link">';
            }
            echo $icon .' '. $this->txt($name).'</a>';
        }

      ?>
          <a href="<?php echo $this->logout_link; ?>" class="nav-link"><i class="fas fa-sign-out-alt mr-2"></i> Logout </a>
      </div>
    </li>
  </ul>
</nav>
