<?php

declare(strict_types=1);

namespace controllers;

use common\models\Book;
use yii\console\Controller;

class NotifyController extends Controller
{
    protected const int TIME_DAY = 86400;
    protected const string API_KEY_SMS_PILOT = 'XXXXXXXXXXXXXXXXXXXXXXX';
    protected const string URL_SMS_PILOT = "https://smspilot.ru/api.php?send=";

    /**
    * php yii notify/send-new-books
     */
    public function actionSendNewBooks(): void
    {
        $books = Book::find()
            ->where(['>=', 'created_at', time() - self::TIME_DAY])
            ->with('authors')
            ->all();

        foreach($books as $book) {
            foreach($book->authors as $author) {
                foreach($author->subscriptions as $subscription) {
                    $phone = $subscription->phone;
                    $message = "New book from {$author->full_name} : {$book->name} ({$book->year})";

                    $url = self::URL_SMS_PILOT . urlencode($message) .
                        "&to=" . urlencode($phone) .
                        "&apikey=" . self::API_KEY_SMS_PILOT;

                    file_get_contents($url);

                    $this->stdout("SMS emulated to $phone:$message\n");
                }
            }
        }

        $this->stdout("All notifications processed successfully.\n");
    }
}