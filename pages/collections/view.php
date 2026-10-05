<link rel="stylesheet" href="pages/communities/style.css">
<script src="pages/collections/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item collection_name" aria-current="page">Data</li>
                <li class="breadcrumb-item lastItem active" aria-current="page">รายการล่าสุด</li>
            </ol>
        </nav>
    </div>
</div>
<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <input type="hidden" id="collection_id" value="<?php echo $_GET["collection"];?>">
            <h2 class="contact-title collection_name"></h2>
            <?php
                if (isset($_GET["community"])) {
                    $link = '&community='.$_GET["community"];
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h4><strong class="community">ขอบเขตเนื้อหา </strong><span id="community"> : </span> </h4>
            <?php
                } else {
                    $link = "";
            ?>
            <input type="hidden" id="community_id" value="">
            <?php        
                }
            ?>
            <hr>
            <h3 class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link lastItem active">รายการล่าสุด</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link year" href="?p=collection-date&collection=<?php echo $_GET["collection"].$link;?>">ปีที่เผยแพร่</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link authorLan" href="?p=collection-author&collection=<?php echo $_GET["collection"].$link;?>">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan" href="?p=collection-title&collection=<?php echo $_GET["collection"].$link;?>">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=collection-subject&collection=<?php echo $_GET["collection"].$link;?>">คำสำคัญ</a>
                </li>
            </ul>
            <p id="pagination_info"></p>
            <div id="item-data"></div>
            <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
        </div>
    </div>
</section>