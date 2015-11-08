<?php
/**
* アップロード
**/
class Upload {
  /**
  * アップロード画像の保存
  * @param String $userFile ファイルname属性
  * @param String $save_path 保存場所
  * @return String 保存した画像のパス
  */
  public static function saveImage($userFile, $save_path) {
    if (isset($_FILES[$userFile]['error']) && is_int($_FILES[$userFile]['error'])) {
      try {
        switch ($_FILES[$userFile]['error']) {
          case UPLOAD_ERR_OK:
            break;
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('upload file size overflow');
          default:
            throw new RuntimeException('upload file Error');
        }

        if (!$img = @imagecreatefromstring(file_get_contents($_FILES[$userFile]['tmp_name']))) {
          throw new RuntimeException('upload file not supported');
        }
        $file_path = sprintf('%s%s.png', $save_path, sha1_file($_FILES[$userFile]['tmp_name']));
        imagesavealpha($img, true);
        if (!imagepng($img, $file_path, 5)) {
          throw new RuntimeException('upload file error');
        }
        imagedestroy($img);
      } catch (RuntimeException $e) {
        var_dump($e);
        return null;
      }
      return $file_path;
    }
    return null;
  }

}
