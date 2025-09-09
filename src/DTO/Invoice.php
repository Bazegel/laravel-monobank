<?php
declare(strict_types=1);

namespace Bazegel\Monobank\DTO;

use Illuminate\Http\Request;

class Invoice
{
    /** @var string */
    public $invoiceId;
    /** @var string */
    public $status;
    /** @var string */
    public $failureReason;
    /** @var string */
    public $errCode;
    /** @var int */
    public $amount;
    /** @var int */
    public $ccy;
    /** @var int */
    public $finalAmount;
    /** @var string */
    public $createdDate;
    /** @var string */
    public $modifiedDate;
    /** @var string */
    public $reference;
    /** @var string */
    public $destination;
    /** @var array */
    public $cancelList;
    /** @var array */
    public $paymentInfo;
    /** @var array */
    public $walletData;
    /** @var array */
    public $tipsInfo;

    /**
     * @param string $invoiceId
     * @param string $status
     * @param string $failureReason
     * @param string $errCode
     * @param int $amount
     * @param int $ccy
     * @param int $finalAmount
     * @param string $createdDate
     * @param string $modifiedDate
     * @param string $reference
     * @param string $destination
     * @param array $cancelList
     * @param array $paymentInfo
     * @param array $walletData
     * @param array $tipsInfo
     */
    public function __construct(
        string $invoiceId,
        string $status,
        string $failureReason,
        string $errCode,
        int $amount,
        int $ccy,
        int $finalAmount,
        string $createdDate,
        string $modifiedDate,
        string $reference,
        string $destination,
        array $cancelList,
        array $paymentInfo,
        array $walletData,
        array $tipsInfo
    )
    {
        $this->invoiceId = $invoiceId;
        $this->status = $status;
        $this->failureReason = $failureReason;
        $this->errCode = $errCode;
        $this->amount = $amount;
        $this->ccy = $ccy;
        $this->finalAmount = $finalAmount;
        $this->createdDate = $createdDate;
        $this->modifiedDate = $modifiedDate;
        $this->reference = $reference;
        $this->destination = $destination;
        $this->cancelList = $cancelList;
        $this->paymentInfo = $paymentInfo;
        $this->walletData = $walletData;
        $this->tipsInfo = $tipsInfo;
    }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('invoiceId'),
            $request->input('status'),
            $request->input('failureReason'),
            $request->input('errCode'),
            $request->input('amount'),
            $request->input('ccy'),
            $request->input('finalAmount'),
            $request->input('createdDate'),
            $request->input('modifiedDate'),
            $request->input('reference'),
            $request->input('destination'),
            $request->input('cancelList'),
            $request->input('paymentInfo'),
            $request->input('walletData'),
            $request->input('tipsInfo'),
        );
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['invoiceId'],
            $data['status'],
            $data['failureReason'],
            $data['errCode'],
            $data['amount'],
            $data['ccy'],
            $data['finalAmount'],
            $data['createdDate'],
            $data['modifiedDate'],
            $data['reference'],
            $data['destination'],
            $data['cancelList'],
            $data['paymentInfo'],
            $data['walletData'],
            $data['tipsInfo'],
        );
    }
}
