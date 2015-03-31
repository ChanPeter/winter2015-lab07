<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
	// Build a list of orders
	
	// Present the list to choose from
	$this->data['pagebody'] = 'homepage';
        // Get directory of data folder
        $this->load->helper('directory');
        $map = directory_map('./data/');
        foreach($map as $file)
        {
            
            if((substr_compare($file, '.xml', strlen($file)-strlen('.xml'), strlen('.xml'))) === 0
                    && substr_compare($file, 'order', 0, strlen('order')) === 0)
            {
                // get customer name
                $this->order->init($file);
                $orders[] = array( 'order' => substr($file, 0, strlen($file)-strlen('.xml')),
                'file' => $file, 'customer' => $this->order->customer());
            }
            
        }
        //echo var_dump($orders);die();
        sort($orders);
        
        //echo var_dump($orders);die();
        $this->data['orders'] = $orders;

	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
        // Construct the order
        $this->order->init($filename);
        
	// Build a receipt for the chosen order
	
        // order number
        $this->data['orderNum'] = substr($filename, 0, strlen($filename)-strlen('.xml'));
        
        // Customer name
        $this->data['customer'] = $this->order->customer();
        
        // order type
        $this->data['orderType'] = $this->order->orderType();
        
        // burgers list
        $this->data['burgers'] = $this->order->burgers();   
        //$burgers[] = array();
        //$burger[] = array();
        //$burger['number'] = "1";
        //$burger['patty'] = "beef";
        //$burgers[] = $burger;
        //$this->data['burgers'] = $burgers;
        
        // burger number
        //$this->data['number'] = key($this->order->burgers);
        
        // special
        $this->data['special'] = $this->order->special();
        
        // Order total
        $this->data['orderTotal'] = $this->order->orderTotal();
        
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
