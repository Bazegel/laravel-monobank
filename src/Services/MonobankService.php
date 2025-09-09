<?php
declare(strict_types=1);

namespace Bazegel\Monobank\Services;

use Illuminate\Http\Request;
use Bazegel\Monobank\Acquiring;
use Bazegel\Monobank\DTO\Invoice;
use Bazegel\Monobank\DTO\Response;
use Bazegel\Monobank\Enums\InitiationKind;

class MonobankService
{
    /**
     * @var Acquiring
     */
    protected $acquiring;

    /**
     * @param Acquiring $acquiring
     */
    public function __construct(Acquiring $acquiring)
    {
        $this->acquiring = $acquiring;
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function createInvoice(array $data): Response
    {
        if (!isset($data['amount'])) {
            throw new \Exception('Amount is required.');
        }

        if (!is_int($data['amount'])) {
            throw new \Exception('Amount must be an integer.');
        }

        return $this->acquiring->createInvoice($data);
    }

    /**
     * @param string $invoiceId
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvoiceStatus(string $invoiceId): Response
    {
        return $this->acquiring->getInvoiceStatus([
            'invoiceId' => $invoiceId
        ]);
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function cancelInvoice(array $data): Response
    {
        if (!isset($data['invoiceId'])) {
            throw new \Exception('InvoiceId is required');
        }

        return $this->acquiring->cancelInvoice($data);
    }

    /**
     * @param string $invoiceId
     * @return Response
     */
    public function invalidateInvoice(string $invoiceId): Response
    {
        return $this->acquiring->invalidateInvoice([
            'invoiceId' => $invoiceId
        ]);
    }

    /**
     * @param Request $request
     * @return Invoice
     */
    public function parseCallbackData(Request $request): Invoice
    {
        return Invoice::fromRequest($request);
    }

    /**
     * @return Response
     */
    public function getKeyForVerification(): Response
    {
        return $this->acquiring->getKeyForVerification();
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function finalizationHoldSum(array $data): Response
    {
        if (!isset($data['invoiceId'])) {
            throw new \Exception('InvoiceId is required');
        }

        return $this->acquiring->finalizationHoldSum($data);
    }

    /**
     * @param string $qrId
     * @return Response
     */
    public function getQrCacheRegisterInfo(string $qrId): Response
    {
        return $this->acquiring->getQrCacheRegisterInfo([
            'qrId' => $qrId
        ]);
    }

    /**
     * @param string $qrId
     * @return Response
     */
    public function resetQrCacheRegisterAmount(string $qrId): Response
    {
        return $this->acquiring->resetQrCacheRegisterAmount([
            'qrId' => $qrId
        ]);
    }

    /**
     * @return Response
     */
    public function getQrCacheRegisterList(): Response
    {
        return $this->acquiring->getQrCacheRegisterList();
    }

    /**
     * @return Response
     */
    public function getMerchantDetails(): Response
    {
        return $this->acquiring->getMerchantDetails();
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function getMerchantStatement(array $data): Response
    {
        if (!isset($data['from']) || !is_int($data['from'])) {
            throw new \Exception('from is required and should be valid unix timestamp value.');
        }

        return $this->acquiring->getMerchantStatement($data);
    }

    /**
     * @param string $cardToken
     * @return Response
     */
    public function deleteWalletCard(string $cardToken): Response
    {
        return $this->acquiring->deleteWalletCard([
            'cardToken' => $cardToken
        ]);
    }

    /**
     * @param string $walletId
     * @return Response
     */
    public function getWallet(string $walletId): Response
    {
        return $this->acquiring->getWallet([
            'walletId' => $walletId
        ]);
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function payByWalletToken(array $data): Response
    {
        if (!isset($data['cardToken']) || !is_string($data['cardToken'])) {
            throw new \Exception('cardToken is required and should be valid string value.');
        }

        if (!isset($data['amount']) || !is_int($data['amount'])) {
            throw new \Exception('amount is required and should be valid integer value.');
        }

        if (!isset($data['ccy']) || !is_int($data['ccy'])) {
            throw new \Exception('ccy is required and should be valid currency code (ISO 4217).');
        }

        if (!isset($data['initiationKind']) || !in_array($data['initiationKind'], InitiationKind::values())) {
            throw new \Exception('initiationKind is required and should be in ' . implode(', ', InitiationKind::values()));
        }

        return $this->acquiring->payByWalletToken($data);
    }

    public function payByRequisites(array $data): Response
    {
        if (!isset($data['amount']) || !is_int($data['amount'])) {
            throw new \Exception('amount is required and should be valid integer value.');
        }

        if (!isset($data['cardData']['pan'])
            || !isset($data['cardData']['exp'])
            || !is_int($data['cardData']['cvv'])) {
            throw new \Exception('cardData is required, should be array and include pan, cvv, exp values.');
        }
        return $this->acquiring->payByRequisites($data);
    }

    /**
     * @return Response
     */
    public function getSubMerchantList(): Response
    {
        return $this->acquiring->getSubMerchantList();
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function getReceipt(array $data): Response
    {
        if (!isset($data['invoiceId'])) {
            throw new \Exception('InvoiceId is required');
        }

        return $this->acquiring->getReceipt($data);
    }

    /**
     * @param string $invoiceId
     * @return Response
     */
    public function getFiscalChecks(string $invoiceId): Response
    {
        return $this->acquiring->getFiscalChecks([
            'invoiceId' => $invoiceId
        ]);
    }

    /**
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function syncPayment(array $data): Response
    {
        if (!isset($data['amount']) || !is_int($data['amount'])) {
            throw new \Exception('amount is required and should be valid integer value.');
        }

        if (!isset($data['ccy']) || !is_int($data['ccy'])) {
            throw new \Exception('ccy is required and should be valid currency code (ISO 4217).');
        }

        return $this->acquiring->syncPayment($data);
    }

    /**
     * @return Response
     */
    public function getEmployeeList(): Response
    {
        return $this->acquiring->getEmployeeList();
    }
}
