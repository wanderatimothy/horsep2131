<?php

namespace core\aggregates\services;

use web\libs\Request;

class ValidationService {

 
    public static function validate_email($email){
        return filter_var($email,FILTER_VALIDATE_EMAIL) != false;
    }

    public static function validate_password(string $password){
        return preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password);    
    }

    public static function required(string $key , $object){
        $props = get_object_vars($object);
        return (array_key_exists($key,$props) && !empty($props[$key]) );
    }

    public static function not_undefined(string $key , $object){
        $props = get_object_vars($object);
        return ( self::required($key,$object) && ($props[$key]  != 'undefined') );
    }

    public static function validate_phonenumber($phone){
        $phone = str_replace(' ','',$phone);
        return ( ctype_digit($phone) && ((strlen($phone) == 10) || (strlen($phone) == 13)));
    }


    public static function UserForm(Request $request){


        $errors = [];

        if(!self::validate_email($request->body->email)) $errors['email'] = 'This email is invalid';
        
        if(!self::validate_password($request->body->password))  $errors['password'] = 'This password is weak (i.e  contain at least an uppercase letter , symbol and should be more than eight characters )';

        if(!self::validate_phonenumber($request->body->contact))  $errors['contact'] = 'This contact is invalid';

        return $errors;

    }

    public static function is_number($key , $object){
        $props = get_object_vars($object);
        return (ctype_digit($props[$key]));
    }

    public static function LoginForm(Request $request){
        $errors = [];
        if(!self::validate_email($request->body->email)) $errors['email'] = 'provide a valid email' ;
        if(!self::required('password',$request->body)) $errors['password'] = 'password is required';
        return $errors;
        
    }




    public static function LandlordForm(Request $request){
        $errors = [];
        if(!self::not_undefined('names',$request->body)) $errors['names'] = 'Names are required';
        if(!self::validate_email($request->body->email)) $errors['email'] = 'Provide a valid email' ;
        if(!self::validate_phonenumber($request->body->contact))  $errors['contact'] = 'Provide a valid contact';
        if(!self::not_undefined('commission',$request->body)) $errors['commission'] = 'Commission is required';
        if(!self::is_number('commission',$request->body)) $errors['commission'] = 'Commission should be number';

        return $errors;

    }            


   public static function PropertyForm(Request $request){
       $errors = [];
       if(!self::not_undefined('label',$request->body)) $errors['label'] = 'Property label is required';
       if(!self::not_undefined('location',$request->body)) $errors['location'] = 'Property  location is required';
       if(!self::not_undefined('landlord_id',$request->body)) $errors['landlord_id'] = 'Please Choose a landlord';
       if(!self::not_undefined('type',$request->body)) $errors['type'] = 'Please Choose a type';
       if(!self::is_number('rent_amount',$request->body)) $errors['rent_amount'] = 'Rent Amount Should be a number';
    
       return $errors;
   }

   public static function UnitForm(Request $request){
    $errors = [];
    if(!self::not_undefined('prefix',$request->body)) $errors['prefix'] = ' Label or Label Prefix  is required';
    if(!self::is_number('rent_amount',$request->body)) $errors['rent_amount'] = 'Rent Amount Should be a number';
    if(!self::is_number('allowed_occupants',$request->body)) $errors['allowed_occupants'] = 'Number of occupants is required';
    if(!self::is_number('floor_id',$request->body)) $errors['floor_id'] = 'Please select the floor';
    if(!self::is_number('rooms',$request->body)) $errors['rooms'] = 'Rooms should be a number';
    if(!self::is_number('facilities',$request->body)) $errors['facilities'] = 'Facilities should be a number';

    if( isset($request->body->auto_generate)){
        if(!self::is_number('number_to_create',$request->body)) $errors['number_to_create'] = 'Enter the number of units to generate';
        if(!self::is_number('start_index',$request->body)) $errors['start_index'] = 'Enter a valid Start Index';
    }
    return $errors;
   }


   public static function TenantForm(Request $request){
    $errors = [];
    if($request->hasFiles()){
        $tenant_photo = $request->files->tenant_photo;
        if($tenant_photo['size']> __MAX_UPLOAD_SIZE) $errors['tenant_photo'] = "File size should not exceed 4 mbs";
        if($tenant_photo['error'])  $errors["tenant_photo"] = "File has an error";
        if(!in_array(strtolower(trim($tenant_photo['type'])) , ['image/png' , 'image/jpeg' , 'image/gif' , 'image/ico'])) $errors["tenant_photo"] = "Please choose a PNG or JPEG file";
    }
    if(!self::not_undefined('tenant_names',$request->body)) $errors['tenant_names'] = 'Tenant names are required';
    if(!self::validate_email($request->body->email)) $errors['email'] = 'Provide a valid email' ;
    if(!self::validate_phonenumber($request->body->contact))  $errors['contact'] = 'Provide a valid contact';
    if(!self::not_undefined('gender',$request->body)) $errors['gender'] = 'Select a gender';
    if(!self::is_number('unit_id',$request->body)) $errors['unit_id'] = 'Allocate the tenant to a unit';
    if(!self::is_number('pets_population',$request->body)) $errors['pets_population'] = 'Pets population should be a number';
    if(!self::is_number('population',$request->body)) $errors['population'] = 'population should be a number';
    if(!self::not_undefined('emergency_person',$request->body)) $errors['emergency_person'] = 'emergency person is required';
    if(!self::validate_phonenumber($request->body->emergency_contact))  $errors['emergency_contact'] = 'Provide a valid contact';

    return $errors;

   }


   public static function AccountForm(Request $request){
       $errors = [];
       if(!self::validate_email($request->body->business_email)) $errors['business_email'] = 'Provide a valid email' ;
       if(!self::validate_phonenumber($request->body->business_contact))  $errors['business_contact'] = 'Provide a valid contact';
       if(!self::not_undefined('business_name',$request->body)) $errors['business_name'] = 'Business Name is required';
       if(!self::not_undefined('business_address',$request->body)) $errors['business_address'] = 'Business address is required';
       if($request->hasFiles()){
        if($request->files->business_logo['size']> __MAX_UPLOAD_SIZE) $errors['business_logo'] = "File size should not exceed 4 mbs";
        if($request->files->business_logo['error']) $errors['business_logo'] = "Choose another file please";
        if(!in_array(strtolower(trim($request->files->business_logo['type'])) , ['image/png' , 'image/jpeg' , 'image/gif' , 'image/ico'])) $errors["tenant_photo"] = "Please choose a PNG or JPEG file";
       }
       return $errors;
   }


   public static function rentRuleForm(Request $request){
       $errors = [];
       if(!self::not_undefined('rule_title',$request->body)) $errors['rule_title'] = 'Provide a title to your rule';
       if(!self::not_undefined('rule_definition',$request->body)) $errors['rule_definition'] = 'Provide a definition for your rule';
       if(!self::not_undefined('payment_period',$request->body)) $errors['payment_period'] = 'Choose a payment schedule';
       if(!self::not_undefined('grace_period',$request->body)) $errors['grace_period'] = 'Choose a grace period';
       return $errors;
   }    

}