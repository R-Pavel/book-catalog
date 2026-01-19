<?php

declare(strict_types=1);

namespace controllers;

use common\models\Book;
use yii\console\Controller;

class NotifyController extends Controller
{
    protected const int TIME_DAY = 86400;
    protected const string API_KEY_SMS_PILOT = 'XXXXXXXXXXXXXXXXXXXXXXX';
    protected const string URL_SMS_PILOT = "https://smspilot.ru/api.php";

    /**
    * php yii notify/send-new-books
    */
    public function actionSendNewBooks(): void
    {
        $newBooks = Book::find()
            ->where(['>=', 'created_at', time() - self::TIME_DAY])
            ->with(['authors.subscriptions'])
            ->all();

        $this->stdout("Count of new books: " . count($newBooks) . "\n");

        foreach ($newBooks as $book) {
            $this->notifySubscribersForBook($book);
        }

        $this->stdout("Notification processed successfully.\n");
    }

    private function notifySubscribersForBook(Book $book): void
    {
        foreach ($book->authors as $author) {
            foreach ($author->subscriptions as $subscription) {
                $this->sendEmulatedSms(
                    $subscription->phone,
                    "New book from {$author->full_name}: {$book->name} ({$book->year})"
                );
            }
        }
    }

    private function sendEmulatedSms(string $phone, string $message): void
    {
        $url = self::URL_SMS_PILOT .
            "?send=" . urlencode($message) .
            "&to=" . urlencode($phone) .
            "&apikey=" . self::API_KEY_SMS_PILOT;

        file_get_contents($url);

        $this->stdout("SMS emulated → $phone: $message\n");
    }
}