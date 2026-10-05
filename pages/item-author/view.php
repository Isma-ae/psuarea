<link rel="stylesheet" href="pages/item-author/style.css">
<script src="pages/item-author/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item active authorLan" aria-current="page">ผู้แต่ง</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <?php
                if (!isset($_GET["community"])) {
                    $com_link = "";
            ?>
            <input type="hidden" id="community_id" value="">
            <h2 class="contact-title showByAuthor">กำลังเรียกดู โดย ผู้แต่ง</h2>
            <?php
                } else {
                    $com_link = "&community=".$_GET["community"]."";
            ?>
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h2 class="contact-title" id="title"></h2>
            <div id="img"></div>
            <hr>
            <h3 class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link collection" href="?p=communities&community=<?php echo $_GET["community"];?>">คอลเล็กชัน</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link year" href="?p=dateissued&community=<?php echo $_GET["community"];?>">ปีที่เผยแพร่</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link authorLan active">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan" href="?p=title&community=<?php echo $_GET["community"];?>">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=subject&community=<?php echo $_GET["community"];?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <?php
                }
                $author = (isset($_GET["author_name"])) ? $_GET["author_name"] : "" ;
            ?>
            <div class="search_widget">
                <div class="input-group mb-3 search_input">
                    <input type="text" class="form-control authorPlace" id="author_name" placeholder="กรองผลลัพธ์โดยพิมพ์ชื่อผู็เขียน..." value="<?= $author;?>">
                    <div class="input-group-append">
                        <button class="btn showButton" type="button" id="search-by-author"><i class="ti-book"></i> เรียกดู</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="my_section_padding2">
    <div class="container">
        <a href="?p=author<?= $com_link;?>" class="genric-btn primary medium back">ย้อนกลับ</a>
        <p id="pagination_info"></p>
        <div id="item-data"></div>
        <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
    </div>
</section>