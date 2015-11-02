<?php
class ModelFeedHansoftzFeed extends Model {
    
        private $headers;
        private $cats;
        private $version;
        private $api;
        private $json;
        private $product_json;
        private $category_uri;
        private $collection;
        private $categories;
        
        
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
            }catch(Exception $e){
                echo 'Internal exception: ',  $e->getMessage(), "\n";
            }
        }

        
        public function run(){
            $this->load->model('feed/hansoftz_feed_opencart_bridge');
            
            $this->categories = $this->model_feed_hansoftz_feed_opencart_bridge->getCategoryPaths();
            foreach($this->cats as $category){
                $this->category_uri = $this->json->apiGroups->affiliate->apiListings->{$category}->availableVariants->{$this->version}->get;
                $this->setProductJson();
                $this->loopProductJson();
            }
        }
        
        private function loopProductJson(){
            
            $language_id = $this->config->get('config_language_id');
            if($this->product_json){
                foreach($this->product_json->productInfoList as $key=>$product){
                    // We are only interested in InStock Product
//                    if($product->productBaseInfo->productAttributes->inStock){
                        
                        $this->collection[$key]['product_description'][$language_id] = array(
                            'name' => $product->productBaseInfo->productAttributes->title,
                            'meta_title' => $product->productBaseInfo->productAttributes->title,
                            'meta_description' => $product->productBaseInfo->productAttributes->productDescription,
                            'description' => $product->productBaseInfo->productAttributes->productDescription,
                        );

                        $this->collection[$key]['product'] = array(
                            'model' => $product->productBaseInfo->productIdentifier->productId,
                            'price' => $product->productBaseInfo->productAttributes->sellingPrice->amount,
                            'mrp' => $product->productBaseInfo->productAttributes->maximumRetailPrice->amount,
                            'cod' => $product->productBaseInfo->productAttributes->codAvailable,
                            'emi' => $product->productBaseInfo->productAttributes->emiAvailable,
                            'sizeVariants' => $product->productBaseInfo->productAttributes->sizeVariants,
                            'colorVariants' => $product->productBaseInfo->productAttributes->colorVariants,
                            'productUrl' => $product->productBaseInfo->productAttributes->productUrl,
                            'discount' => $product->productBaseInfo->productAttributes->discountPercentage,
                            'manufacturer_id' => $this->model_feed_hansoftz_feed_opencart_bridge->saveManufacurer($product->productBaseInfo->productAttributes->productBrand),
                            'stock_status_id' => $product->productBaseInfo->productAttributes->inStock
                        );
                        
                        
                        // Fetch category and save
                        $category_chain = str_replace('Apparels>', '',$product->productBaseInfo->productIdentifier->categoryPaths->categoryPath[0][0]->title);
                        
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
                        
                        // Collect options to save after product is added/updated
                        
                        $this->collection[$key]['product_option'] = array(
                            array('name'=>"size","value"=>$product->productBaseInfo->productAttributes->size),
                            array('name'=>"color","value"=>$product->productBaseInfo->productAttributes->color),
                            array('name'=>"sizeUnit","value"=>$product->productBaseInfo->productAttributes->sizeUnit),
                        );
                        
                        
//                    }else{
                        // OutofStock product has to be removed if exists in system
//                    }
                }
                
                print_r($this->collection); exit;
            }
        }

    
}
