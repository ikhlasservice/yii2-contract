<?php

namespace ikhlas\contract\controllers;

use Yii;
use ikhlas\contract\models\Contract;
use ikhlas\contract\models\ContractSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Contract model.
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contract models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ContractSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contract model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Contract model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Contract();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Contract model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Contract model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contract model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contract the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Contract::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    ###########################################################

    public function actionList($rowNo = null, $listId = null) {
        $listId = $listId ? explode(',', $listId) : null;
        $listId = $listId ? array_filter($listId) : null;
        //$listId=$listId?array_flip ($listId):null;

        Yii::$app->params['listId'] = $listId;
        //$listId=$listId?\yii\helpers\ArrayHelper::getValue($listId,[]):null;
        //print_r($listId);

        $query = Contract::find()
                ->joinWith('credit')
                ->where([
            'contract.status' => 0,
            'credit.seller_id' => Yii::$app->user->id
        ]);
        //$query = $listId? $query->andWhere(['NOT IN','contract.id',[$listId]]):$query;
        $query->orderBy('contract.id DESC');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
//        print_r($dataProvider);
//        exit();
        return $this->renderAjax('list', [
                    'listDataProvider' => $dataProvider,
                    'ajax' => Yii::$app->request->isAjax,
                    'rowNo' => $rowNo,
                    'listId' => $listId,
                        //'model' => $model,
                        //'header' => $header
        ]);
    }

    public function actionLoadData($id) {
        $data = Contract::find()->where(['id' => $id])->one();
        $out = [
            'id' => $data->id,
            'customer_id' => $data->credit->fullname . '<br/><small>' . $data->credit->customer_id . '</small>',
            'fullname' => $data->credit->fullname,
            'products' => $data->credit->productsLabel,
            'totalPay' => $data->credit->totalPay,
        ];
        echo Json::encode($out);
    }

    public function actionPayments($id) {
        $model = $this->findModel($id);
        $modelDetails = $model->paymentAll;


        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \ikhlas\payment\models\PaymentDetail::find()
                    ->joinWith('contract')
                    ->joinWith('payment')
                    ->where([
                        'contract.id' => $id,
                        'payment.status' => 3
                    ])
                    ->orderBy('period'),
            'pagination' => false,
        ]);
        return $this->render('payments', [
                    'model' => $model,
                    'modelDetails' => $modelDetails,
                    'dataProvider' => $dataProvider
        ]);
    }

}
