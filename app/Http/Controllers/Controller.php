<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function unauthorisedApiResponse($message = "Unauthorised request", $code = 401)
    {
        return $this->apiResponse($message, [], $code);
    }

    public function successApiResponse($message = "Success request", $data = [], $code = 200)
    {
        return $this->apiResponse($message, $data, $code);
    }

    public function notFoundApiResponse($message = "Route not found", $data = [], $code = 404)
    {
        return $this->apiResponse($message, $data, $code);
    }

    public function validationErrorApiResponse($message = "Validation error", $errors = [], $code = 422)
    {
        $validationResponse = [
            'message' => 'Invalid data',
            'errors' => $errors
        ];
        return $this->apiResponse($message, $validationResponse, $code);
    }

    public function internalErrorApiResponse($message = "Server error", $errors = [], $code = 500)
    {
        $responseData = [
            'message' => 'Internal error. Please contact to our support',
            'errors' => $errors
        ];
        return $this->apiResponse($message, $responseData, $code);
    }

    public function apiResponse($message, $data, $code)
    {
        if(isset($data) && count($data)>0){
            return response()->json(["message"=>$message, "data"=>$data], $code);
        } else {
            return response()->json($message, $code);
        }
    }

    public function paginateResponse(Paginator $pagination)
    {
        $data = [
            'items' => $pagination->items(),
            'pagination' => [
                'per_page' => $pagination->perPage(),
                'page' => $pagination->currentPage(),
                'has_more' => $pagination->hasMorePages(),
                'total' => $pagination->total()
            ],
        ];

        return $this->successApiResponse($data);
    }
}