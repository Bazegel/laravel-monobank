**English** | [Українська](README.uk.md)

# Laravel Monobank Acquiring API

A package for integrating with the Monobank Acquiring API in Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require bazegel/laravel-monobank
```

## Configuration

1.  Publish the configuration file:

    ```bash
    php artisan vendor:publish --provider="Bazegel\Monobank\Providers\MonobankServiceProvider" --tag="config"
    ```

    This command will create a `config/monobank.php` file.

2.  Add your acquiring token to the `.env` file:

    ```env
    MONOBANK_TOKEN=your_secret_token
    ```

## Usage

The package provides a convenient `Monobank` facade for interacting with the API.

### Creating an Invoice

To create an invoice and redirect the user to the payment page:

```php
use Bazegel\Monobank\Facades\Monobank;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $invoiceData = [
            'amount' => 1000, // Amount in the smallest currency units (e.g., kopecks)
            'ccy' => 980, // ISO 4217 currency code (980 = UAH)
            'redirectUrl' => route('payment.success'),
            'webHookUrl' => route('monobank.webhook'),
            'merchantPaymInfo' => [
                'destination' => 'Payment for order #12345',
                'basketOrder' => [
                    [
                        'name' => 'Product 1',
                        'qty' => 1,
                        'sum' => 1000,
                        'unit' => 'pcs.'
                    ]
                ]
            ],
        ];

        try {
            $response = Monobank::createInvoice($invoiceData);

            // Get the payment URL
            $paymentUrl = $response->getData('pageUrl');

            // Redirect the user to the payment page
            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            // Error handling
            return back()->withErrors('Payment creation error: ' . $e->getMessage());
        }
    }
}
```

### Checking Invoice Status

You can check the status of a previously created invoice by its `invoiceId`.

```php
use Bazegel\Monobank\Facades\Monobank;

$invoiceId = '240228A9aB1cD23d4E5f'; // Invoice ID received upon creation

try {
    $response = Monobank::getInvoiceStatus($invoiceId);

    // 'created', 'processing', 'hold', 'success', 'failure', 'expired'
    $status = $response->getData('status');

    if ($status === 'success') {
        // Payment is successful
    }

} catch (\Exception $e) {
    // Error handling
}
```

### Handling Webhooks

Create a route and a controller to handle incoming notifications from Monobank.

**Route (`routes/web.php`):**
```php
Route::post('/monobank/webhook', [MonobankWebhookController::class, 'handle'])->name('monobank.webhook');
```

**Controller:**
```php
use Bazegel\Monobank\Facades\Monobank;
use Illuminate\Http\Request;

class MonobankWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Signature validation (IMPORTANT!)
        // You need to implement the validation of the X-Sign header.
        // $publicKey = Monobank::getKeyForVerification()->getData('key');
        // ... signature validation logic ...

        // 2. Parsing the data
        $invoice = Monobank::parseCallbackData($request);

        $status = $invoice->getStatus(); // 'success', 'failure', etc.
        $invoiceId = $invoice->getInvoiceId();
        $amount = $invoice->getAmount();

        // Your logic for handling the payment status goes here
        // For example, updating the order status in the database

        return response('OK', 200);
    }
}
```
> **Important:** Before processing a webhook, you must verify the `X-Sign` digital signature in the request headers to ensure that the request actually came from Monobank. To do this, get the public key using `Monobank::getKeyForVerification()`.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.