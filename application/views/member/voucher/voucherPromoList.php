<?php if($result['response'] == "false") { ?>
  <div class='alert alert-error'><?= $result['message'] ?></div>
<?php } else { ?>
  <table class='table table-bordered bootstrap-datatable datatable' width="100%">
    <thead>
      <tr>
        <th colspan="7">Data Voucher</th>
      </tr>
      <tr>
        <th>No Voucher</th>
        <th>Key Voucher</th>
        <th>Product Name</th>
        <th>Status</th>
        <th>No MM</th>
        <th>Activate Member</th>
        <th>Activated By</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($result['arraydata'] as $row) { ?>
        <tr>
          <td><?= $row->formno ?></td>
          <td><?= $row->vchkey ?></td>
          <td><?= $row->prdnm ?></td>
          <td><?= $row->status ?></td>
          <td><?= $row->no_mm ?></td>
          <td><?= $row->activate_dfno ?></td>
          <td><?= $row->activate_by ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php 

} 

setDatatable();
    
?>


<!-- <script>
$(document).ready(function() {
  $('.datatable').dataTable( {

    "sPaginationType": "bootstrap",
    "oLanguage": {

    }
  });
})
</script> -->
