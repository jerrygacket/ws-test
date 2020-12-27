<?php
include ('db.php');
include ('ThumbGenerator.php');

/**
 * Формируем галерею для конкретного изображения с учетом размера экрана
 */
if (isset($_REQUEST['img'])) {
    $result = '<ul class="gallery">';
    foreach (ThumbGenerator::getSizes() as $sizeName => $size) {
        if (intval($_REQUEST['width']) < 768 && $sizeName === 'big') {
            continue;
        }
        if (intval($_REQUEST['width']) >= 992 && $sizeName === 'mic') {
            continue;
        }
        $result .= '
            <li>
                <a href="cache/'.$_REQUEST['img'].'/'.$sizeName.'/'.$_REQUEST['img'].'.jpg">
                    <img src="generator.php?name='.$_REQUEST['img'].'&size=mic" alt="'.$_REQUEST['img'].'">
                </a>
            </li>
            ';
    }
    $result .= '</ul>';
    echo json_encode(['result'=>$result]);
    exit;
}
?>
<!doctype html>
<html lang="ru">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="jquery.lightbox.css">
      <link rel="stylesheet" href="demo.css">
    <title>Галереи</title>
  </head>
  <body>
  <div class="container">
      <div class="row">

        <?php
        $fileList = array_diff(scandir('gallery'), ['.', '..']);
        foreach ($fileList as $key => &$item) {
            $item = str_replace('.jpg', '', $item);
            echo '
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a class="imgItem" data-image-id="'.$item.'" href="#"><img class="screen-img" src="generator.php?name='.$item.'&size=mic" alt="'.$item.'"></a>
                </div>          
            ';
        }
        ?>

        <h2>Галерея</h2>
        <div id="gallery"></div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="jquery.lightbox.js"></script>
        <script>
          $( ".imgItem" ).click(function() {
              $.post('demo.php', { img: $( this ).data("imageId"), width: screen.width, height:screen.height }, function(json) {
                  $('#gallery').html( json.result );
                  $('.gallery a').lightbox();
              },'json');
          });
        </script>
      </div>
  </div>
  </body>
</html>