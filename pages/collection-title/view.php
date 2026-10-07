<link rel="stylesheet" href="pages/collection-title/style.css?v=<?= file_exists(__DIR__ . '/style.css') ? filemtime(__DIR__ . '/style.css') : time(); ?>">
<script src="pages/collection-title/script.js?v=<?= file_exists(__DIR__ . '/script.js') ? filemtime(__DIR__ . '/script.js') : time(); ?>"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item collection_name" aria-current="page">Data</li>
                <li class="breadcrumb-item titleLan active" aria-current="page">ชื่อเรื่อง</li>
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
            <h3  class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link lastItem" href="?p=collections&collection=<?php echo $_GET["collection"].$link;?>">รายการล่าสุด</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link year" href="?p=collection-date&collection=<?php echo $_GET["collection"].$link;?>">ปีที่เผยแพร่</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link authorLan" href="?p=collection-author&collection=<?php echo $_GET["collection"].$link;?>">ผู้แต่ง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link titleLan active">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan" href="?p=collection-subject&collection=<?php echo $_GET["collection"].$link;?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
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