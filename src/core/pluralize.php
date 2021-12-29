<?php

namespace core;


class pluralize {

  public $consonants = ['b','c','d','g','f','k','l','h','m','n','p','q','r','t','v','w','x','y','z'];
  public $vowels = ['a','e'  , 'i' , 'o' ,'u'];
  public $words_without_plural = ['fish','sheep' , 'you' , 'i' , 'the' ,'we','people'  /** more when mind works add */];
  public $special_plural = [
    'this' => 'these',
    'that' =>'those',
    'ox' => 'oxen',
    'miss'=>'miss',
    'mouse' => 'mice',
    'louse' => 'lice',
    'goose' => 'geese',
    'mongoose'=> 'mongeese',
    'man' => 'men',
    'woman' => 'women',
    'child' => 'children'
  ];
  public $output;

  function __construct(string $word){
    $word = $this->clean($word);

    if(in_array($word,$this->words_without_plural)){
      $this->output = $word;
      return;
    }

    if(in_array($word , array_keys($this->special_plural))){
      $this->output = $this->special_plural[$word];
      return;
    }

    $length = strlen($word);
    $last = $word[$length - 1];
    $second_last = $word[$length - 2];
    $third_last =  $word[$length-3];
    if(in_array($last,$this->consonants)){
      if($last == 'y' && !in_array($second_last,$this->vowels)){
        $this->output = rtrim($word,'y').'ies';
        return;
      }
      if($last == 'h' &&  ($second_last == 't' || $second_last == 'c')){
        $this->output = $word.'es';
        return;
      }
      if(($last == 's' &&  $second_last == 's') || $last == 'x' ){
        $this->output = $word.'es';
        return;
      }
      

      $this->output = $word.'s';
    }else{
      if($last == 'e' && $second_last == 'y'){
        $this->output = rtrim($word,'ye').'ies';
        return;
      }

      if($last == 'o' && $second_last == 'g'){
        $this->output = $word.'es';
        return;
      }

      $this->output = $word.'s';

    }

  }

  function clean($word){
    return strtolower(trim($word,' '));
  }

  public static function plural(string $word){
    $p = new pluralize($word);
    return $p->output;
  }




}

?>
