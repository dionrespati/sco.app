<?php
if($result == null) {
	echo setErrorMessage("No Result Found..");
} else {
    $filenm = "$kodestk-$form[type].xls";
    header("Content-type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename='.$filenm.'');
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
    header("Pragma: public");
?>
<table width="100%" border="1">
    <thead>
      <tr></tr>
      <tr>
        <th align="left">No. Trx</th>
        <th align="left" colspan="2"><?php echo $result[0]->trcd." - ".$result[0]->dfno ?></th>
      </tr>
      <tr>
        <th align="left">No. TTP</th>
        <th align="left" colspan="2"><?php echo $result[0]->orderno ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Kode Produk</th>
        <th width="55%">Nama Produk</th>
        <th width="10%">Qty</th>
      </tr>
    <?php
    $init_value = $result[0]->trcd;
    foreach ($result as $list) {
        if ($list->trcd == $init_value) { ?>
        <tr>
          <td><?php echo $list->prdcd ?></td>
          <td><?php echo $list->prdnm ?></td>
          <td><?php echo $list->qtyord ?></td>
        </tr>
      <?php } else { ?>
        <table width="100%" border="1">
          <thead>
            <tr></tr>
            <tr>
              <th align="left">No. Trx</th>
              <th align="left" colspan="2"><?php echo $list->trcd." - ".$list->dfno ?></th>
            </tr>
            <tr>
              <th align="left">No. TTP</th>
              <th align="left" colspan="2"><?php echo $list->orderno ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>Kode Produk</th>
              <th width="55%">Nama Produk</th>
              <th width="10%">Qty</th>
            </tr>
            <tr>
              <td><?php echo $list->prdcd ?></td>
              <td><?php echo $list->prdnm ?></td>
              <td><?php echo $list->qtyord ?></td>
            </tr>
          </tbody>
      <?php $init_value = $list->trcd;
        }
    } ?>
    </tbody>
</table>
<?php } ?>
