<?php
namespace calisia_ticket_system;

class uploader{
    public static function save_uploaded_files(){
        

        $files_count = count($_FILES['calisia_file_upload']['name']);
        if($files_count > 5){
            throw new \Exception('Maximum number of files (5) exceeded');
        }
 
        if(data::get_number_of_uploads(get_current_user_id()) >= 10){
            throw new \Exception('Maximum number of uploads exceeded. Try again later.');
        }
        $uploaded_files = array();

        for($i=0; $i<$files_count; $i++){
            //Get the temp file path
            $tmp_file_path = $_FILES['calisia_file_upload']['tmp_name'][$i];

            //Make sure we have a file path
            if ($tmp_file_path != ""){
                //Setup our new file path
                $file_extension = pathinfo(strtolower($_FILES['calisia_file_upload']['name'][$i]), PATHINFO_EXTENSION);
                $allowed_extensions = array('jpg','jpeg','png','pdf','doc','docx');
                if (!in_array($file_extension, $allowed_extensions)) {
                    throw new \Exception('File type not allowed. Allowed file types are: jpg, jpeg, png, pdf, doc, docx');
                }
                if($_FILES['calisia_file_upload']['size'][$i] > 2000000){
                    throw new \Exception('Maximum file size exceeded');
                }

                $new_file_name = md5($_FILES['calisia_file_upload']['size'][$i] . get_current_user_id() . time()) . '.' . $file_extension;
                $new_file_path = "/uploads/" . $new_file_name;

                //Upload the file into the temp dir
                if(move_uploaded_file($tmp_file_path, CALISIA_TICKET_SYSTEM_ROOT . $new_file_path)) {
                    $uploaded_files[] = array('path' => CALISIA_TICKET_SYSTEM_URL . "uploads/" . $new_file_name, 'name' => $new_file_name);
                }
            }else{
                throw new \Exception('There was a problem uploading one of the files (files cannot be bigger than 2MB)');
            }
        }
        return $uploaded_files;
    }

 
}