<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\search\UserSearch;
use app\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends Controller
{
    /**
     * Requests password reset
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'column1';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$model->sendEmail()) {
                return ActiveForm::validate($model);
            } else {
                return ['success' => true, 'text' => 'Вам отправленно e-mail письмо с инструкциями для восстановления доступа.'];
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goBack(Yii::$app->getHomeUrl());
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->validate()) {
                $model->resetPassword();
                return ['success' => true, 'text' => 'Ваш пароль изменен'];
            } else {
                return ActiveForm::validate($model);
            }
        }

        $this->layout = 'column1';

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'column1';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegistration()
    {
        $this->layout = 'column1';

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->generateConfirm();
            if ($model->save()) {
                $model->sendConfirm();
                return $this->goHome();
            }
        }
        return $this->render('registration', ['model' => $model]);
    }

    /**
     * @param $token
     * @return string|Response
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {
        $user = User::findOne(['confirmation_token' => $token]);
        /** @var User $user */

        if ($user === null) {
            throw new BadRequestHttpException;
        }

        if ($user->confirm()) {
            if (Yii::$app->user->isGuest) {
                return $this->render('confirmSuccess');
            } else {
                return $this->redirect('edit');
            }
        }
    }

    public function actionEdit()
    {
        $model = User::findOne(Yii::$app->user->getId());

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('edit');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}