<?php

use App\Models\RTTProduct;
use App\Models\Setting;
 use App\Models\User;
 use App\Models\Product;
 use App\Models\Package;

 function setting()
 {
 	$setting = Setting::first();
 	return $setting;
 }

 function user()
 {
 	$user = auth()->user();
 	return $user;
 }

 function product($id)
 {
 	$product = Product::findorfail($id);
 	return $product;
 }
 function rttProduct($id)
 {
 	$product = RTTProduct::findorfail($id);
 	return $product;
 }

 function package($id)
 {
 	$packge = Package::findorfail($id);
 	return $packge;
 }
