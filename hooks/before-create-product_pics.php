<?php 

use Modules\Default\Libraries\Sdk\Media;

$media = Media::singleUpload($_FILES['media_id']);

$data['media_id'] = $media->id;

$data['product_id'] = $_GET['filter']['product_id'];

?>