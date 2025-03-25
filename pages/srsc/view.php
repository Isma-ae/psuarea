<link rel="stylesheet" href="pages/srsc/style.css">
<script src="pages/srsc/script.js"></script>
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
            <div class="alert alert-info" role="alert">
                เลือกหัวข้อที่จะเพิ่มเป็นตัวกรองการค้นหา
            </div>
            <div class="search_widget">
                <div class="input-group mb-3 search_input">
                    <input type="text" class="form-control" id="search_query">
                    <div class="input-group-append">
                        <button class="btn" type="button" id="btn-search">ค้นหา</button>
                    </div>
                    <div class="input-group-append">
                        <button class="btn" type="button" id="btn-reset">รีเซ็ต</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <form>
                <input type="hidden" name="p" value="search-srsc">
                <?php
                    $community_id = (isset($_GET["community"])) ? '<input type="hidden" name="community" value="'.$_GET["community"].'">' : "" ;
                    $collection_id = (isset($_GET["collection"])) ? '<input type="hidden" name="collection" value="'.$_GET["collection"].'">' : "" ;
                ?>
                <?= $community_id?>
                <?= $collection_id?>
                <div class="list-group list-group-root well type-data">
                </div>
                <button type="submit" class="genric-btn primary medium" id="browse">เรียกดู</button>
            </form>
        </div>
    </div>
</section>