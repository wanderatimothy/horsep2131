<?php

$router->get('/','web\controllers\HomeController@index');
$router->get('/login','web\controllers\HomeController@login');
$router->get('/register','web\controllers\HomeController@register');
$router->get('/migrate','web\controllers\HomeController@migrate');
$router->get('/seed','web\controllers\HomeController@seed');



// authentication end points
$router->post('/create-account','web\controllers\AuthController@handle_user_registration');
$router->post('/authenticate','web\controllers\AuthController@handle_user_authentication');
$router->post('/logout','web\controllers\SystemController@logout');
// authentication end points


// system Routes
$router->get('/dashboard','web\controllers\SystemController@index');
$router->get('/properties','web\controllers\SystemController@properties');
$router->get('/property/{property}/unit/{unit}','web\controllers\SystemController@manage_unit');
$router->get('/property/{id}','web\controllers\SystemController@manage_property');
$router->get('/settings','web\controllers\SystemController@settings');


// system endpoints
$router->post('/api/landlord','web\controllers\resources\LandlordResource@create');
$router->get('/api/landlords','web\controllers\resources\LandlordResource@index');
$router->get('/api/types','web\controllers\resources\PropertyResource@property_types');
$router->get('/api/properties','web\controllers\resources\PropertyResource@index');
$router->post('/api/property/{id}/delete','web\controllers\resources\PropertyResource@delete');
$router->get('/api/property/{id}/units','web\controllers\resources\PropertyResource@property_units');
$router->post('/api/property','web\controllers\resources\PropertyResource@create');
$router->post('/api/property/{id}/unit','web\controllers\resources\PropertyResource@create_unit');
$router->get('/api/floors','web\controllers\resources\PropertyResource@floors');
$router->post('/api/tenant','web\controllers\resources\TenantResource@create');
$router->get('/api/property/{id}/tenants','web\controllers\resources\TenantResource@TenantsOnProperty');
$router->get('/api/details','web\controllers\resources\AccountResource@index');
$router->post('/api/account/{id}.','web\controllers\resources\AccountResource@edit');
$router->post('/api/rent-rule','web\controllers\resources\SettingsResource@create_rent_rule');
$router->get('/api/rent-rules','web\controllers\resources\SettingsResource@get_all_rent_rules');
$router->post('/api/create-field','web\controllers\resources\SettingsResource@create_field');
$router->get('/api/tenant-fields','web\controllers\resources\SettingsResource@customTenantsField');
$router->get('/api/property-fields','web\controllers\resources\SettingsResource@customPropertyField');
$router->post('/api/on-boarding-rule','web\controllers\resources\SettingsResource@create_on_boarding_rule');
$router->get('/api/on-boarding-rules','web\controllers\resources\SettingsResource@onboardingRules');
$router->get('/api/property/{property}/unit/{unit}/tenants','web\controllers\resources\TenantResource@TenantsInUnit');




// 

// system endpoints

// system Routes
// $router->set404('api/*',function(){
//     respondWithJson(['errors' => 'item not found '],['content-type: application/json'],404);
// });
$router->set404(function(){
    return _view("errors._view_not_found");
});