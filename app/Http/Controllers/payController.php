<?php

namespace App\Http\Controllers;
use App\Models\pay;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
class payController extends Controller
{
    public function index(){
        return view('index');
    }
    //rzp_test_aRwsuSSDb1VVLo -keyId
    //cjPuGRsrqe7RQP1HSMzQa8iM -keySecret

    public function payment(Request $request){
        $name = $request->input('name');
        $amount=$request->input('amount');

        $api = new Api('rzp_test_aRwsuSSDb1VVLo', 'cjPuGRsrqe7RQP1HSMzQa8iM');


$order  = $api->order->create(array('receipt' => '123', 'amount' => $amount*100, 'currency' => 'INR')); 
$orderId = $order['id']; 

$user_pay=new pay();
$user_pay->name=$name;
$user_pay->amount=$amount;
$user_pay->payment_id=$orderId;
$user_pay->save();

Session::put('order_id',$orderId);
Session::put('amount',$amount);

return redirect('/');
    }
    public function pay(Request $request){
        $data=$request->all();
        $user=pay::where('payment_id',$data['razorpay_order_id'])->first();
        $user->payment_done=true;
        $user->razorpay_id=$data['razorpay_payment_id'];
        $user->save();
        return redirect('/success');
        dd($data);
    }
}
