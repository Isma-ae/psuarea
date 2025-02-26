<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item <?php echo ''.(($page=="home")?'active':"").'';?>">
                    <a class="sidebar-link sidebar-link <?php echo ''.(($page=="home")?'active':"").'';?>" href="./" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">จัดการข้อมูล</span></li>
                <li class="sidebar-item <?php echo ''.(($page=="home-page")?'active':"").'';?>">
                    <a class="sidebar-link sidebar-link <?php echo ''.(($page=="home-page")?'active':"").'';?>" href="?p=home-page" aria-expanded="false">
                        <i data-feather="sidebar" class="feather-icon"></i>
                        <span class="hide-menu">ข้อมูลหน้าแรก </span>
                    </a>
                </li>
                <li class="sidebar-item <?php echo ''.(($page=="community" || $page=="collection" || $page=="item-list" || $page=="item-add")?'selected':"").'';?>">
                    <a class="sidebar-link has-arrow <?php echo ''.(($page=="community" || $page=="collection" || $page=="item-list" || $page=="item-add")?'active':"").'';?>" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">ชุมชน </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level base-level-line <?php echo ''.(($page=="community" || $page=="collection" || $page=="item-list" || $page=="item-add")?'in':"").'';?>">
                        <li class="sidebar-item <?php echo ''.(($page=="community")?'active':"").'';?>">
                            <a href="?p=community" class="sidebar-link <?php echo ''.(($page=="community")?'active':"").'';?>">
                                <span class="hide-menu"> ชุมชน</span>
                            </a>
                        </li>
                        <li class="sidebar-item <?php echo ''.(($page=="collection")?'active':"").'';?>">
                            <a href="?p=collection" class="sidebar-link <?php echo ''.(($page=="collection")?'active':"").'';?>">
                                <span class="hide-menu"> คอลเลกชัน </span>
                            </a>
                        </li>
                        <li class="sidebar-item <?php echo ''.(($page=="item-list" || $page=="item-add")?'active':"").'';?>">
                            <a href="?p=item-list" class="sidebar-link <?php echo ''.(($page=="item-list" || $page=="item-add")?'active':"").'';?>">
                                <span class="hide-menu"> รายการ </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="ui-cards.html"
                        aria-expanded="false"><i data-feather="sidebar" class="feather-icon"></i><span
                            class="hide-menu">Cards
                        </span></a>
                </li>
            </ul>
        </nav>
    </div>
</aside>