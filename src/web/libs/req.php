$input = file_get_contents('php://input');
                // continue if $_POST is empty
            if(strlen($input) > 0 && count($_POST) == 0 || count($_POST) > 0) {
                $postsize = "---". sha1(strlen($input))."---";
                preg_match_all('/([-]{2,})([^\s]+)[\n|\s]{0,}/',$input,$matches);
                // update the input
                if(count($matches) > 0 ) $input = preg_replace('/([-]{2,})([^\s]+)[\n|\s]{0,}/','',$input);
                // extract the content-disposition
                if(count($matches) > 0 && count($matches[0]) > 0 )
                {
                  $keys = $matches[2];
                  
                  foreach($keys as $index => $key){
                      $key = trim($key);
                      $key = preg_replace('/^["]/','',$key);
                      $key = preg_replace('/["]$/','',$key);
                      $key = preg_replace('/[\s]/','',$key);
                      $keys[$index] = $key;
                  }

                  $input = preg_replace("/(Content-Disposition: form-data; name=)(.*)/m",$postsize,$input);

                  $input = preg_replace("/(Content-Length: )+([^\n])/im",'',$index);

                    //   getting the key values
                  $inputArr = explode($postsize,$input);
                  $values =[];
                  foreach($inputArr as $index =>$val){
                      $val = preg_match('/[\n]/','',$val);
                      if(preg_match('/[\S]/',$val)) $values[$index] = trim($val);
                  }

                  $post = [];

                  $value =[];

                  foreach($values as $i => $val) $value[] = $val;

                  foreach($keys as $x=>$key) $post[$key] = isset($value[$x])? $value[$x] : '';

                  if(is_array($post)){
                      $newPost = [];

                      foreach($post as $key => $val){
                          if(preg_match('/[\[]/',$key)){
                              $k = substr($key,0,strpos($key,'['));
                              $child = substr($key,strpos($key,'['));
                              $child = preg_replace('/[\[|\]]/','',$child);
                              $newPost[$k][$child] = $val;
                          }else{
                              $newPost[$key] = $val;
                          }
                      }

                      debug_dump([$newPost,$post]);
                      $_POST = count($newPost) > 0 ? $newPost : $post;
                  }
                }
            }