<?php
require('common.php');

list($invoices, $payments) = gen_rnd_data();

$paid = 0;

foreach ($invoices as $invoice) {
    foreach ($payments as $payment) {
        if ($invoice->id == $payment->invoiceId) {
            $paid++;
            break;
        }
    }
}

echo "Paid invoices: $paid\n";
