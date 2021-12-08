<?php
    // create function to call api call, and graphql
    class Shopify{
        public $shop_url;
        public $access_token;

        public function set_url($url){
            $this->shop_url = $url;
        }

        public function set_token($token){
            $this->access_token = $token;
        }

        public function get_url(){
            return $this->shop_url;
        }

        public function get_token(){
            return $this->access_token;
        }

        //we need api endpoint, method, and query
        // Shopify admin endpoint : /admin/api/2021-04/products.json
        public function rest_api($api_endpoint, $query = array(), $method = 'GET'){
            $url = 'https://' . $this->shop_url . $api_endpoint;

            //if there are 'GET' or 'DELETE' in method variable, we are going to concatenate the value of the query
            if(in_array($method, array('GET', 'DELETE')) && !is_null($query)){
                $url = $url . '?' . http_build_query($query);
            }

            //initialize curl
            $curl = curl_init($url);
            
            //setup option
            curl_setopt($curl, CURLOPT_HEADER, true); 
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //will have response instead of output it
            curl_setopt($curl, CURLOPT_FOLLOWLOCATGION, true); //allows your location in the header
            curl_setopt($curl, CURLOPT_MAXDIRS, 3);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);

            //pass the method
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

            //access token
            $headers[] = "";
            if(!is_null($this->$access_token)){
                $headers[] = "X-Shopify-Access-Token " . $this->access_token;

                //pass that header to our http header
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }

            //going to check if the method is post or put
            if($method != 'GET' && in_array($method, array('POST', 'PUT'))){
                
                if(is_array($query)) $query = http_build_query($query);
                //pass the query to the post field
                curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
            }

            $response = curl_exec($curl);
            $error = curl_errno($curl);
            $error_msg = curl_error($curl);

            curl_close($curl);

            //check error
            if($error){
                return $error_msg;
            }else {
               // split the response into header and the body
               $response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

               //echo print_r($response);

                $headers = array();
                $headers_content = explode("\n", $response[0]); //retrieve first array
                $headers['status'] = $headers_content[0];
            
                array_shift($headers_content);

                foreach($headers_content as $content){
                    $data = explode(':', $content);
                    $headers[trim($data[0])] = trim($data[1]);
                }

                // echo print_r($headers);
                // echo print_r($response[1]);

                return array('headers' => $headers, 'body'=>$response[1]);
            }

        }
    }