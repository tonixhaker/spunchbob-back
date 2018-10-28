<?php

namespace App\Http\Controllers\API;

use App\Mail\NotRegisteredUserOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

class OrderController extends Controller
{
    public function registeredOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'allergy' => 'required',
            'first_name' => 'required',
            'goals' => 'required',
            'growth' => 'required',
            'phone' => 'required',
            'weight' => 'required',
            'email' => 'required|email'
        ]);
        $validator->validate();
        $user = Auth::user();
        if(!$user){
            return $this->notFoundApiResponse('Такого пользователя не существует');
        }
        $order = User::pendingOrder();
        if($order){
            return $this->internalErrorApiResponse('Заказ уже совершен!');
        }
        $order = Order::create([
            'user_id' => $user->id,
            'type' => Order::TYPE_PERSONAL_MENU,
            'goals' => $request->goals
        ]);
        $user = User::update($request->only(User::getFillableFields()));
        return $this->successApiResponse();
    }

    public function notRegisteredOrder(Request $request){
        $validator = Validator::make($request->all(), [
            'allergy' => 'required',
            'first_name' => 'required',
            'goals' => 'required',
            'growth' => 'required',
            'phone' => 'required',
            'weight' => 'required',
            'email' => 'required|email'
        ]);
        $validator->validate();
        $user = User::where('email',$request->email)->first();
        if($user){
            return $this->unauthorisedApiResponse('Email exist');
        }
        $data = $request->only(User::getFillableFields());
        $data['confirm_token'] = bcrypt(str_random(32));
        $data['password'] = bcrypt(str_random(32));
        $user = User::create($data);
        $order = Order::create([
            'user_id' => $user->id,
            'type' => Order::TYPE_PERSONAL_MENU,
            'goals' => $request->goals
        ]);
        Mail::to('tonixhaker@gmail.com')->send(new NotRegisteredUserOrder($user));

//        Mail::send([], [], function (Message $message) {
//            $message
//                ->to('tonixhaker@gmail.com')
//                ->embedData([
//                    'personalizations' => [
//                        [
//                            'dynamic_template_data' => [
//                                'name'  => 's-ichikawa',
//                            ],
//                        ],
//                    ],
//                    'template_id' => 'd-b73199f6d79c497ea79a56f23152b610',
//                ], SendgridTransport::SMTP_API_NAME);
//        });


        return $this->successApiResponse();
    }
}
