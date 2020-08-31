<?php

foreach ($data['assets_scripts'] as $value) {
  if($value['header'] && ($value['page'] == $page || $value['page'] == false))
  {
    ?>
    <script type="text/javascript" src="<?= $value['url'] ?>?v=<?= $value['version'] ?>"></script>
    <?php
  }
}

foreach ($data['assets_styles'] as $value) {
  if($value['header'] && ($value['page'] == $page || $value['page'] == false))
  {
    ?>
    <link href="<?= $value['url'] ?>?v=<?= $value['version'] ?>" type="text/css" rel="stylesheet" />
    <?php
  }
}
?>
