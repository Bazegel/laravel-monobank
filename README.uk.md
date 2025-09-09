[English](README.md) | **Українська**

# API еквайрингу Monobank для Laravel

Пакет для інтеграції з API еквайрингу Monobank у Laravel-додатках.

## Встановлення

Ви можете встановити пакет через composer:

```bash
composer require bazegel/laravel-monobank
```

## Налаштування

1.  Опублікуйте файл конфігурації:

    ```bash
    php artisan vendor:publish --provider="Bazegel\Monobank\Providers\MonobankServiceProvider" --tag="config"
    ```

    Ця команда створить файл `config/monobank.php`.

2.  Додайте ваш токен еквайрингу до файлу `.env`:

    ```env
    MONOBANK_TOKEN=ваш_секретний_токен
    ```

## Використання

Пакет надає зручний фасад `Monobank` для взаємодії з API.

### Створення рахунку (інвойсу)

Щоб створити рахунок і перенаправити користувача на сторінку оплати:

```php
use Bazegel\Monobank\Facades\Monobank;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $invoiceData = [
            'amount' => 1000, // Сума в найменших одиницях валюти (копійках)
            'ccy' => 980, // Код валюти ISO 4217 (980 = UAH)
            'redirectUrl' => route('payment.success'),
            'webHookUrl' => route('monobank.webhook'),
            'merchantPaymInfo' => [
                'destination' => 'Оплата замовлення #12345',
                'basketOrder' => [
                    [
                        'name' => 'Товар 1',
                        'qty' => 1,
                        'sum' => 1000,
                        'unit' => 'шт.'
                    ]
                ]
            ],
        ];

        try {
            $response = Monobank::createInvoice($invoiceData);

            // Отримуємо URL для оплати
            $paymentUrl = $response->getData('pageUrl');

            // Перенаправляємо користувача на сторінку оплати
            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            // Обробка помилок
            return back()->withErrors('Помилка створення платежу: ' . $e->getMessage());
        }
    }
}
```

### Перевірка статусу рахунку

Ви можете перевірити статус раніше створеного рахунку за його `invoiceId`.

```php
use Bazegel\Monobank\Facades\Monobank;

$invoiceId = '240228A9aB1cD23d4E5f'; // ID рахунку, отриманий при створенні

try {
    $response = Monobank::getInvoiceStatus($invoiceId);

    // 'created', 'processing', 'hold', 'success', 'failure', 'expired'
    $status = $response->getData('status');

    if ($status === 'success') {
        // Платіж успішний
    }

} catch (\Exception $e) {
    // Обробка помилок
}
```

### Обробка Webhooks

Створіть маршрут і контролер для обробки вхідних повідомлень від Monobank.

**Маршрут (`routes/web.php`):**
```php
Route::post('/monobank/webhook', [MonobankWebhookController::class, 'handle'])->name('monobank.webhook');
```

**Контроллер:**
```php
use Bazegel\Monobank\Facades\Monobank;
use Illuminate\Http\Request;

class MonobankWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Валідація підпису (ВАЖЛИВО!)
        // Необхідно реалізувати перевірку заголовка X-Sign.
        // $publicKey = Monobank::getKeyForVerification()->getData('key');
        // ... логіка валідації підпису ...

        // 2. Парсинг даних
        $invoice = Monobank::parseCallbackData($request);

        $status = $invoice->getStatus(); // 'success', 'failure', etc.
        $invoiceId = $invoice->getInvoiceId();
        $amount = $invoice->getAmount();

        // Ваша логіка обробки статусу платежу
        // Наприклад, оновлення статусу замовлення в базі даних

        return response('OK', 200);
    }
}
```
> **Важливо:** Перед обробкою вебхука необхідно перевіряти цифровий підпис `X-Sign` у заголовках запиту, щоб переконатися, що запит дійсно надійшов від Monobank. Для цього отримайте публічний ключ за допомогою `Monobank::getKeyForVerification()`.

## Ліцензія

The MIT License (MIT). Будь ласка, дивіться [Файл ліцензії](LICENSE) для отримання додаткової інформації.
