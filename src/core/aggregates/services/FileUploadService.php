<?php

namespace core\aggregates\services;


class FileUploadService {

    public $errors = [];

    public $uploadedFiles = [];

    public function uploadSingleImage($files ,$name = null){

        if($files->size  > __MAX_UPLOAD_SIZE) $this->errors[] = 'File too Big can not exceed 10 mbs !'; return false;
        
        if($files->size == 0 ||  !is_numeric($files->size)) $this->errors[] = 'This item has security threats can not  save it '; return  false;

        $extension = pathinfo($files->name,PATHINFO_EXTENSION);
        
        if(!in_array(strtolower($extension),['png' , 'jpeg' ,'jpg' , 'ico' , 'gif'])) $this->errors[] = 'Please choose an image (jpg,png, jpeg or ico) '; return false;

        if(move_uploaded_file($files->tmp_name , __APP_STORAGE__DIR.$name.'.'.$extension)) $this->uploadedFiles[] = $name.'.'.$extension; return true;

        return false;
        
    }


    function deleteUpload($filename){

        if(file_exists(__APP_STORAGE__DIR .$filename)) return unlink(__APP_STORAGE__DIR.$filename);

        return false;
    }





}