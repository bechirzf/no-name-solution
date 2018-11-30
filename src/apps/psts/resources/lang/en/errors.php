<?php

/**
 * Error messages
 * 
 */

return [
    '401' => 'You do not have sufficient privileges to access this resource.',
    '422' => 'Fill in all the fields correctly.',
    '600' => 'Invalid setting name: :name',
    '650' => 'Coupon code already inactive.',
    '651' => 'Coupon code not ready to use at this moment',
    '652' => 'Coupon code already expired',
    // 7xx  F3 related Issue.
    '700' => 'Error on sending order for :referenceid', // transaction
    '701' => 'Failed to updated order for :trackingno', // transaction
    // 8xx Paypal related issue/ connection, etc.
    '800' => 'Paypal setCheckoutExpress error',
    '404' => ':module not found.',
    
];
