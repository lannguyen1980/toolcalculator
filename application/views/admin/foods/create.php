<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<script type='text/javascript' src="<?php echo base_url(); ?>assets/toolcals/js/toolcals.js"></script>
<script>
    $(document).ready(function(){
        let arrFoods={};
        arrFoods.fCalori=onConvertObjToFloat($("#fCalori").val());
        arrFoods.fDam=onConvertObjToFloat($("#fDam").val());
        arrFoods.fBeo=onConvertObjToFloat($("#fBeo").val());
        arrFoods.fBotOrDuong=onConvertObjToFloat($("#fBotOrDuong").val());
        arrFoods.fXo=onConvertObjToFloat($("#fXo").val());

        $("#fDam").change(function(){
            arrFoods.fDam=onConvertObjToFloat($("#fDam").val());
            arrFoods.fCalori=arrFoods.fDam*4+arrFoods.fBeo*9+arrFoods.fBotOrDuong*4;  //=D79*4+E79*9+F79*4

            $("#fCalori").val(Math.round(arrFoods.fCalori * 100) / 100);
        });
        $("#fBeo").change(function(){
            arrFoods.fBeo=onConvertObjToFloat($("#fBeo").val());
            arrFoods.fCalori=arrFoods.fDam*4+arrFoods.fBeo*9+arrFoods.fBotOrDuong*4;  //=D79*4+E79*9+F79*4

            $("#fCalori").val(Math.round(arrFoods.fCalori * 100) / 100);
        });
        $("#fBotOrDuong").change(function(){
            arrFoods.fBotOrDuong=onConvertObjToFloat($("#fBotOrDuong").val());
            arrFoods.fCalori=arrFoods.fDam*4+arrFoods.fBeo*9+arrFoods.fBotOrDuong*4;  //=D79*4+E79*9+F79*4

            $("#fCalori").val(Math.round(arrFoods.fCalori * 100) / 100);
        });
        
        
    });
</script>

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
                                    <h3 class="box-title"><?php echo lang('foods_create'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <?php echo $message;?>

                                    <?php echo form_open(current_url(), array('class' => 'form-horizontal', 'iIDThucAn' => 'form-create_food')); ?>
                                        <div class="form-group">
                                            <?php echo lang('foods_sTenThucAn', 'sTenThucAn', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <?php echo form_input($sTenThucAn);  ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_sSLplusDVT', 'sSLplusDVT', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-6">
                                                <?php echo form_input($sSLplusDVT);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_fCalori', 'fCalori', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($fCalori);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_fDam', 'fDam', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($fDam);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_fBeo', 'fBeo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($fBeo);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_fBotOrDuong', 'fBotOrDuong', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($fBotOrDuong);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php echo lang('foods_fXo', 'fXo', array('class' => 'col-sm-2 control-label')); ?>
                                            <div class="col-sm-2">
                                                <?php echo form_input($fXo);?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <div class="btn-food">
                                                    <?php echo form_button(array('type' => 'submit', 'class' => 'btn btn-primary btn-flat', 'content' => lang('actions_submit'))); ?>
                                                    <?php echo form_button(array('type' => 'reset', 'class' => 'btn btn-warning btn-flat', 'content' => lang('actions_reset'))); ?>
                                                    <?php echo anchor('admin/foods', lang('actions_cancel'), array('class' => 'btn btn-default btn-flat')); ?>
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
