<link rel="stylesheet" href="pages/author/style.css?v=<?= file_exists(__DIR__ . '/../author/style.css') ? filemtime(__DIR__ . '/../author/style.css') : time(); ?>">
<script src="pages/subject/script.js?v=<?= file_exists(__DIR__ . '/script.js') ? filemtime(__DIR__ . '/script.js') : time(); ?>"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item subLan active" aria-current="page">คำสำคัญ</li>
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
            <h2 class="contact-title showBySub">กำลังเรียกดู โดย คำสำคัญ</h2>
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
                    <a class="nav-link  my-nav-link titleLan" href="?p=title&community=<?php echo $_GET["community"];?>">ชื่อเรื่อง</a>
                </li>
                <li class="nav-item my-nav-item">
                    <a class="nav-link  my-nav-link subLan active">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <?php }?>
            <div class="search_widget">
                <div class="input-group mb-3 search_input">
                    <input type="text" class="form-control subPlace" id="subject_name" placeholder="กรองผลลัพธ์โดยพิมพ์คำสำคัญ..."
                        onfocus="this.placeholder = ''">
                    <div class="input-group-append">
                        <button class="btn showButton" type="button" id="search-by-subject"><i class="ti-book"></i> เรียกดู</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="my_section_padding2">
    <div class="container">
        <p id="pagination_info"></p>
        <div class="my-item"></div>
        <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
    </div>
</section>