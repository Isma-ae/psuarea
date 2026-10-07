<link rel="stylesheet" href="pages/title/style.css?v=<?= file_exists(__DIR__ . '/style.css') ? filemtime(__DIR__ . '/style.css') : time(); ?>">
<script src="pages/title/script.js?v=<?= file_exists(__DIR__ . '/script.js') ? filemtime(__DIR__ . '/script.js') : time(); ?>"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item active titleLan" aria-current="page">ชื่อเรื่อง</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <?php
                if (!isset($_GET["community"])) {
            ?>
            <input type="hidden" id="community_id" value="">
            <h2 class="contact-title showByTitle">กำลังเรียกดู โดย ชื่อเรื่อง</h2>
            <?php
                } else {
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
                    <a class="nav-link my-nav-link authorLan" href="?p=author&community=<?php echo $_GET["community"];?>">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan active">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=subject&community=<?php echo $_GET["community"];?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <?php }?>
            <div class="search_widget">
                <div class="input-group mb-3 search_input">
                    <input type="text" class="form-control titlePlace" id="item_title" placeholder="กรองผลลัพธ์โดยพิมพ์ชื่อเรื่อง..."
                        onfocus="this.placeholder = ''">
                    <div class="input-group-append">
                        <button class="btn showButton" type="button" id="search-by-title"><i class="ti-book"></i> เรียกดู</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="blog_area my_section_padding2">
    <div class="container">
        <p id="pagination_info"></p>
        <div id="item-data"></div>
        <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
    </div>
</section>