<?php

class ControllerPaymentCubits extends Controller
{

    /**
     * @var array
     */
    private $error = array();

    /**
     * @var string
     */
    private $payment_module_name  = 'cubits';

    /**
     * @return boolean
     */
    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/'.$this->payment_module_name))
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['cubits_key'])
        {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }
        if (!$this->request->post['cubits_secret'])
        {
            $this->error['secret_key'] = $this->language->get('error_secret');
        }

        if (!$this->error)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     */
    public function index()
    {
        $this->load->language('payment/'.$this->payment_module_name);
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()))
        {
            $this->model_setting_setting->editSetting($this->payment_module_name, $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
        }
        if (isset($this->error['warning']))
        {
            $data['error_warning'] = $this->error['warning'];
        }
        else
        {
            $data['error_warning'] = '';
        }

        //$this->document->title = $this->language->get('heading_title'); // for 1.4.9
        $this->document->setTitle($this->language->get('heading_title')); // for 1.5.0

        $data['heading_title']           = $this->language->get('heading_title');
        $data['text_enabled']            = $this->language->get('text_enabled');
        $data['text_disabled']           = $this->language->get('text_disabled');
        $data['text_high']               = $this->language->get('text_high');
        $data['text_medium']             = $this->language->get('text_medium');
        $data['text_low']                = $this->language->get('text_low');
        $data['entry_key']               = $this->language->get('entry_key');
        $data['entry_secret']            = $this->language->get('entry_secret');
        $data['entry_confirmed_status']  = $this->language->get('entry_confirmed_status');
        $data['entry_invalid_status']    = $this->language->get('entry_invalid_status');
        $data['entry_pending_status']    = $this->language->get('entry_pending_status');


        $data['entry_status']            = $this->language->get('entry_status');
        $data['entry_sort_order']        = $this->language->get('entry_sort_order');
        $data['button_save']             = $this->language->get('button_save');
        $data['button_cancel']           = $this->language->get('button_cancel');
        $data['tab_general']             = $this->language->get('tab_general');

        if (isset($this->error['api_key']))
        {
            $data['error_api_key'] = $this->error['api_key'];
        }
        else
        {
            $data['error_api_key'] = '';
        }
        if (isset($this->error['secret_key']))
        {
            $data['error_secret'] = $this->error['secret_key'];
        }
        else
        {
            $data['error_secret'] = '';
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/cubits', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['action'] = HTTPS_SERVER . 'index.php?route=payment/'.$this->payment_module_name.'&token=' . $this->session->data['token'];
        $data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post[$this->payment_module_name.'_key']))
        {
            $data[$this->payment_module_name.'_key'] = $this->request->post[$this->payment_module_name.'_key'];
        }
        else
        {
            $data[$this->payment_module_name.'_key'] = $this->config->get($this->payment_module_name.'_key');
        }

        if (isset($this->request->post[$this->payment_module_name.'_key']))
        {
            $data[$this->payment_module_name.'_secret'] = $this->request->post[$this->payment_module_name.'_secret'];
        }
        else
        {
            $data[$this->payment_module_name.'_secret'] = $this->config->get($this->payment_module_name.'_secret');
        }


        if (isset($this->request->post[$this->payment_module_name.'_confirmed_status_id']))
        {
            $data[$this->payment_module_name.'_confirmed_status_id'] = $this->request->post[$this->payment_module_name.'_confirmed_status_id'];
        }
        else
        {
            $data[$this->payment_module_name.'_confirmed_status_id'] = $this->config->get($this->payment_module_name.'_confirmed_status_id');
        }

        if (isset($this->request->post[$this->payment_module_name.'_invalid_status_id']))
        {
            $data[$this->payment_module_name.'_invalid_status_id'] = $this->request->post[$this->payment_module_name.'_invalid_status_id'];
        }
        else
        {
            $data[$this->payment_module_name.'_invalid_status_id'] = $this->config->get($this->payment_module_name.'_invalid_status_id');
        }

        if (isset($this->request->post[$this->payment_module_name.'_pending_status_id']))
        {
            $data[$this->payment_module_name.'_pending_status_id'] = $this->request->post[$this->payment_module_name.'_pending_status_id'];
        }
        else
        {
            $data[$this->payment_module_name.'_pending_status_id'] = $this->config->get($this->payment_module_name.'_pending_status_id');
        }

        if (isset($this->request->post[$this->payment_module_name.'_status']))
        {
            $data[$this->payment_module_name.'_status'] = $this->request->post[$this->payment_module_name.'_status'];
        }
        else
        {
            $data[$this->payment_module_name.'_status'] = $this->config->get($this->payment_module_name.'_status');
        }

        if (isset($this->request->post[$this->payment_module_name.'_sort_order']))
        {
            $data[$this->payment_module_name.'_sort_order'] = $this->request->post[$this->payment_module_name.'_sort_order'];
        }
        else
        {
            $data[$this->payment_module_name.'_sort_order'] = $this->config->get($this->payment_module_name.'_sort_order');
        }

        $this->template = 'payment/'.$this->payment_module_name.'.tpl';

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/cubits.tpl', $data), $this->config->get('config_compression'));
    }
}
