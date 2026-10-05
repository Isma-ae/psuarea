<link rel="stylesheet" href="pages/search/style.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="pages/search/script.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<div class="breadcrumb_section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my_breadcrumb">
            <li class="breadcrumb-item"><a href="./" class="home">หน้าแรก</a></li>
                <li class="breadcrumb-item search active" aria-current="page">ค้นหา</li>
            </ol>
        </nav>
    </div>
</div>

<section class="cart_area">
    <div class="container">
        <div class="just-padding">
            <div class="row">
                <div class="col-12 col-md-3">
                    <h2 class="contact-title filter">ตัวกรอง</h2>
                    <div class="product_sidebar">
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list filterAuthor">ผู้เขียน <i class="right fas fa-caret-down"></i></div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div id="authur-data">
                                        <?php 
                                            if (isset($_GET["author_name"])) {
                                                $author_name = $_GET["author_name"];
                                        ?>
                                        <p data-type="author">
                                            <input type="checkbox" id="author_in_param" name="author_name[]" style="width:20%;" value="<?= $author_name;?>" class="my-check" checked>
                                            <label for="author_in_param"><?= $author_name;?></label>
                                        </p>
                                        <?php
                                            }else{
                                                echo '<input type="hidden" id="author_in_param" value="">';
                                            }
                                        ?>
                                    </div>
                                    <p>
                                        <a href="#" id="showMore">Show More</a>
                                        <a href="#" id="collapse" style="float:right">collapse</a>
                                    </p>
                                    <input type="text" class="form-control bg-light" id="search-author">
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list filterSubject">คำสำคัญ <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div id="subject-data">
                                        <?php 
                                            if (isset($_GET["subject_name"])) {
                                                $subject_name = $_GET["subject_name"];
                                        ?>
                                        <p data-type="subject">
                                            <input type="checkbox" id="subject_in_param" name="subject_name[]" style="width:20%;" value="<?= $subject_name;?>" class="my-check" checked>
                                            <label for="subject_in_param"><?= $subject_name;?></label>
                                        </p>
                                        <?php
                                            }else{
                                                echo '<input type="hidden" id="subject_in_param" value="">';
                                            }
                                        ?>
                                    </div>
                                    <p>
                                        <a href="#" id="showMore2">Show More</a>
                                        <a href="#" id="collapse2" style="float:right">collapse</a>
                                    </p>
                                    <input type="text" class="form-control bg-light" id="search-subject">
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list filterYear">ปีที่เผยแพร่ <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <div class="inputs">
                                        <div class="range-input-group">
                                            <label for="min-input" class="since">ตั้งแต่</label>
                                            <input type="text" id="min-input" value="" class="form-control bg-light" style="border-radius: 0px;">
                                        </div>
                                        <div class="range-input-group">
                                            <label for="max-input" class="to">ถึง</label>
                                            <input type="text" id="max-input" value="" class="form-control bg-light" style="border-radius: 0px;">
                                        </div>
                                    </div>
                                    <br>
                                    <div id="slider-range"></div>
                                </div>
                            </div>
                        </div>
                        <div class="single_sedebar">
                            <div class="select_option">
                                <div class="select_option_list hasFile">มีไฟล์ <i class="right fas fa-caret-down"></i> </div>
                                <div class="select_option_dropdown" style="display: none;">
                                    <p>
                                        <input type="checkbox" id="has_file1" name="has_file" value="1"
                                            style="width:20%;">
                                        <label for="has_file1" class="yes">ใช่</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="?p=search" class="genric-btn primary radius resetFilter"><i class="ti-reload"></i> รีเซ็ตตัวกรอง</a>
                    </div>
                    <hr>
                    <h2 class="contact-title setting">การตั้งค่า</h2>
                    <div style="margin-bottom: 80px;">
                        <h4 class="orderBy">เรียงตาม</h4>
                        <div class="form-group">
                            <select class="shipping_select" id="order_by">
                                <option value="1" class="latestItems">รายการล่าสุด</option>
                                <option value="2" class="firstItem">รายการแรก</option>
                                <option value="3" class="namesFromLeast">ชื่อจากน้อยไปมาก</option>
                                <option value="4" class="namesFromMost">ชื่อจากมากไปน้อย</option>
                                <option value="5" class="yearsFromLeast">ปีจากน้อยไปมาก</option>
                                <option value="6" class="yearsFromMost">ปีจากมากไปน้อย</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 80px;">
                        <h4 class="resultPerPage">ผลลัพธ์ต่อหน้า</h4>
                        <div class="form-group">
                            <select class="shipping_select" id="limit">
                                <option value="1">1</option>
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9" style="padding-left: 50px;">
                    <div class="search_widget">
                        <div class="input-group mb-3 search_input">
                            <?php $search_term = (isset($_GET["search_term"])) ? $_GET["search_term"] : "" ;?>
                            <input type="text" class="form-control titlePlace" placeholder="กรองผลลัพธ์โดยพิมพ์ชื่อเรื่อง..."
                                id="search_query" value="<?php echo $search_term;?>" onfocus="this.placeholder = ''">
                            <div class="input-group-append">
                                <button class="btn showButton" type="button" id="search-button"><i class="ti-book"></i>
                                    เรียกดู</button>
                            </div>
                        </div>
                    </div>
                    <h2 class="contact-title searchResults">ผลการค้นหา</h2>
                    <p id="pagination_info"></p>
                    <div id="item-data"></div>
                    <nav class="my-blog-pagination justify-content-center d-flex" id="pagination_link"></nav>
                </div>
            </div>
        </div>
    </div>
</section>