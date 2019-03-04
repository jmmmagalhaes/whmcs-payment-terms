<?php
/*
* WHMCS hook to adjust the payment terms of invoices automatically.
*/

function setDueDate($vars)
{
// Number of days client has to pay an invoice
$PAYMENT_TERM = 14;
// Get the invoice id
$invoiceId = $vars['invoiceid'];
$params = array(
'invoiceid' => $invoiceId
);
// Get invoice and cast to an Object (I prefer working with objects)
$invoice = (object) localAPI('GetInvoice', $params);
//if ($invoice->result == 'success' && $invoice->paymentmethod == 'banktransfer') {
if ($invoice->result == 'success') {
// Calculate the due date
$dueDate = new DateTime($invoice->date);
$dueDate->add(new DateInterval('P' . $PAYMENT_TERM . 'D'));
// Update the invoice
$params = array(
'invoiceid' => $invoiceId,
'duedate' => $dueDate->format('Y-m-d')
);
$result = (object) localAPI('UpdateInvoice', $params);
}
}
// Update due date when invoice is created or gateway is changed
//add_hook("InvoiceCreationPreEmail", 1, "setDueDate");
//add_hook("InvoiceChangeGateway", 1, "setDueDate");
add_hook("InvoiceCreation", 1, "setDueDate");
