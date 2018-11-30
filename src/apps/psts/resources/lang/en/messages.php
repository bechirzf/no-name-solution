<?php

return [

    'dangerous' => "You Ship DANGEROUS item: :name",
    "unknownerror" => 'Unknown Error.',
    'air_shipping_fee' => 'Air Shipping Fee',
    'sea_shipping_fee' => 'Sea Shipping Fee',
    'invalid_status' => 'Invalid status',
    'invalid_binno' =>  'Invalid Bin number',
    'promo_add_success' => 'Promo Successfully created',
    'promo_update_success' => 'Promo Successfully updated',
    'success' => 'Successfully update data.',
    'deleted' => 'Successfully delete :module.',
    'parcel_404' => 'Parel not found.',
    'auth' => [
        '422' => 'Username or password is incorrect.',
        '401_incorrect' => 'User email or password is incorrect.',
        '401_notinorg' => 'The user is not a member of the system organization.',
        '401_disabled' => 'The credentials that you provided are invalid or your account may have been disabled.',
    ],

    'coupons' => [
        'file' => [
            'success' => 'Bulk upload of promo codes is successful.',
            'failed' => 'Upload of csv file is unsuccessful.', //
            'empty' => 'Csv file does not contain data.',
            'too_large' => 'CSV file is too large.',
        ],
        'code' => [
            'invalid' => 'Please enter a valid promo code.', // SIT-105
            'advance' => 'Your code not ready to use at this moment.',
            'expired' => 'Your code already expired.',
            'consumed' => 'Your code was fully consumed.',
            'unauthorize' => "You're not authorized to use this code.",
            'used' => 'You already used this code.'
        ],
        'created' => 'Promo code has been created.',
        'updated' => 'Promo code is updated.',
        '422' => 'Fill in all the fields correctly.',
        'inactive' => 'Promo code is now inactive.',
        'status_change' => 'Promo code is now :status.',
        'download' => 'Promo codes from :start_date to :end_date have been downloaded.',
        'code_download' => 'User of ":code_name" code from :start_date to :end_date has been downloaded.',
        'rules' => [
            'limit' => 'This promo code is valid for a minimum shipping fee of :CURRENCY :amount.',
            'shipment_error' => 'Discount is valid but available to :shipmentmethod cargo only!',
        ]
    ],
    'blocks' => [
        'created' => 'Block has been created.',
        'updated' => 'Block is updated.',
        'inactive' => 'Block is inactive.',
        'active' => 'Block is active.',
        'deleted' => 'Block has been deleted.', 

    ],
    
    'facebook' => [
        'not_match' => 'You registered as ":email". Please update your facebook email to match your account.',
    ],

    'media' => [
        'upload'    => 'Successfully uploaded.', // SF-301
        'deleted'   => 'Successfully deleted.', // SF-301
        'invalid'   => 'Invalid image.',
        'too_large' => 'The image failed to upload. File size exceeded the :sizeMB limit.',
    ],
   

    'pages' => [
        'created'   => 'Page has been created.',
        'updated'   => 'Page is updated.',
        'inactive'  => 'Page is inactive.',
        'active'    => 'Page is active.',
        'deleted'   => 'Page has been deleted.', // Wala to
        'protected' => 'Sorry you cant disable proctected page(:page)',
        'unique'    => [
            'title' => 'The page title has already been taken.',
        ],
    ],
    
    'payment' => [
       'list' => 'Successfully list payments.', 
    ],
    
    // Promotion Banners
    'promotions' => [
        'sorting' => 'Banner order is updated.',
        '404' => 'Promotion not found!',
        'loaded' => 'Promotion successfully loaded',
        'uploaded' => 'Banner has been uploaded.',
        'updated' => 'Banner details have been updated.',
        'status' => 'Banner has been updated.',
        'disable' => 'Banner is inactive.', // I think there no set incative since there a validity data.
        'activate' => 'Banner is active.',  // I think there no set incative since there a range data.
        'invalid' => [
            'image' => 'Only png, jpeg, gif, svg or bmp images are accepted',
        ],
    ],
        
    'settings' => [
        'emtpty'    => 'Fill in all the fields correctly.',
        'required'  => ':Field is required',
        'numeric'   => ':Field must be a number.',
        'min'       => ':Field must be atleast :min.',
        'max'       => ':Field must not be greater than :max.',
    ],
    
    
];
