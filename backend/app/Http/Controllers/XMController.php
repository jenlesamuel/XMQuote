<?php

namespace App\Http\Controllers;

use App\Services\XMQuoteService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class XMController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var XMQuoteService
     */
    private $xmService;

    public function __construct(XMQuoteService $xmService)
    {
        $this->xmService = $xmService;
    }

    /**
     * Handles request to generate a company's quotes
     *
     * @param Request $request
     * @return void
     */
    public function getQuotes(Request $request): JsonResponse
    {
        //TODO validate 
        $symbol = $request->input("symbol");
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");
        $email = $request->input("email");

        $response = $this->xmService->generateQuote(
            $startDate,
            $endDate,
            $symbol,
            $email
        );

        return response()->json($response, 200);
    }

    public function getListings(): JsonResponse
    {
        $data = $this->xmService->getNasdaqListings();

        return response()->json($data, 200);
    }
}
