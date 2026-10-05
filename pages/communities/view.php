<link rel="stylesheet" href="pages/communities/style.css">
<script src="pages/communities/script.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
                <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item active community_title" aria-current="page">Data</li>
            </ol>
        </nav>
    </div>
</div>
<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <input type="hidden" id="community_id" value="<?php echo $_GET["community"];?>">
            <h2 class="contact-title community_title" id="community_title"></h2>
            <div id="community_img"></div>
            <hr>
            <h3 class="show">เรียกดู</h3>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item my-nav-item">
                    <a class="nav-link my-nav-link collection active">คอลเล็กชัน</a>
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
                    <a class="nav-link  my-nav-link subLan" href="?p=subject&community=<?php echo $_GET["community"];?>">คำสำคัญ</a>
                </li>
            </ul>
            <br>
            <p id="pagination_info"></p>
            <div class="my-item"></div>
            <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
        </div>
    </div>
</section>