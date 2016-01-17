<?php
class ModelFeedSnapdealFeed extends Model {
    
        private $headers;
        private $cats;
        private $version;
        private $api;
        private $json;
        private $product_json;
        private $category_uri;
        private $collection;
        private $categories;
        private $file;
        
        public function install() {
		
	}

	public function uninstall() {
		
	}
        
        private function sendRequest($url, $timeout=30){
    	//Make sure cURL is available
    	if (function_exists('curl_init') && function_exists('curl_setopt')){
	        //The headers are required for authentication
	        
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
	        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
                    $result = curl_exec($ch);
                
	        curl_close($ch);
	        if($result)
                    return json_decode ($result);
                else throw new Exception('No response recieved from the API.');
	    }else{
                throw new Exception('Curl is not enabled.');
		return false;
	    }        
        }
        
        public function setCats($cats) {
            $this->cats = $cats;
            return $this;
            
        }

        public function setVersion($version) {
            $this->version = $version;
            return $this;
        }

        public function setApi($api) {
            $this->api = $api;
            return $this;
        }
        
        public function setJson() {
            try{
                $this->json = $this->sendRequest($this->api);
            }catch(Exception $e){
                echo 'Internal exception: ',  $e->getMessage(), "\n";
            }
            return $this;
        }

                
        public function setHeader($header){
            $this->headers = $header;
            return $this;
        }
        
        public function setProductJson(){
            try{
                $this->product_json = $this->sendRequest($this->category_uri);
                
                if(file_exists($this->file)) unlink($this->file);
                
                $handle = fopen($this->file, 'w+');
                
                fwrite($handle,  $this->product_json->nextUrl);

                fclose($handle);
            
            }catch(Exception $e){
                echo 'Internal exception: ',  $e->getMessage(), "\n";
            }
        }

        public function kickstart($url){
            try{
                $this->product_json = $this->sendRequest($url);
                
                if(file_exists($this->file)) unlink($this->file);
                
                $handle = fopen($this->file, 'w+');
                
                fwrite($handle,  $this->product_json->nextUrl);

                fclose($handle);
                
            }catch(Exception $e){
                echo 'Internal exception: ',  $e->getMessage(), "\n";
            }
        }
        
        public function run(){
            $this->file = $file = DIR_LOGS . 'next.json'  . '.txt';
            $this->load->model('feed/hansoftz_feed_opencart_bridge');
            $this->_l("Starting the Feed script...");
            $this->categories = $this->model_feed_hansoftz_feed_opencart_bridge->getCategoryPaths();
            $last_run = 0;
            if(file_exists($this->file)){
                $last_modified = date ("Y-m-d H:i:s", filemtime($this->file));
                $today = date("Y-m-d H:i:s");
                $to_time = strtotime($today);
                $from_time = strtotime($last_modified);
                $last_run = round(abs($to_time - $from_time) / 60,2);
            }
            
            if($last_run > 10){
                echo "New Run...\n";
                foreach($this->cats as $category){
                    $this->category_uri = $this->json->apiGroups->Affiliate->listingsAvailable->{$category}->listingVersions->{$this->version}->get;
                    $this->_l("category url: " . $this->category_uri);
                    $this->setProductJson();
                    $this->loopProductJson();
                }
            }else{
                echo "Resume Run...\n";
                $this->kickstart(file_get_contents($this->file));
                $this->loopProductJson();
            }
        }
        
        private function loopProductJson(){
            
            $language_id = $this->config->get('config_language_id');
            
            if($this->product_json){
                foreach($this->product_json->products as $key=>$product){
                    // We are only interested in InStock Product
                
                    if($product->availability=='in stock' && $product->subCategoryName == 'Dress Material'){
                        
                        $this->collection[$key]['product_description'][$language_id] = array(
                            'name' => $product->title,
                            'meta_title' => $product->title,
                            'meta_description' => $product->description,
                            'description' => $product->description,
                        );
                        
                        $category_chain = "Womens>" . $product->categoryName .'>'. $product->subCategoryName;
                        
                        $image_path = strtolower(str_replace(' ','-',str_replace('>','/',$category_chain)));
                        if(isset($product->imageLink)){
                            $image_url = $product->imageLink;
                            $image = $product->imageLink; //$this->saveImage($product->imageLink,$image_path);
                        }else {$image = ""; $image_url='';}
                        $this->collection[$key]['product'] = array(
                            'model' => $product->id,
                            'sku' => 'snapdeal-' . $product->id,
                            'price' => $product->mrp,
                            'mrp' => $product->mrp,
                            'productUrl' => $product->link,
                            'image'     => $image,
                            'imageurl' => $image_url,
                            'discount' => $product->offerPrice,
                            'quantity'  =>  999,
                            'status'  =>  1,
                            'date_modified' => date("Y-m-d h:i:s"),
                            'manufacturer_id' => $this->model_feed_hansoftz_feed_opencart_bridge->saveManufacurer($product->brand),
                            'stock_status_id' => ($product->availability=='in stock')?1:0
                        );
                        
                        $this->collection[$key]['product_store'] = array(0);
                            
                       
                        
                        
                        // Fetch category and save
                        if (isset($this->categories[$category_chain])) {
                            $category_id = $this->categories[$category_chain];
                            if ($category_id)
                                $this->collection[$key]['product_category'][] = $category_id;
                        }else {
                            if (!empty($category_chain))
                                $categoryData = $this->model_feed_hansoftz_feed_opencart_bridge->saveCategory($category_chain);
                            if ($categoryData['id']){
                                $this->collection[$key]['product_category'][] = $categoryData['id'];
                                $this->categories[$categoryData['path']] = $categoryData['id'];
                            }
                        }
                       
                        
                    }else{
//                      OutofStock product has to be removed if exists in system
                        $product_id = $this->model_feed_hansoftz_feed_opencart_bridge->product_exists($product->id);
                        if($product_id)
                        	$this->model_feed_hansoftz_feed_opencart_bridge->deleteProduct($product_id);
                    }
                }
                
            }
            
            //Now save new product collection AND reset once all are saved.
            if(!empty($this->collection)){
                
                $this->_l("products found : " . count($this->collection));
                
                $this->saveCollection();
                $this->collection = array();
                $this->_l("slept for 5 sec");
                sleep(1);
            }
            
            //recursively call next url untill finished
            /*if(!empty($this->product_json->nextUrl)){
                $this->category_uri = $this->product_json->nextUrl;
                $this->_l("fetching next category : " . $this->category_uri);
                $this->setProductJson();
                $this->loopProductJson();
            }*/
        }
        
        public function saveCollection(){
           
            foreach($this->collection as $product){
                $product_id = $this->model_feed_hansoftz_feed_opencart_bridge->product_exists($product['product']['model']);
                if($product_id){
                    $this->_l("Editing existing product");
                    $this->model_feed_hansoftz_feed_opencart_bridge->editProduct($product_id,$product);
                }else{
                    $this->_l("Adding New product");
                    $this->model_feed_hansoftz_feed_opencart_bridge->addProduct($product);
                }
            }
        }

    public function _l($mesg){
            
            $file = DIR_LOGS . 'snap_data_feed'  . '.txt';

            $handle = fopen($file, 'a+');

            fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $mesg . "\n");

            fclose($handle);
    }
    
    public function saveImage($uri,$path){
        
        $image_name = substr($uri, strrpos($uri,'/'), strlen($uri));
        $dir = 'snapdeal/' .$path;
        if(!file_exists(DIR_IMAGE . $dir . $image_name)){
            if(!is_dir(DIR_IMAGE . $dir))
                mkdir(DIR_IMAGE . $dir,0777,TRUE);

            
            if($this->checkRemoteFile($uri)){
                copy($uri,DIR_IMAGE . $dir . $image_name);
            }else{
                $uri = str_replace("i1.sdlcdn.com","n1.sdlcdn.com",$uri);
                if($this->checkRemoteFile($uri)){
                    copy($uri,DIR_IMAGE . $dir . $image_name);
                }else{
                    return "";
                }

            }
        }
        return $dir . '/' . $image_name;
    }
    
    public function checkRemoteFile($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    
    
}
