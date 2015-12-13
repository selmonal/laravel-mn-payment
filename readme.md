Laravel 4.2.* MN Payment
===============

Requirements
------------

- PHP >= 5.4.0
- [Composer](http://getcomposer.org/).

Installation
---------------

[Composer](http://getcomposer.org/) ашиглан суурьлуулах.

    $ composer require selmonal/laravel-mn-payment dev-master

Тохиргооны app.php файлд дараахь ServiceProvider - ийг бүртгэнэ.

    'Selmonal\Payment\PaymentServiceProvider'

Мөн aliases хэсэгт дараахь Facade - ийг бүртгэнэ.

    'Payment' => 'Selmonal\Payment\PaymentFacade'

app/config/payment.php тохиргооны файлыг үүсгэн дараахь тохиргоог бүртгэнэ.

    return array(
            'default' => 'golomt',
            'gateways' => array(
                'golomt' => array(
                    'merchant_id' => '',
                    'soap_username' => '',
                    'soap_password' => '',
                    'request_action' => '',
                    'wsdl' => ''
                )
            )
        );

Contributing
------------

See CONTRIBUTING.md file.

Usage
------------

Эхлээд төлбөр төлөх боломжтой model класс таа Selmonal\Payment\BillableInterface ийг хэрэгжүүлнэ. Жш:

    class Order extends Model implements BillableInterface
    {
        /**
         * Вальютийн код буцаана.
         *
         * @return string
         */
        public function getCurrencyCode() { return 840; }

        /**
         * Төлөх төлбөрийн мөнгөн дүн буцаана.
         *
         * @return double
         */
        public function getPaymentPrice() { return $this->payment_price; }

        /**
         * Төлбөрийн тухайн тайлбар.
         *
         * @return string
         */
        public function getPaymentDescription() { return 'tailbar'; }

        /**
         * Захиалгын дугаар буцаана.
         *
         * @return integer|mix
         */
        public function getBillableId() { return $this->id; }

        /**
         * @return mixed
         */
        public function getLangCode() { return 'mn'; }
    }

Төлбөр төлөх банкны хуудас уруу үсрэх.

    Route::get('orders/create', function() {

        $order = new Order();
        $order->payment_amount = Input::get('payment_amount');
        $order->save();

        $form = Payment::using('golomt')->makeRequestForm($order);

        return $form->render(); // Энд шууд автоматаар redirect хийх html кодыг зурах болно.

    });

Банкны хуудас дээр төлбөр төлөлт амжилттай дууссаны дараа буцаад сайтын буцах хаягийг дууддаг. Үүнийг боловсруулахдаа.

    use Selmonal\Payment\ResponseInterface;

    Route::get('golomt_webhook', function() {

        $order = Order::find(Input::get('trans_number');

        $response = Payment::using('golomt')->handleResponse($order, Input::only(
            'trans_number', 'success', 'error_code', 'error_desc', 'card_number'
        ));

        if($response->getStatus() == ResponseInterface::STATUS_APPROVED) {

            try {

                // Гүйлгээний лавлагаа хийх.
                $response->validate();

                $order->is_paid = true;
                $order->save();

            } catch(Selmonal\Payment\Exceptions\TransactionValidationException $exception) {
                // Банк уруу лавлагаа хийгээд гүйлгээг хүчингүй гэж үзсэн байна.
                echo 'message: ' . $exception->getMessage();
                echo 'soap check response code: ' . $exception->getCode();
            }  catch(Selmonal\Payment\Gateways\Golomt\SoupClientErrorException $exception) {
                // Голомт банкны лавлагаа хийж байхад үүсэж болох бөгөөд
                // soap ийн тохиргоо буруу хийгдсэнээс болж үүснэ.
            }
        }
        elseif($response->getStatus() == ResponseInterface::STATUS_DECLINED) {

            // Гүйлгээ амжилтгүй болсон
            echo $response->getMessage();
        }
        elseif($response->getStatus() == ResponseInterface::STATUS_CANCELLED_BY_CARDHOLDER) {

            // Гүйлгээг төлбөр төлөгч цуцласан.
            echo $response->getMessage();
        }
        elseif($response->getStatus() == ResponseInterface::STATUS_FAILED) {

            // Гүйлгээ хийх явцад алдаа гарсан.
            echo $response->getMessage();
        }
        elseif($response->getStatus() == ResponseInterface::STATUS_TIMED_OUT) {

            // Төлбөр төлөх хугацаа дууссан.
            echo $response->getMessage();
        }
    });

RoadMap
-------

    - Төрийн банк
    - Худалдаа хөгжлийн банк