<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.min.js'); ?>"></script>
<link href="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.css'); ?>" rel="stylesheet">
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
                                    <h3 class="box-title"><?php echo anchor('admin/foods/create', '<i class="fa fa-plus"></i> '. lang('foods_create'), array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="txtSearch" name="txtSearch" onkeyup="searchFoods();" placeholder="Tìm thức ăn">
                                </div>
                                <div class="box-body" style="max-height: 400px; overflow-y: scroll;">
                                    <table id="tableLstThucAn" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('foods_sTenThucAn');?></th>
                                                <th><?php echo lang('foods_sSLplusDVT');?></th>
                                                <th><?php echo lang('foods_fCalori');?></th>
                                                <th><?php echo lang('foods_fDam');?></th>
                                                <th><?php echo lang('foods_fBeo');?></th>
                                                <th><?php echo lang('foods_fBotOrDuong');?></th>
                                                <th><?php echo lang('foods_fXo');?></th>
                                                <!--<th><?php echo lang('foods_sNote');?></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $lstHtmladminfoods; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>

<script>
    $(document).ready(function(){
        $(".delete-me").click(function() {
            var id = $(this).parent("td").data('id');
            var c_obj = $(this).parents("tr");
            //confirm
            swal({
                title: "Bạn có chắc chắn xóa?",
                    // text: "Your will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        dataType: 'json',
                        type:'POST',
                        url: "<?php echo base_url(); ?>admin/foods/delete/" + id,
                        success: function(data){
                        // console.log(data);
                        c_obj.remove();
                        swal("Deleted!", "Xóa thành công!", "success");
                            // toastr.success('Item Deleted Successfully.', 'Success Alert', {timeOut: 3000});
                        },
                        error: function(data){
                            //console.log(data)
                        }
                    });
                    
            });

            

        });
    });

    function searchFoods() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("txtSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableLstThucAn");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
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
