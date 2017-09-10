<?php

namespace app\controllers;

use app\models\Notification;
use app\models\search\PublicationSearch;
use Yii;
use app\models\Publication;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * PublicationController implements the CRUD actions for Publication model.
 */
class PublicationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['moderator', 'admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['moderator', 'admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updatePublication'],
                        'roleParams' => function($rule) {
                            return ['publication' => Publication::findOne(Yii::$app->request->get('id'))];
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['updatePublication'],
                        'roleParams' => function($rule) {
                            return ['publication' => Publication::findOne(Yii::$app->request->get('id'))];
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Publication models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PublicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Publication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Publication();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['text' => 'Добавлена публикациия', 'success' => true];
                } else {
                    return ActiveForm::validate($model);
                }
            } elseif($model->save()) {
                return $this->goHome();
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => 'Создание публикации',
                'body' => $this->renderAjax('_form', ['model' => $model])
            ];
        } else {
            return $this->render('_form', ['model' => $model]);
        }
    }

    /**
     * Updates an existing Publication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Publication::findOne(Yii::$app->request->get('id'));

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($model->save()) {
                    return ['text' => 'Публикация изменена', 'success' => true];
                } else {
                    return ActiveForm::validate($model);
                }
            } elseif($model->save()) {
                return $this->goHome();
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Изменение публикации:' . $model->name,
                'body' => $this->renderAjax('_form', ['model' => $model])
            ];
        } else {
            return $this->render('_form', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Publication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Publication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Publication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Publication::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @throws \yii\base\ExitException
     */
    public function actionEditable()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->get('id');
        $value = Yii::$app->request->get('status');

        Publication::updateAll(['status_id' => $value], ['id' => $id]);
        return ['success' => true];
    }

    public function actionView($id)
    {
        $model = Publication::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app->user->id;
        if (!empty($userId)) {
            Notification::updateAll(
                [
                    'status_id' => Notification::STATUS_HIDDEN
                ],
                [
                    'user_id' => $userId, 'publication_id' => $id
                ]
            );
        }
        return $this->render('view', ['model' => $model]);
    }
}
