<?php
declare(strict_types=1);
//todo separate this class to invoice, wallet, merchant etc classes
namespace Bazegel\Monobank;

use Bazegel\Monobank\DTO\Response;
use Bazegel\Monobank\Enums\HttpMethod;
use Bazegel\Monobank\Interfaces\HttpClient;

class Acquiring
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }


    /**
     * @param array $data
     * @return Response
     */
    public function createInvoice(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/invoice/create', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getInvoiceStatus(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/invoice/status', $data);
    }


    /**
     * @param array $data
     * @return Response
     */
    public function cancelInvoice(array $data): Response
    {
        return $this->client->request(HttpMethod::POST,'merchant/invoice/cancel', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function invalidateInvoice(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/invoice/remove', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function finalizationHoldSum(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/invoice/finalize', $data);
    }

    /**
     * @return Response
     */
    public function getKeyForVerification(): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/pubkey');
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getQrCacheRegisterInfo(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/qr/details', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function resetQrCacheRegisterAmount(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/qr/reset-amount', $data);
    }

    /**
     * @return Response
     */
    public function getQrCacheRegisterList(): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/qr/list');
    }

    /**
     * @return Response
     */
    public function getMerchantDetails(): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/details');
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getMerchantStatement(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/statement', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function deleteWalletCard(array $data): Response
    {
        return $this->client->request(HttpMethod::DELETE, 'merchant/card/delete', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getWallet(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/wallet', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function payByWalletToken(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/wallet/payment', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function payByRequisites(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/invoice/payment-direct', $data);
    }

    /**
     * @return Response
     */
    public function getSubMerchantList(): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/submerchant/list');
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getReceipt(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/invoice/receipt', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function getFiscalChecks(array $data): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/invoice/fiscal-checks', $data);
    }

    /**
     * @param array $data
     * @return Response
     */
    public function syncPayment(array $data): Response
    {
        return $this->client->request(HttpMethod::POST, 'merchant/invoice/sync-payment', $data);
    }

    /**
     * @return Response
     */
    public function getEmployeeList(): Response
    {
        return $this->client->request(HttpMethod::GET, 'merchant/employee/list');
    }
}
