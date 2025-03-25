<link rel="stylesheet" href="pages/community-collection/style.css">
<script src="pages/community-collection/script.js"></script>
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
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <input type="hidden" id="collection_id" value="<?php echo $_GET["collection"];?>">
            <?php
                    $sql = "SELECT * FROM tb_community WHERE MD5(community_id) = '".$_GET["community"]."'";
                    $obj = $DATABASE->QueryObj($sql);
            ?>
            <h2 class="contact-title" id="community_title"><?= $obj[0]["community_title"];?></h2>
            <div id="community_img"><img src="files/community/<?= $obj[0]["community_img"];?>" width="25%"></div>
            <hr>
            <h3>เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link active">ชุมชนย่อยและคอลเลคชัน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=dateissued&community=<?php echo $_GET["community"];?>">ตามวันที่ออก</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link" href="?p=author&community=<?php echo $_GET["community"];?>">โดยผู้เขียน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=title&community=<?php echo $_GET["community"];?>">ตามชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=subject&community=<?php echo $_GET["community"];?>">ตามหัวเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link" href="?p=srsc&community=<?php echo $_GET["community"];?>">ตามหมวดหมู่หัวเรื่อง</a>
                </li>
            </ul>
            <br>
            <a href="?p=communities&community=<?php echo $_GET["community"];?>" class="genric-btn primary medium">ย้อนกลับ</a>
            <p id="pagination_info"></p>
            <div id="item-data"></div>
            <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
        </div>
    </div>
</section>