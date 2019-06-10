<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.04.2019
 * Time: 17:11
 */

class Image
{

    public static function DownloadImage()
    {

        $flag = false;
        $already_uploaded = false;
        if (empty($_FILES['myfile']['tmp_name'])) return ['already_uploaded' => $already_uploaded, 'flag' => $flag];

        $view = (int)0;
        $name = $_POST['NameImg'];
        $uploadDir = Config::get('UPLOAD_DIR');
        $uploadSmallDir = Config::get('UPLOAD_SMALL_DIR');
        $myHashFile = hash_file('md5', $_FILES['myfile']['tmp_name']). '.'. end(explode(".", $_FILES['myfile']['name']));
        $myFileName = $_FILES['myfile']['name'];
        $destination = $uploadDir . '/' . $myHashFile;
        $destinationSmall = $uploadSmallDir . $myHashFile;

        if (file_exists($destination))
        {
            $sql = "SELECT * FROM `galery` WHERE hash_file = '$myHashFile';";
            $result = db::getInstance()->Select($sql);
            if ($result)
            {
                $already_uploaded = true;
            }
        } else {
            if (move_uploaded_file($_FILES['myfile']['tmp_name'], $destination))
            {
                $sql = "INSERT INTO galery (name_foto, hash_file, name_file, view) VALUES ('$myFileName', '$myHashFile', '$name', '$view')";
                db::getInstance()->Query($sql);

                Resize::create_thumbnail($destination, $destinationSmall, 320, 320);

                $already_uploaded = false;
                $flag = true;
            } else {
                $flag = false;
            }
        }

        return ['already_uploaded' => $already_uploaded, 'flag' => $flag];
    }

    /**
     * @return array of 2 values if image moved from temporary directory to image directory.
     * @value flag is true because image moved from temporary directory.
     * @value image_name is hash name of image.
     */
    public static function downloadImageUser() {

        if (!empty($_FILES['img_user']['name'])) {

            $nameHash = hash('sha256', $_FILES['img_user']['tmp_name']). '.'.
                end(explode(".", $_FILES['img_user']['name']));
            $dirSave = 'img' . "/" . $nameHash;

            if (move_uploaded_file($_FILES['img_user']['tmp_name'], $dirSave)) {

                return ['flag' => true, 'image_name' => $nameHash];

                } else {

                return false;

                }
        } else {
            return false;
        }
    }
}