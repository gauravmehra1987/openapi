<?php
class ControllerFeedHansoftzFeed extends Controller {
	private $error = array();
        
        private $settings = array();

	public function index() {
		$this->load->language('feed/hansoftz_feed');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hansoftz_feed', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_import'] = $this->language->get('text_import');

		
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_import'] = $this->language->get('button_import');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_category_add'] = $this->language->get('button_category_add');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_feed'),
			'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('feed/hansoftz_feed', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('feed/hansoftz_feed', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		$data['data_feed'] = HTTP_CATALOG . 'index.php?route=feed/hansoftz_feed';

                $this->load->model('setting/setting');
                $data['_xx'] = $this->model_setting_setting->getSetting('hansoftz_feed');
                
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('feed/hansoftz_feed.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'feed/hansoftz_feed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('feed/hansoftz_feed');

		$this->model_feed_hansoftz_feed->install();
	}

	public function uninstall() {
		$this->load->model('feed/hansoftz_feed');

		$this->model_feed_hansoftz_feed->uninstall();
	}
        
        private function _loadSettings(){
            $this->load->model('setting/setting');
            $this->settings = $this->model_setting_setting->getSetting('hansoftz_feed');
            return $this;
        }
        
        private function _xx($key){
            return (isset($this->settings['hansoftz_feed_'.$key]))?$this->settings['hansoftz_feed_'.$key]:'null';
        }

        public function execute(){
            
            //Load the feed model
            $this->load->model('feed/hansoftz_feed');
            
            //Load default settings
            $this->_loadSettings();
            $this->_xx('fk_feed_status');
            
            if($this->_xx('fk_feed_status')){
                $fk = $this->model_feed_hansoftz_feed
                    ->setApi($this->_xx('fk_feed_uri'))
                    ->setHeader(array(
                        'Cache-Control: max-age=999999',
                        'Fk-Affiliate-Id: '.$this->_xx('fk_tracking_id'),
                        'Fk-Affiliate-Token: '.$this->_xx('fk_token')
                    ))
                    ->setJson()

                    ->setVersion($this->_xx('fk_api_version'))

                    ->setCats(explode(',', $this->_xx('fk_cats')))
                    ->run();
            }
            
        }
        
        public function _l($mesg){
            echo date("Y-m-d h:i:s " . $mesg);
        }
}
