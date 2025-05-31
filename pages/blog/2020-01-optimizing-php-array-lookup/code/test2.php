<?php
require('common.php');

list($invoices, $payments) = gen_rnd_data();

$invoicePayments = [];

foreach ($payments as $payment) {
    $invoicePayments[$payment->invoiceId][] = $payment;
}

$paid = 0;

foreach ($invoices as $invoice) {
    $pmts = $invoicePayments[$invoice->id] ?? [];

    if (count($pmts) > 0) {
        $paid++;
    }
}

echo "Paid invoices: $paid\n";
