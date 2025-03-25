<link rel="stylesheet" href="pages/communities/style.css">
<script src="pages/collections/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data</li>
            </ol>
        </nav>
    </div>
</div>
<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <?php
                $sql = "SELECT * FROM tb_collection WHERE MD5(collection_id) = '".$_GET["collection"]."'";
                $obj = $DATABASE->QueryObj($sql);
            ?>
            <input type="hidden" id="collection_id" value="<?php echo $_GET["collection"];?>">
            <h2 class="contact-title" id="collection_name"><?= $obj[0]["collection_name"];?></h2>
            <?php
                if (isset($_GET["community"])) {
                    $link = '&community='.$_GET["community"];
                    $sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '".$_GET["community"]."'";
                    $obj = $DATABASE->QueryObj($sql);
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h4 id="community_title">ชุมชน : <?= $obj[0]["community_title"];?></h4>
            <?php
                } else {
                    $link = "";
            ?>
            <input type="hidden" id="community_id" value="">
            <?php        
                }
            ?>
            <hr>
            <h3>เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link active">รายการล่าสุด</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=collection-date&collection=<?php echo $_GET["collection"].$link;?>">ตามวันที่ออก</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=collection-author&collection=<?php echo $_GET["collection"].$link;?>">โดยผู้เขียน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=collection-title&collection=<?php echo $_GET["collection"].$link;?>">ตามชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=collection-subject&collection=<?php echo $_GET["collection"].$link;?>">ตามหัวเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=srsc&collection=<?php echo $_GET["collection"].$link;?>">ตามหมวดหมู่หัวเรื่อง</a>
                </li>
            </ul>
            <p id="pagination_info"></p>
            <div id="item-data"></div>
            <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
        </div>
    </div>
</section>