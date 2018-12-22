<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.min.js'); ?>"></script>
<link href="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.css'); ?>" rel="stylesheet">

<link href="<?php echo base_url(); ?>assets/toolcals/css/toolcals.css" rel="stylesheet">
<script type='text/javascript' src="<?php echo base_url(); ?>assets/toolcals/js/toolcals.js"></script>


            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo lang('toolcals_create'); ?></h3>
                                    <?php 
                                        // $aaa=json_decode('[{"0":"1","1":"Cơm trắng (chén)","2":"10gio","3":"1 chén 100g","4":"97","5":"2","6":"0.2","7":"21.8","8":"1","9":"Remove"},{"0":"2","1":"Bầu xào trứng (đĩa)","2":"11gio","3":"1 đĩa","4":"108.5","5":"4","6":"8.5","7":"4","8":"1.3","9":"Remove"}]',true);
                                        // // var_dump($aaa);
                                        // // var_dump(count($aaa));
                                        // // var_dump(sizeof($aaa));
                                        // foreach ($aaa as $value) {
                                        //     // var_dump($value);
                                        //     echo($value[0]);
                                        //     echo('<br />');
                                        // }
                                     ?>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'iIDCheDoAn' => 'form-create_toolcal')); ?>
                                        
                                        <div class="panel panel-default col-md-6">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_dDateCheDoAn', 'dDateCheDoAn', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($dDateCheDoAn);  ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_sTenKhachHang', 'sTenKhachHang', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-7">
                                                        <?php echo form_input($sTenKhachHang);?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_iGioTinh', 'iGioTinh', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_dropdown('iGioTinh',$iGioTinh , null,'id="iGioTinh" name="iGioTinh" class="form-control" ');?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fTuoi', 'fTuoi', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fTuoi);?>
                                                    </div>
                                                    <label class="control-label label-right">(Năm)</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fCao', 'fCao', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fCao);?>
                                                    </div>
                                                    <label class="control-label label-right">(Cms)</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fNang', 'fNang', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fNang);?>
                                                    </div>
                                                    <label class="control-label label-right">(Kgs)</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_iIDCuongDo', 'iIDCuongDo', array('class' => 'col-sm-5 control-label')); ?>
                                                    <div class="col-sm-7">
                                                        <?php echo form_dropdown('iIDCuongDo',$lstCuongDo , null,'id="iIDCuongDo" name="iIDCuongDo" class="form-control" ');?>
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group" style="height:8px;">
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="panel panel-default col-md-6">
                                            <div class="panel-body">
                                                <!-- ///////////////////////////Vong eo, Bung, Mong,.../////////////////////// -->
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fVongEo', 'fVongEo', array('class' => 'col-sm-6 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fVongEo);?>
                                                    </div>
                                                    <label class="control-label label-right">(Cms)</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fVongCo', 'fVongCo', array('class' => 'col-sm-6 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fVongCo);?>
                                                    </div>
                                                    <label class="control-label label-right">(Cms)</label>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('toolcals_fVongMong', 'fVongMong', array('class' => 'col-sm-6 control-label')); ?>
                                                    <div class="col-sm-5">
                                                        <?php echo form_input($fVongMong);?>
                                                    </div>
                                                    <label class="control-label label-right">(Cms)</label>
                                                </div>
                                                <hr>
                                                <!-- ///////////////////////////Tốc độ tăng/giảm cân, Macro, So bua an,.../////////////////////// -->
                                                <div class="form-group">
                                                    <label for="iIDTocDoTangOrGiam" class="col-sm-6 control-label">Tốc độ tăng/giảm cân</label>
                                                    <div class="col-sm-6">
                                                        <?php echo form_dropdown('iIDTocDoTangOrGiam',$iIDTocDoTangOrGiam , null,'id="iIDTocDoTangOrGiam" name="iIDTocDoTangOrGiam" class="form-control" ');?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="iIDMacro" class="col-sm-6 control-label">Macro</label>
                                                    <div class="col-sm-6">
                                                        <?php echo form_dropdown('iIDMacro',$iIDMacro , null,'id="iIDMacro" name="iIDMacro" class="form-control" ');?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label">Số bữa ăn trong ngày</label>
                                                    <div class="col-sm-6">
                                                        <?php echo form_dropdown('iSoBuaAnNgay',$iSoBuaAnNgay , null,'id="iSoBuaAnNgay" name="iSoBuaAnNgay" class="form-control" ');?>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="height:8px;">
                                                    <!--/////////add them cho bang ben trai///////////////-->
                                                </div>   

                                            </div>
                                        </div>
                                        <!-- ///////////////////////////View thong tin không input/////////////////////// -->
                                        <div id="accordion" class="panel-group col-sm-12" style="width: 103%; margin: -10px 0 0px -14px;">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                        <button type="button" id="showOrHideInfo" class="btn btn-info" >ẨN/HIỆN THÔNG TIN</button>
                                                    </a>
                                                </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <!-- ///////////////////////////BMI, BMR,.../////////////////////// -->
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_fBMI', 'fBMI', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fBMI);?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_fBMR', 'fBMR', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fBMR);?>
                                                            </div>
                                                            <label class="control-label label-right">(Kcals)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_fTDEE', 'fTDEE', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fTDEE);?>
                                                            </div>
                                                            <label class="control-label label-right">(Kcals)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_sNguyCoBeoPhi', 'sNguyCoBeoPhi', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($sNguyCoBeoPhi);?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_fTiLeMo', 'fTiLeMo', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fTiLeMo);?>
                                                            </div>
                                                            <label class="control-label label-right">(%)</label>
                                                        </div>
                                                        <hr>
                                                        <!-- ///////////////////////////PROTEIN,../////////////////////// -->
                                                        <div class="form-group ">
                                                            <?php echo lang('toolcals_fProtein', 'fProtein', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fProteinPercent);?>
                                                            </div>
                                                            <label class="control-label label-right">(%)</label>
                                                        <!-- </div>
                                                        <div class="form-group ">
                                                            <label class="col-sm-5 control-label"></label> -->
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fProteinQty);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        <div class="form-group ">
                                                            <?php echo lang('toolcals_fCarbohydrate', 'fCarbohydrate', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fCarbohydratePercent);?>
                                                            </div>
                                                            <label class="control-label label-right">(%)</label>
                                                        <!-- </div>
                                                        <div class="form-group ">
                                                            <label class="col-sm-5 control-label"></label> -->
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fCarbohydrateQty);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_fFat', 'fFat', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fFatPercent);?>
                                                            </div>
                                                            <label class="control-label label-right">(%)</label>
                                                        <!-- </div>
                                                        <div class="form-group ">
                                                            <label class="col-sm-5 control-label"></label> -->
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fFatQty);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label">Protein 1 bữa ăn</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fProteinNgay);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label">Carbohydrate 1 bữa ăn</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fCarbohydrateNgay);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label">Fat 1 bữa ăn</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fFatNgay);?>
                                                            </div>
                                                            <label class="col-sm-1 control-label label-right" >(Grams)</label>
                                                        </div>
                                                        <!--<div class="form-group" style="height:34px;">-->
                                                        <!--</div>   -->
                                                        <hr>
                                                        <!-- ///////////////////////////KET LUAN/////////////////////// -->
                                                        <div class="form-group">
                                                            <?php echo lang('toolcals_sLoiKhuyen', 'sLoiKhuyen', array('class' => 'col-sm-5 control-label')); ?>
                                                            <div class="col-sm-6">
                                                                <?php echo form_input($sLoiKhuyen);?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label">Kèm</label>
                                                            <div class="col-sm-6">
                                                                <input type="text" id="sKemLoiKhuyen" name="sKemLoiKhuyen" class="form-control" value="" readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="fTangOrGiamKG" class="col-sm-5 control-label">Số kgs tăng/giảm</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fTangOrGiamKG);?>
                                                            </div>
                                                            <label class="control-label label-right">(Kgs)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="iIDTocDoTangOrGiam" class="col-sm-5 control-label">Lượng Calories mục tiêu hằng ngày</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($fLuongCalories);?>
                                                            </div>
                                                            <label class="control-label label-right">(Kcals)</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="iSoNgayDuKien" class="col-sm-5 control-label">Số ngày dự kiến hoàn thành</label>
                                                            <div class="col-sm-2">
                                                                <?php echo form_input($iSoNgayDuKien);?>
                                                            </div>
                                                            <label class="control-label label-right">(ngày)</label>
                                                        </div>
                                                        <hr>
                                                        <!-- ///////////////////////////GHI CHÚ/////////////////////// -->
                                                        <div class="form-group">
                                                            <div class="col-sm-1" style="text-align: top;">
                                                                <label class="control-label">GHI CHÚ:</label>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <div class="label-description">
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Số cân nặng chuẩn đẹp nên bằng 2 số cuối chiều cao (168 cm thì nên nặng 68 kg).
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Lời khuyên chỉ mang tính tham khảo, mắt nhìn cũng là 1 thước đo chuẩn mực.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Không nên tăng/giảm cân quá nhanh sẽ ảnh hưởng sức khỏe, da dẻ.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Tinh bột màu trắng (cơm, bún, đường cát...) dễ gây tích mỡ, nên hạn chế.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Nên thay bằng tinh bột khác màu trắng (gạo lức, yến mạch, khoai lang...)
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Hạn chế tối đa chất béo xấu: mỡ động vật và dầu ăn thường.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Thay bằng chất béo tốt: Omega-3,6,9 (Mỡ cá hồi, quả bơ, dầu olive không chiên xào, dầu dừa,bơ đậu phộng...)
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Khi chiên xào nên dùng dầu hạt cải, dầu hướng dương.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Ăn nhiều xơ không sợ tăng cân.
                                                                    <br />
                                                                    <i class="fa fa-hand-o-right" aria-hidden="true"></i> Ăn nhiều rau củ quả bổ sung Vitamins và khoáng chất.
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <!--end Ghi chu-->
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <button type="button" id="showOrHideInfo" class="btn btn-info" style="margin:-10px 0 0 -14px;" data-toggle="collapse" data-target="#info">XEM THÔNG TIN</button>
                                            <div id="info" class="collapse">
                                                <div class="panel panel-default col-md-12" style="width: 103%; margin: 0 0 0 -14px;">
                                                    <div class="panel-body">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         -->
                                        <div class="col-md-12">
                                            <div class="panel with-nav-tabs panel-primary" style="width: 103%; margin: 10px 0 10px -14px;">
                                                <div class="panel-heading">
                                                        <ul class="nav nav-tabs">
                                                            <?php for ($i = 1; $i <= $iMaxSoBuaAnNgay; $i++) { ?>
                                                                <li id="liBuaId<?php echo $i; ?>" class="<?php echo ($i==1?'active':''); ?>"><a href="#tabsBua<?php echo $i; ?>" data-toggle="tab" onclick="selectGioAn(<?php  echo $i;  ?>)"><?php echo lang("toolcals_sBua".$i); ?></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="tab-content">
                                                        <?php for ($i = 1; $i <= $iMaxSoBuaAnNgay; $i++) { ?>
                                                            <div class="tab-pane fade in <?php echo ($i==1?'active':''); ?>" id="tabsBua<?php echo $i; ?>">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title">
                                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lstFoodModal"><i class="fa fa-search-plus" aria-hidden="true"></i> Chọn thức ăn bữa <?php echo $i; ?></button>
                                                                        <input type="hidden" id="hideBua<?php echo $i; ?>" name="hideBua<?php echo $i; ?>" class="form-control" value=""/>
                                                                    </h3>
                                                                    <div class="box-body">
                                                                        <table id="tableBua<?php echo $i; ?>" class="table table-striped table-hover">
                                                                            <?php include 'headerfoods.php';?>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-offset-0 col-sm-10">
                                                <div class="btn-toolcal" style="margin: 0 0 0 20px;">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/toolcals', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>


    <!-- Modal foods -->
    <div id="lstFoodModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!-- <h4 class="modal-title">Danh sách thức ăn</h4> -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="txtSearch" name="txtSearch" onkeyup="searchFoods();" placeholder="Tìm thức ăn">
                            </div>
                            <!-- <div class="col-sm-2">
                                <button type="submit" class="btn btn-info" name="submit" ><i class="fa fa-search" aria-hidden="true"></i> Tìm</button>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <table id="tableLstThucAn" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?php echo "Chọn";?></th>
                                <th style="display: none">ID thức ăn</th>
                                <th><?php echo lang('toolcals_sTenThucAn');?></th>
                                <th><?php echo lang('toolcals_sSLplusDVT');?></th>
                                <th><?php echo lang('toolcals_fCalori');?></th>
                                <th><?php echo lang('toolcals_fDam');?></th>
                                <th><?php echo lang('toolcals_fBeo');?></th>
                                <th><?php echo lang('toolcals_fBotOrDuong');?></th>
                                <th><?php echo lang('toolcals_fXo');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $lstHtmlFoods; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="btnOK" type="button" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Đóng</button>
                </div>
            </div>

        </div>
    </div>



<script>
    var fTotalCalori=0;
    var iMaxSoBuaAnNgay=<?php echo $iMaxSoBuaAnNgay ?>;
    $("#iSoBuaAnNgay").val(iMaxSoBuaAnNgay);
    var sGioAn="Bua1";
    function selectGioAn(iIDGioAn){
        iIDGioAn=onConvertObjToInt(iIDGioAn);
        sGioAn="Bua"+iIDGioAn;
    }
    $(document).ready(function(){
        function CalProtein_Carbohydrate_Fat(){
            //fProteinPercent=ifs(C26="Ăn kiêng vừa phải 1","25%",C26="Ăn kiêng vừa phải 2","25%",C26="Zone Diet","30%",C26="Ăn ít chất béo (Low Fat)","25%",C26="Ăn ít tinh bột (Low Carb)","40%",C26="Rất ít tinh bột (Ketogenic)","45%",true,"0")
            //fCarbohydratePercent=ifs(C26="Ăn kiêng vừa phải 1","50%",C26="Ăn kiêng vừa phải 2","55%",C26="Zone Diet","40%",C26="Ăn ít chất béo (Low Fat)","60%",C26="Ăn ít tinh bột (Low Carb)","25%",C26="Rất ít tinh bột (Ketogenic)","10%",true,"0")
            //fFatPercent=ifs(C26="Ăn kiêng vừa phải 1","25%",C26="Ăn kiêng vừa phải 2","20%",C26="Zone Diet","30%",C26="Ăn ít chất béo (Low Fat)","15%",C26="Ăn ít tinh bột (Low Carb)","35%",C26="Rất ít tinh bột (Ketogenic)","45%",true,"0")
            let fProteinPercent=0;
            let fCarbohydratePercent=0;
            let fFatPercent=0;
            if($("#iIDMacro").val()==1){        //Ăn kiêng vừa phải 1
                fProteinPercent=25;
                fCarbohydratePercent=50;
                fFatPercent=25;
            }else if($("#iIDMacro").val()==2){  //Ăn kiêng vừa phải 2
                fProteinPercent=25;
                fCarbohydratePercent=55;
                fFatPercent=20;
            }else if($("#iIDMacro").val()==3){  //Zone Diet
                fProteinPercent=30;
                fCarbohydratePercent=40;
                fFatPercent=30;
            }else if($("#iIDMacro").val()==4){  //Ăn ít chất béo (Low Fat)
                fProteinPercent=25;
                fCarbohydratePercent=60;
                fFatPercent=15;
            }else if($("#iIDMacro").val()==5){  //Ăn ít tinh bột (Low Carb)
                fProteinPercent=40;
                fCarbohydratePercent=25;
                fFatPercent=35;
            }else if($("#iIDMacro").val()==6){  //Rất ít tinh bột (Ketogenic)
                fProteinPercent=45;
                fCarbohydratePercent=10;
                fFatPercent=45;
            }
            $("#fProteinPercent").val(Math.round((fProteinPercent) * 100) / 100); 
            $("#fCarbohydratePercent").val(Math.round((fCarbohydratePercent) * 100) / 100); 
            $("#fFatPercent").val(Math.round((fFatPercent) * 100) / 100); 
            //fProteinQty=D23*C27/4
            let fProteinQty=onConvertObjToFloat($("#fLuongCalories").val())*(fProteinPercent/100)/4;
            $("#fProteinQty").val(Math.round((fProteinQty) * 100) / 100); 
            //fCarbohydrateQty=D23*C28/4
            let fCarbohydrateQty=onConvertObjToFloat($("#fLuongCalories").val())*(fCarbohydratePercent/100)/4;
            $("#fCarbohydrateQty").val(Math.round((fCarbohydrateQty) * 100) / 100); 
            //fFatQty=D23*C29/9
            let fFatQty=onConvertObjToFloat($("#fLuongCalories").val())*(fFatPercent/100)/9;
            $("#fFatQty").val(Math.round((fFatQty) * 100) / 100); 

            //////////////////Số bữa ăn trong ngày://///////////////
            let iSoBuaAnTrongNgay=onConvertObjToInt($("#iSoBuaAnNgay").val());
            //fProteinNgay=ifs(C30=2,(D27/2),C30=3,(D27/3),C30=4,(D27/4),C30=5,(D27/5),C30=6,(D27/6),true,0)
            //fCarbohydrateNgay=ifs(C30=2,(D28/2),C30=3,(D28/3),C30=4,(D28/4),C30=5,(D28/5),C30=6,(D28/6),true,"0")
            //fFatNgay==ifs(C30=2,(D29/2),C30=3,(D29/3),C30=4,(D29/4),C30=5,(D29/5),C30=6,(D29/6),true,"0")
            let fProteinNgay=0;
            let fCarbohydrateNgay=0;
            let fFatNgay=0;
            if(iSoBuaAnTrongNgay>0){
                fProteinNgay=(fProteinQty/iSoBuaAnTrongNgay);   
                fCarbohydrateNgay=(fCarbohydrateQty/iSoBuaAnTrongNgay);   
                fFatNgay=(fFatQty/iSoBuaAnTrongNgay);   
            }
            $("#fProteinNgay").val(Math.round((fProteinNgay) * 100) / 100); 
            $("#fCarbohydrateNgay").val(Math.round((fCarbohydrateNgay) * 100) / 100); 
            $("#fFatNgay").val(Math.round((fFatNgay) * 100) / 100); 
        }

        function showOrHideTabBuaAn(){
            let iSoBuaAnTrongNgay=onConvertObjToInt($("#iSoBuaAnNgay").val());
            for(i=1+1;i<=iMaxSoBuaAnNgay;i++){
                if(i<=iSoBuaAnTrongNgay)
                    $("#liBuaId"+i).show();
                else
                    $("#liBuaId"+i).hide();
            }
        }
        
        $("#iSoBuaAnNgay").change(function(){
            showOrHideTabBuaAn();
            CalProtein_Carbohydrate_Fat();
        });
        $("#iIDMacro").change(function(){
            CalProtein_Carbohydrate_Fat();
        });
        
        function CalBMI_BMR_TDEE(){
            //BMI=D8/((D7/100)*(D7/100))
            let fBMI=onConvertObjToFloat($("#fNang").val())/((onConvertObjToFloat($("#fCao").val())/100)*(onConvertObjToFloat($("#fCao").val())/100));    
            $("#fBMI").val(Math.round((fBMI) * 100) / 100); 

            //BMR=IF(D5="Nam",((13.397*D8)+(4.799*D7)-(5.677*D6)+88.362),((9.247*D8)+(3.098*D7)-(4.33*D6)+447.593 ))
            let fBMR=0;
            if($("#iGioTinh").val()==1){
                //((13.397*D8)+(4.799*D7)-(5.677*D6)+88.362)
                fBMR=((13.397*onConvertObjToFloat($("#fNang").val()))+(4.799*onConvertObjToFloat($("#fCao").val()))-(5.677*onConvertObjToFloat($("#fTuoi").val()))+88.362);
            }else{
                //((9.247*D8)+(3.098*D7)-(4.33*D6)+447.593 )
                fBMR=((9.247*onConvertObjToFloat($("#fNang").val()))+(3.098*onConvertObjToFloat($("#fCao").val()))-(4.33*onConvertObjToFloat($("#fTuoi").val()))+447.593 );
            }
            $("#fBMR").val(Math.round((fBMR) * 100) / 100); 

            //fTDEE=ifs(D9="Nhóm 1: Không tập luyện",(D12*1.2),D9="Nhóm 2: Tập nhẹ 1-3 lần/tuần",(D12*1.375),D9="Nhóm 3: Tập vừa phải: 3-5 lần/tuần",(D12*1.55),D9="Nhóm 4: Tập nhiều 6-7 lần/tuần",(D12*1.725),D9="Nhóm 5: Tập nặng trên 7 lần/tuần",(D12*1.9),true,"0")
            let fTDEE=0;
            let fHeSo=0;
            if($("#iIDCuongDo").val()==1){
                fHeSo=1.2;    //(D12*1.2)
            }else if($("#iIDCuongDo").val()==2){
                fHeSo=1.375;    //(D12*1.375)
            }else if($("#iIDCuongDo").val()==3){
                fHeSo=1.55;    //(D12*1.55)
            }else if($("#iIDCuongDo").val()==4){
                fHeSo=1.725;    //(D12*1.725)
            }else if($("#iIDCuongDo").val()==5){
                fHeSo=1.9;    //(D12*1.9)
            }
            fTDEE=onConvertObjToFloat($("#fBMR").val())*fHeSo;  
            $("#fTDEE").val(Math.round((fTDEE) * 100) / 100); 

            //sLoiKhuyen==ifs(D11<18.5,"Nên tăng cân",D11<24.9,"Nên giữ cân",D11<29.9,"Nên giảm cân",D11<34.9,"Nên giảm cân",D11<39.9,"Nên tích cực giảm cân",true,"Nên tích cực giảm cân")
            //fTangOrGiamKG=ifs(D11<18.5,(right(D7,2)-D8),D11<24.9,"0",D11<29.9,(D8-right(D7,2)),D11<34.9,(D8-right(D7,2)),D11<39.9,(D8-right(D7,2)),true,(D8-right(D7,2)))
            let arrLoiKhuyen={};
            arrLoiKhuyen.NenTang='Nên tăng cân';
            arrLoiKhuyen.NenGiu='Nên giữ cân';
            arrLoiKhuyen.NenGiam='Nên giảm cân';
            arrLoiKhuyen.NenTichCucGiam='Nên tích cực giảm cân';
            let sLoiKhuyen='';
            let fTangOrGiamKG=0;
            if(onConvertObjToFloat($("#fBMI").val())<18.5){
                sLoiKhuyen=arrLoiKhuyen.NenTang;
                fTangOrGiamKG=(onConvertObjToFloat($("#fCao").val().slice(-2))-onConvertObjToFloat($("#fNang").val()));    //(right(D7,2)-D8)
            }else if(onConvertObjToFloat($("#fBMI").val())<24.9){
                sLoiKhuyen=arrLoiKhuyen.NenGiu;
                fTangOrGiamKG=0;  //D11<24.9,"0"
            }else if(onConvertObjToFloat($("#fBMI").val())<34.9){
                sLoiKhuyen=arrLoiKhuyen.NenGiam;
                fTangOrGiamKG=(onConvertObjToFloat($("#fNang").val())-onConvertObjToFloat($("#fCao").val().slice(-2)));     //(D8-right(D7,2))
            }else{
                sLoiKhuyen=arrLoiKhuyen.NenTichCucGiam;
                fTangOrGiamKG=(onConvertObjToFloat($("#fNang").val())-onConvertObjToFloat($("#fCao").val().slice(-2)));     //(D8-right(D7,2))
            }
            $("#sLoiKhuyen").val(sLoiKhuyen);
            $("#fTangOrGiamKG").val(Math.round((fTangOrGiamKG) * 100) / 100);
            
            //fLuongCalories=ifs(C21="Nên tăng cân",ifs(C22="Chậm",(D13*1.1),C22="Bình thường",(D13*1.2),C22="Nhanh",(D13*1.29),C22="Cấp tốc",(D13*1.37)),
            //                   C21="Nên giảm cân",ifs(C22="Chậm",(D13*0.9),C22="Bình thường",(D13*0.8),C22="Nhanh",(D13*0.71),C22="Cấp tốc",(D13*0.63)),true,D13)
            let fLuongCalories=0;
            let fHeSoLuongCalories=1;
            if(sLoiKhuyen==arrLoiKhuyen.NenTang){ //Nên tăng
                //ifs(C22="Chậm",(D13*1.1),C22="Bình thường",(D13*1.2),C22="Nhanh",(D13*1.29),C22="Cấp tốc",(D13*1.37))
                if($("#iIDTocDoTangOrGiam").val()==2){ //Chậm
                    fHeSoLuongCalories=1.1;
                }else if(onConvertObjToInt($("#iIDTocDoTangOrGiam").val())==1){    //Bình thường
                    fHeSoLuongCalories=1.2;
                }else if($("#iIDTocDoTangOrGiam").val()==3){    //Nhanh
                    fHeSoLuongCalories=1.29;
                }else if($("#iIDTocDoTangOrGiam").val()==4){    //Cấp tốc
                    fHeSoLuongCalories=1.37;
                }
            }else if(sLoiKhuyen==arrLoiKhuyen.NenGiam){ //Nên giảm
                //ifs(C22="Chậm",(D13*0.9),C22="Bình thường",(D13*0.8),C22="Nhanh",(D13*0.71),C22="Cấp tốc",(D13*0.63))
                if($("#iIDTocDoTangOrGiam").val()==2){ //Chậm
                    fHeSoLuongCalories=0.9;
                }else if($("#iIDTocDoTangOrGiam").val()==1){    //Bình thường
                    fHeSoLuongCalories=0.8;
                }else if($("#iIDTocDoTangOrGiam").val()==3){    //Nhanh
                    fHeSoLuongCalories=0.71;
                }else if($("#iIDTocDoTangOrGiam").val()==4){    //Cấp tốc
                    fHeSoLuongCalories=0.63;
                }
            }
            $("#fLuongCalories").val(Math.round((onConvertObjToFloat($("#fTDEE").val()) * fHeSoLuongCalories) * 100) / 100);

            //iSoNgayDuKien=ifs(C21="Nên giữ cân","0",C21="Nên giảm cân",(7000*D21/(D13-D24)),true,(7000*D21/(D24-D13)))
            let iSoNgayDuKien=0;
            if(sLoiKhuyen==arrLoiKhuyen.NenGiu){ //Nên giữ cân
                iSoNgayDuKien=0;
            }else if(sLoiKhuyen==arrLoiKhuyen.NenGiam){ //Nên giảm cân
                iSoNgayDuKien=7000*fTangOrGiamKG/(onConvertObjToFloat($("#fTDEE").val())-onConvertObjToFloat($("#fLuongCalories").val())); //(7000*D21/(D13-D24))
            }else{
                iSoNgayDuKien=7000*fTangOrGiamKG/(onConvertObjToFloat($("#fLuongCalories").val())-onConvertObjToFloat($("#fTDEE").val())); //(7000*D21/(D24-D13)))
            }
            $("#iSoNgayDuKien").val(Math.floor(iSoNgayDuKien));

            ///
            CalProtein_Carbohydrate_Fat();
        }

        function CalNguyCoBeoPhi(){
            //sNguyCoBeoPhi=ifs(D11<18.5,"Gầy",D11<24.9,"Bình thường",D11<29.9,"Thừa cân",D11<34.9,"Béo phì cấp độ 1",D11<39.9,"Béo phì cấp độ 2",true,"Béo phì cấp độ 3")
            let sNguyCoBeoPhi="";
            if(onConvertObjToFloat($("#fBMI").val())<18.5){
                sNguyCoBeoPhi="Gầy";
            } else if(onConvertObjToFloat($("#fBMI").val())<24.9){
                sNguyCoBeoPhi="Bình thường";
            } else if(onConvertObjToFloat($("#fBMI").val())<29.9){
                sNguyCoBeoPhi="Thừa cân";
            } else if(onConvertObjToFloat($("#fBMI").val())<34.9){
                sNguyCoBeoPhi="Béo phì cấp độ 1";
            } else if(onConvertObjToFloat($("#fBMI").val())<39.9){
                sNguyCoBeoPhi="Béo phì cấp độ 2";
            } else{
                sNguyCoBeoPhi="Béo phì cấp độ 3";
            } 
            $("#sNguyCoBeoPhi").val(sNguyCoBeoPhi); 
        }

        function CalTiLeMo(){
            //fTiLeMo==if(D5="Nam",(495/(1.0324-0.19077*log(D16-D17)+0.15456*LOG(D7))-450),(495/(1.29579-0.35004*log10(D16+D18-D17)+0.221*log10(D7))-450))
            let fTiLeMo=0;
            if($("#iGioTinh").val()==1){
                //(495/(1.0324-0.19077*log(D16-D17)+0.15456*LOG(D7))-450)
                fTiLeMo=(495/(1.0324-0.19077*Math.log10(onConvertObjToFloat($("#fVongEo").val())-onConvertObjToFloat($("#fVongCo").val()))+0.15456*Math.log10(onConvertObjToFloat($("#fCao").val())))-450)
            }else{
                //(495/(1.29579-0.35004*log10(D16+D18-D17)+0.221*log10(D7))-450)
                fTiLeMo=(495/(1.29579-0.35004*Math.log10(onConvertObjToFloat($("#fVongEo").val())+onConvertObjToFloat($("#fVongMong").val())-onConvertObjToFloat($("#fVongCo").val()))+0.221*Math.log10(onConvertObjToFloat($("#fCao").val())))-450);
            }
            $("#fTiLeMo").val(Math.round((fTiLeMo) * 100) / 100);  

            //sKemLoiKhuyen=if(D5="Nam",if(D19>12,"Nên giảm mỡ",""),if(D19>22,"Nên giảm mỡ",""))
            let sKemLoiKhuyen='';
            if($("#iGioTinh").val()==1){ //Nam
                //if(D19>12,"Nên giảm mỡ","")
                if(onConvertObjToFloat($("#fTiLeMo").val())>12){
                    sKemLoiKhuyen='Nên giảm mỡ';
                }
            }else{ //Nữ
                //if(D19>22,"Nên giảm mỡ","")
                if(onConvertObjToFloat($("#fTiLeMo").val())>22){
                    sKemLoiKhuyen='Nên giảm mỡ';
                }
            }
            $("#sKemLoiKhuyen").val(sKemLoiKhuyen);
        }

        $("#iGioTinh").change(function(){
            CalBMI_BMR_TDEE();
            CalNguyCoBeoPhi();
            CalTiLeMo();
        });
        $("#fTuoi").change(function(){
            CalBMI_BMR_TDEE();
            CalNguyCoBeoPhi();
        });
        $("#fCao").change(function(){
            CalBMI_BMR_TDEE();
            CalNguyCoBeoPhi();
            CalTiLeMo();
        });
        $("#fNang").change(function(){
            CalBMI_BMR_TDEE();
            CalNguyCoBeoPhi();
        });
        $("#iIDCuongDo").change(function(){
            CalBMI_BMR_TDEE();
            // CalNguyCoBeoPhi();
        });
        //
        $("#fVongEo").change(function(){
            CalTiLeMo();
        });
        $("#fVongCo").change(function(){
            CalTiLeMo();
        });
        $("#fVongMong").change(function(){
            CalTiLeMo();
        });

        
        //List Thuc An
         $("#btnOK").click(function() {

            let arrIdThucAn=  new Array();
            let idxtd=0;
            $('#table'+sGioAn).find('tr').each(function () {
                var row = $(this);
                if(row.closest('tr').attr('id')){
                    arrIdThucAn.push(row.closest('tr').attr('id'));
                }
            });

            //Check luong Calori chon trong thuc an vuot qua luong Calori hang ngay
            if(checkOverloadCalori(arrIdThucAn)==true){
                swal("", "Tổng calori trong thức ăn bạn đã chọn vượt quá calori mục tiêu hằng ngày. Vui lòng kiểm tra lại!", "warning");
                return;
            }

            let htmlData="";
            idxtd=0;
            // var values = new Array();
            $('#tableLstThucAn').find('tr').each(function () {
                var row = $(this);
                if (row.find('input[type="checkbox"]').is(':checked') ) {
                    // alert(row.closest('tr').attr('id'));
                    if (row.closest('tr').attr('id') && arrIdThucAn.indexOf(row.closest('tr').attr('id')) === -1) { //Kiểm tra đã choose hay chua
                        htmlData+="<tr id='"+row.closest('tr').attr('id')+"'>";
                        idxtd=0;
                        row.find('td').each (function() {
                            if(idxtd>0){
                                if(idxtd==1){
                                    htmlData+='<td style="display: none">'+$(this).text()+'</td>';
                                    htmlData+='<td><input type="text" id="'+sGioAn +'_' + row.closest('tr').attr('id') +'" id="'+sGioAn +'_' + row.closest('tr').attr('id') +' " size="4" value="" onchange="ChangeThoiGian();" ></td>';
                                }else{
                                    htmlData+="<td class='col-number'>"+$(this).text()+"</td>";
                                    if(idxtd==4){
                                        fTotalCalori += onConvertObjToFloat($(this).text());
                                    }
                                }
                                // values.push($(this).text());
                            }
                            idxtd++;
                        });
                        // alert("<td data-id='"+row.closest('tr').attr('id')+"'><a class='remove-item' href='#'>Remove</a></td>");
                        htmlData+="<td data-id='"+row.closest('tr').attr('id')+"' ><a class='remove-item' onclick='removeItem("+row.closest('tr').attr('id')+");'>Remove</a></td>";
                        htmlData+="</tr>"; 

                        arrIdThucAn.push(row.closest('tr').attr('id'));
                    }
                    //set uncheck
                    $('#chk_'+row.closest('tr').attr('id')).prop('checked', false);
                }
            });
            
            $('#table'+sGioAn).append(htmlData);

            //Get json list thuc an
            SetDataToHiddenField(sGioAn);
            
            //close popup form
            $('#lstFoodModal').modal('toggle');

            // alert(JSON.stringify(arrIdThucAn));

         });
    });

    function checkOverloadCalori(arrIdThucAn){
        let fCaloriAdd=0;
        let idxtd=0;
        $('#tableLstThucAn').find('tr').each(function () {
            var row = $(this);
            if (row.find('input[type="checkbox"]').is(':checked') ) {
                // alert(row.closest('tr').attr('id'));
                if (row.closest('tr').attr('id') && arrIdThucAn.indexOf(row.closest('tr').attr('id')) === -1) { //Kiểm tra đã choose hay chua
                    idxtd=0;
                    row.find('td').each (function() {
                        if(idxtd==4){
                            fCaloriAdd += onConvertObjToFloat($(this).text());
                        }
                        idxtd++;
                    });
                }
            }
        });
        if((fTotalCalori+fCaloriAdd)>onConvertObjToFloat($('#fLuongCalories').val())){
            return true;
        }else{
            return false;
        }
    }
    
    function ChangeThoiGian(){
        SetDataToHiddenField(sGioAn);
    }

    function SetDataToHiddenField(sGioAnCurent){
        let lstThucAn=  new Array();
        let datarow={};
        let idxtd=0;
        $('#table'+sGioAnCurent).find('tr').each(function () {
            var row = $(this);
            if(row.closest('tr').attr('id')){
                idxtd=0;
                datarow={};
                row.find('td').each (function() {
                    //alert($('#'+sGioAn+'_'+row.closest('tr').attr('id')).val());
                    if(idxtd==1){
                        datarow[idxtd]=$('#'+sGioAnCurent+'_'+row.closest('tr').attr('id')).val();
                    }else{
                        datarow[idxtd]=$(this).text();
                    }
                    
                    idxtd++;
                });
                lstThucAn.push(datarow);
            }
        });
        //alert(JSON.stringify(lstThucAn));
        $('#hide'+sGioAnCurent).val(JSON.stringify(lstThucAn));
    }

    function removeItem(iIDThucAn) {
        var id = $(this).parent("td").data('id');
        var c_obj = $(this).parents("tr");
        //confirm
        swal({
                title: "Bạn có chắc chắc xóa dòng này?",
                // text: "Your will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            },
            function(){
                $('#table'+sGioAn).find('tr').each(function () {
                    var row = $(this);
                    if(row.closest('tr').attr('id')==iIDThucAn){
                        row.remove();
                    }
                });
                swal.close();
            });
            
    };

    function searchFoods() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("txtSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableLstThucAn");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            } 
        }
    }

    

</script>