<?php

/**
 *
 * @author peter
 */
class Order extends CI_Model {

    protected $xml = null;
    protected $burgers = array();
    protected $orderTotal = 0.00;
    protected $count = 1;
    

    // Constructor
    public function __construct() {
        parent::__construct();
    }
    
    public function init($filename){
        $this->xml = simplexml_load_file(DATAPATH . $filename);

        // build a full list of burgers
        foreach ($this->xml->burger as $burger) {
            $record = array();
            $record['number'] = (string) $this->count++;
            $record['patty'] = (string) $burger->patty['type'];
            $record['cheeses'] = "";
            if(isset($burger->cheeses['top']))
                $record['cheeses'] .= $burger->cheeses['top'] . "(top) ";
            if(isset($burger->cheeses['bottom']))
                $record['cheeses'] .= $burger->cheeses['bottom'] . "(bottom) ";
            $record['toppings'] = "";
            if(isset($burger->topping))
                foreach($burger->topping as $topping)
                    $record['toppings'] .= $topping['type'] . " ";
            //$record['toppings'] = $toppings;
            $record['sauces'] = "";
            if(isset($burger->sauce))
                foreach($burger->sauce as $sauce)
                    $record['sauces'] .= $sauce['type'] . " ";
            $record['instructions'] = "";
            if(isset($burger->instructions))
                $record['instructions'] = (string) $burger->instructions;
            $record['name'] = "";
            if(isset($burger->name))
                $record['name'] = (string) $burger->name;
            
            //Burger total
            $total = $this->burgerTotal($burger);
            $record['burgerTotal'] = $total;
            
            //Order total
            $this->orderTotal += $total;
           
            $this->burgers[] = $record;
        }
    }

    // orderType
    function orderType()
    {
        return $this->xml['type'];
    }

    // customer name
    function customer() {
        return (string) $this->xml->customer;
    }
    
    // delivery
    function delivery()
    {
        if(isset($this->xml->special))
            return (string) $this->xml->delivery;
        else
            return "";
    }
    
    // burgers
    function burgers()
    {
        return $this->burgers;
    }
    
    // delivery
    function special()
    {
        if(isset($this->xml->special))
            return (string) $this->xml->special;
        else
            return "";
    }
    
    // calculate burger cost
    function burgerTotal($burger)
    {
        $total = 0.00;
        // patty
        $total += $this->menu->getPatty((string) $burger->patty['type'])->price;
        
        // cheese
        if(isset($burger->cheeses['top']))
        {
            $total += $this->menu->getCheese((string) $burger->cheeses['top'])->price;
        }
        if(isset($burger->cheeses['bottom']))
        {
            $total += $this->menu->getCheese((string) $burger->cheeses['bottom'])->price;
        }
        
        //topping
        foreach($burger->topping as $topping)
        {
            $total += $this->menu->getTopping((string) $topping['type'])->price;
        }
        
        return $total;
    }
    
    // Order Total
    function orderTotal()
    {
        return $this->orderTotal;
    }
}
