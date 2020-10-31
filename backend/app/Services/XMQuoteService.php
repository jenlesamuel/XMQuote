<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\QuoteGeneratedEvent;
use App\Services\XMClient;

class XMQuoteService
{
    /**
     * @var XMClient
     */
    private $client;

    public function __construct(XMClient $client)
    {
        $this->client = $client;
    }

    public function generateQuote(
        string $startDate,
        string $endDate,
        string $symbol,
        string $email
    ): array {


        $historicalData = $this->getHistoricalData(
            $startDate,
            $endDate,
            $symbol
        );

        $company = $this->getCompanyNameFromSymbol($symbol);

        // Asynchronoulsy send email by firing an event
        event(new QuoteGeneratedEvent([
            "startDate" => $startDate,
            "endDate" => $endDate,
            "email" => $email,
            "company" => $company
        ]));

        return json_decode($historicalData, true);
    }

    public function getHistoricalData(
        string $startDate,
        string $endDate,
        string $symbol
    ) {
        $startTimestamp = \DateTime::createFromFormat("Y-m-d", $startDate)->getTimestamp();
        $endTimestamp = \DateTime::createFromFormat("Y-m-d", $endDate)->getTimestamp();

        $url = "https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-historical-data?frequency=1d&filter=history&period1={$startTimestamp}&period2={$endTimestamp}&symbol={$symbol}";
        $headers = [
            "x-rapidapi-host: apidojo-yahoo-finance-v1.p.rapidapi.com",
            "x-rapidapi-key: a73452823amshefae3693689888bp1418a3jsnac4b0c008450"
        ];

        return $this->client->makeRequest($url, "GET", $headers);
    }

    public function getNasdaqListings(): array
    {
        $url = "https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json";

        $response = $this->client->makeRequest($url, "GET", []);

        return json_decode($response, true);
    }

    public function getCompanyNameFromSymbol(string $symbol): ?string
    {
        $listings = $this->getNasdaqListings();

        foreach ($listings as $listing) {
            if ($listing["Symbol"] === $symbol) {
                return $listing["Company Name"];
            }
        }

        return null;
    }
}
