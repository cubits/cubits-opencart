<?php
class ControllerPaymentCubits extends Controller
{

    /**
     * @var string
     */
    private $payment_module_name  = 'cubits';

    /**
     */
    public function index()
    {
        $this->language->load('payment/'.$this->payment_module_name);

        $data['button_cubits_confirm'] = $this->language->get('button_cubits_confirm');
        $data['continue']              = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cubits.tpl'))
        {
          echo 'aaaa';
          return $this->load->view($this->config->get('config_template') . '/template/payment/cubits.tpl', $data);
        }
        else
        {
          echo 'bbb';
          return $this->load->view('default/template/payment/cubits.tpl', $data);
		    }

	  }

    /**
     * @param string $contents
     */
    function log($contents)
    {
      error_log($contents);
    }

    public function send()
    {
      require DIR_APPLICATION.'../cubits-php/lib/Cubits.php';

      $this->load->model('checkout/order');

      $order   = $this->model_checkout_order->getOrder($this->session->data['order_id']);
      $price   = $this->currency->format($order['total'], $order['currency_code'], $order['currency_value'], false);
      $key     = $this->config->get($this->payment_module_name.'_key');
      $secret  = $this->config->get($this->payment_module_name.'_secret');


      Cubits::configure("https://pay.cubits.com/api/v1/",true);
      $cubits     = Cubits::withApiKey($key, $secret);
      $ordered_products = $this->cart->getProducts();

      $names = array();
      $description = array();
      foreach ($ordered_products as $product) {
        $description[] = $product['quantity'].' x '.$product['name'];
      }
      $description = implode(', ', $description);
      $name = 'Order Id: '.$order['order_id'];
      if (strlen($name) > 256){
        $names = substr($names, 0, 253).'...';
      }
      if (strlen($description) > 512){
        $description = substr($description, 0, 509).'...';
      }

      $options = array(
        'callback_url'  => $this->url->link('payment/cubits/callback'),
        'success_url'   => $this->url->link('checkout/success'),
        'cancel_url'    => $this->url->link('account/order/info&order_id=' . $order['order_id']),
        'reference'     => $order['order_id'],
        'description'   => $description
      );
      $response = $cubits->createInvoice($name, $order['total'], $order['currency_code'], $options);
      if(!$response->invoice_url)
      {
          $this->log("communication error");
		      $this->log(var_export($response['error'], true));
          echo "{\"error\": \"Error: Problem communicating with payment provider.\\nPlease try again later.\"}";
          echo var_dump($response);
      }
      else
      {
          echo "{\"url\": \"" . $response->invoice_url . "\"}";
      }
    }

    /**
     */
    public function callback()
    {
      require DIR_APPLICATION.'../cubits-php/lib/Cubits.php';

      $key = $this->config->get($this->payment_module_name.'_key');
      $secret = $this->config->get($this->payment_module_name.'_secret');


      Cubits::configure("https://pay.cubits.com/api/v1/",true);
      $cubits = Cubits::withApiKey($key, $secret);

      $params = json_decode(file_get_contents('php://input'));
      $payment_id = $params->id;
      $order_id_ref = (int)$params->reference;

      $invoice_data = $cubits->getInvoice($payment_id);
      $order_id = (int)$invoice_data->reference;


      if($order_id == $order_id_ref){
        $this->load->model('checkout/order');
        $order    = $this->model_checkout_order->getOrder($order_id_ref);
        echo var_dump($order);
        switch ($invoice_data->status) {
        case 'completed':
        case 'overpaid':
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cubits_confirmed_status_id'), '', true);
        break;
        case 'pending':
        case 'underpaid':
        case 'unconfirmed':
          $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cubits_pending_status_id'), '', true);
        break;
        case 'aborted':
        case 'timeout':
          $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('cubits_invalid_status_id'), '', true);
        break;
        }
      }
    }
}
