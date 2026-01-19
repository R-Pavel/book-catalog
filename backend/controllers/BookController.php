<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\Author;
use common\models\Book;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;

class BookController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'report'],
                        'allow' => true,
                        'roles' => ['?', '@'], // guest and user
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => '@',// only logged user
                    ]
                ]
            ]
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Book::find()->with('authors'),
            ])
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Book();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->cover_photo = UploadedFile::getInstance($model, 'cover_photo');
                if ($model->cover_photo) {
                    $filename = time() . '.' . $model->cover_photo->extension;
                    $model->cover_photo->saveAs(Yii::getAlias('@webroot/uploads/') . $filename);
                    $model->cover_photo = '/uploads/' . $filename;
                }

                if ($model->save()) {
                    $authorIds = $this->request->post('Book')['author_ids'] ?? [];
                    foreach ($authorIds as $authorId) {
                        $author = Author::findOne($authorId);
                        if ($author) {
                            $model->link('authors', $author);
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' =>$model,
            'authors' => Author::find()->all(),
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): string|Response
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->cover_photo = UploadedFile::getInstance($model, 'cover_photo');
            if ($model->cover_photo) {
                $filename = time() . '.' . $model->cover_photo->extension;
                $model->cover_photo->saveAs(Yii::getAlias('@webroot/uploads/') . $filename);
                $model->cover_photo = '/uploads/' . $filename;
            }
        }

        if ($model->save()) {
            $model->unlinkAll('authors', true);
            $authorIds = $this->request->post('Book')['author_ids'] ?? [];
            foreach ($authorIds as $authorId) {
                $author = Author::findOne($authorId);
                if ($author) {
                    $model->link('authors', $author);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => Author::find()->all(),
        ]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionReport(): string
    {
        $year = Yii::$app->request->get('year', date('Y'));

        $authors = Author::find()
            ->select([
                'author.*',
                'COUNT(book.id) AS book_count'
            ])
            ->leftJoin('{{%book_author}}', '{{%book_author}}.author_id = author.id')
            ->leftJoin('{{%book}}', '{{%book}}.id = {{%book_author}}.book_id')
            ->andWhere(['YEAR({{%book}}.year)' => $year])
            ->groupBy('author.id')
            ->orderBy(['book_count' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('report', [
            'authors' => $authors,
            'year' => $year,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function findModel($id): Book
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Book not found.');
    }
}