<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.min.js'); ?>"></script>
<link href="<?php echo base_url($frameworks_dir . '/bootstrap-sweetalert/sweetalert.css'); ?>" rel="stylesheet">
<script type='text/javascript' src="<?php echo base_url(); ?>js/toolcals.js"></script>


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
                                    <h3 class="box-title"><?php echo anchor('home/create', '<i class="fa fa-plus"></i> '. lang('toolcals_create'), array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
                                </div>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="txtSearch" name="txtSearch" onkeyup="searchCustomers();" placeholder="Tìm tên khách hàng">
                                </div>
                                <div class="box-body">
                                    <table id="tableLstCheDoAn" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Ngày</th>
                                                <th>Tên</th>
                                                <th>Giới tính</th>
                                                <th>Tuổi</th>
                                                <th>Cao</th>
                                                <th>Nặng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($toolcals as $values):?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($values->dDateCheDoAn)); ?></td>
                                                <td><?php echo htmlspecialchars($values->sTenKhachHang, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(($values->iGioTinh==0?"Nữ":"Nam"), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="float-right"><?php echo round($values->fTuoi); ?></td>
                                                <td class="float-right"><?php echo round($values->fCao); ?></td>
                                                <td class="float-right"><?php echo round($values->fNang); ?></td>
                                                <td><?php echo anchor("admin/toolcals/edit/".$values->iIDCheDoAn, lang('actions_edit')); ?></td>
                                                <td data-id="<?php echo $values->iIDCheDoAn; ?>"><a class="delete-chedoan" href="#">Delete</a></td>
                                            </tr>
<?php endforeach;?>
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
        $(".delete-chedoan").click(function() {
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
                        type:'delete',
                        url: "<?php echo base_url(); ?>admin/toolcals/delete/" + id,
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

    function searchCustomers() {
        // Declare variables 
        var input, filter, table, tr, td, i;
        input = document.getElementById("txtSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableLstCheDoAn");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
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