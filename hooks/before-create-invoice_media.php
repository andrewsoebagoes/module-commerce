<?php 

use Modules\Default\Libraries\Sdk\Media;

$media = Media::singleUpload($_FILES['media_id']);

$data['media_id'] = $media->id;

$data['invoice_id'] = $_GET['filter']['invoice_id'];

?>