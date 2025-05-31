<?php

class Invoice
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}

class Payment
{
    public $id;
    public $invoiceId;

    public function __construct($id, $invoiceId)
    {
        $this->id = $id;
        $this->invoiceId = $invoiceId;
    }
}

function gen_rnd_data()
{
    $invoices = [];
    $payments = [];

    for ($i = 1; $i <= 25000; $i++) {
        $invoices[] = new Invoice($i);
        $payments[] = new Payment($i, mt_rand(1, 25000));
    }

    return [$invoices, $payments];
}
