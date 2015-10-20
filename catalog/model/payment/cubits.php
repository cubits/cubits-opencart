<?php

class ModelPaymentCubits extends Model
{

    /**
     * @param string $address
     *
     * @return array
     */
    public function getMethod($address)
    {
        $this->load->language('payment/cubits');

        if ($this->config->get('cubits_status'))
        {
            $status = TRUE;
        }
        else
        {
            $status = FALSE;
        }

        $method_data = array();

        if ($status)
        {
            $method_data = array(
                'code'       => 'cubits',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('cubits_sort_order'),
            );
        }

        return $method_data;
    }
}
