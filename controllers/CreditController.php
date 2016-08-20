<?php

namespace ikhlas\contract\controllers;

use Yii;
use ikhlas\contract\models\Contract;
use ikhlas\contract\models\Credit;
use ikhlas\contract\models\CreditDetail;
use ikhlas\contract\models\CreditConsider;
use ikhlas\contract\models\CreditSearch;
use ikhlas\contract\models\CreditDraftSearch;
use ikhlas\contract\models\CreditOfferSearch;
use ikhlas\contract\models\CreditConsiderSearch;
use ikhlas\contract\models\CreditResultSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use ikhlas\product\models\Product;
use common\models\Model;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\mpdf\Pdf;

/**
 * CreditController implements the CRUD actions for Credit model.
 */
class CreditController extends Controller {

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
     * Lists all Credit models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CreditSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Credit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
                    'modelDetails' => $model->creditDetails
        ]);
    }

    /**
     * Creates a new Credit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null, $customer_id = null) {

        if ($id === NULL && $customer_id === NULL) {
            Yii::$app->session->setFlash('warning', 'ขออภัย ไม่พบช่องทางนี้');
            return $this->redirect(['index']);
        }


        if ($id === null && Yii::$app->request->post()) {
            $model = new Credit();
            $model->customer_id = $customer_id;
            $model->created_at = time();
            $model->seller_id = Yii::$app->user->id;
            $model->status = 0;
            if ($model->save(false)) {
                $this->redirect(['create', 'id' => $model->id]);
            } else {
                print_r($model->getErrors());
            }
            
        } elseif (isset($id)) {

            $model = Credit::findOne($id);
            if (!in_array($model->status,[0,4])) {
                Yii::$app->session->setFlash('warning', 'ไม่พบสถานะที่แก้ไขได้');
                return $this->redirect(['index']);
            }
            
            $modelCustomer = $model->customer;
            $modelDetail = $model->creditDetails ? $model->creditDetails : [new CreditDetail()];
            $modelProduct = [new Product()];

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();
                //print_r($post);
                //exit();
                $oldIDs = ArrayHelper::map($modelDetail, 'id', 'id');
                $modelDetail = Model::createMultiple(CreditDetail::classname());
                Model::loadMultiple($modelDetail, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetail, 'id', 'id')));
//                foreach ($modelDetail as $index => $modelOptionValue) {
//                    
//                }
                // ajax validation
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ArrayHelper::merge(
                                    ActiveForm::validateMultiple($modelDetail), ActiveForm::validate($model)
                    );
                }

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelDetail) && $valid;

                //echo $valid;
                
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $model->seller_id = Yii::$app->user->id;
                        //$model->created_at = time();
//                        if (isset($post['save'])) {
//                            $model->status = 0;
//                        } elseif (isset($post['send'])) {
//                            $model->status = 1;
//                        }

                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                $flag = CreditDetail::deleteByIDs($deletedIDs);
                            }
                            foreach ($modelDetail as $key => $detail) {
                                $detail->credit_id = $model->id;
                                $detail->product_id = $this->chkTb(Product::className(), $post['CreditDetail'][$key], 'title', 'title');

                                $detail->price = str_replace(',', '', $detail->price);
                                $detail->amount = $post['CreditDetail'][$key]['amount'];
                                $detail->period = $post['CreditDetail'][$key]['period'];
                                if (($flag = $detail->save(false)) === false) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }else{
                            print_r($model->getError());
                            exit();
                        }
                        
                        if ($flag) {
                            $transaction->commit();
                            if (isset($post['save'])) {
                                return $this->redirect(['create', 'id' => $model->id]);
                            } else if (isset($post['send'])) {  
                                return $this->redirect(['confirm', 'id' => $model->id]);
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }else{                    
                    //print_r($model->getErrors());
                    //print_r($modelDetail->getErrors());
                }
            }

            return $this->render('create', [
                        'model' => $model,
                        'modelDetail' => $modelDetail,
                        'modelCustomer' => $modelCustomer,
            ]);
        }
    }

    /**
     * Updates an existing Credit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $modelDetail = $model->creditDetails;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            //print_r($post);
            //exit();
            $oldIDs = ArrayHelper::map($modelDetail, 'id', 'id');
            $modelDetail = Model::createMultiple(CreditDetail::classname());
            Model::loadMultiple($modelDetail, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetail, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                                ActiveForm::validateMultiple($modelDetail), ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelDetail) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model->seller_id = Yii::$app->user->id;
                    $model->created_at = time();

                    if (isset($post['save'])) {
                        $model->status = 0;
                    } elseif (isset($post['send'])) {
                        $model->status = 1;
                    }

                    if ($flag = $model->save(false)) {

                        if (!empty($deletedIDs)) {
                            $flag = CreditDetail::deleteByIDs($deletedIDs);
                        }

                        foreach ($modelDetail as $key => $detail) {
                            $detail->credit_id = $model->id;
                            $detail->product_id = $this->chkTb(Product::className(), $post['CreditDetail'][$key], 'title', 'title');
                            $detail->price = str_replace(',', '', $detail->price);

                            if (($flag = $detail->save(false)) === false) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        if (isset($post['save'])) {
                            return $this->redirect(['update', 'id' => $model->id]);
                        } elseif (isset($post['send'])) {
                            return $this->redirect(['confirm', 'id' => $model->id]);
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelDetail' => (empty($modelDetail) ? [new CreditDetail] : $modelDetail),
        ]);
    }

    /**
     * Deletes an existing Credit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Credit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Credit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Credit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    ######################################################
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function actionConfirm($id) {
        $model = $this->findModel($id);

        if (!in_array($model->status,[0,4])) {
            Yii::$app->session->setFlash('warning', 'ไม่พบสถานะที่จะยืนยันได้');
            return $this->redirect(['index']);
        }
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
//            print_r($post);
//            exit();
            if (isset($post['confirm'])) {
                $model->status = 1;
            }
            if ($model->save(false)) {
                if ($model->status == 1) {
                    Yii::$app->session->setFlash('success', 'ระบบได้ทำการยืนจองเรียบร้อยแล้ว');
                    Yii::$app->notification->sentStaff('ขอยื่นจองสินเชื่อ', Yii::$app->urlManager->createAbsoluteUrl(['/credit/default/view', 'id' => $model->id]), User::getThisUser()
                    );
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('confirm', [
                    'model' => $model,
                    'modelDetail' => $model->creditDetails
        ]);
    }
    
    /**
     * 
     * @param type $q
     */
    public function actionCustomerList($q = null) {
        $query = new \yii\db\Query;

        $query->select('id')
                ->from('customer')
                ->where('id LIKE "%' . $q . '%"')
                ->orderBy('id');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = ['value' => ['id' => $d['id']]];
        }
        echo \yii\helpers\Json::encode($out);
    }
    
    /**
     * ไว้กรณีเพิ่มข้อมูลใหม่
     * @param type $modelName
     * @param type $val
     * @param type $id
     * @param type $title
     * @return type
     */
    public function chkTb($modelName, $val, $id, $title) {
//        echo $val[$id];
//        exit();
        if (isset($val[$id])) {
            $modelPost = new $modelName();
            $model = $modelName::findOne([$id => $val[$id]]);
//            print_r($model);
//            exit();
            if ($model === NULL) {
                //$this->pr($model);
                $model = new $modelName();
                $model->$title = $val[$id];
                //$val[$title]=$val[$id];
//                echo $modelName;
//                exit();
                switch ($modelName) {
                    case 'ikhlas\product\models\Product':
                        $model->price = $val['price'];
                        $model->status = 1;
                        $model->created_at = time();
                        $model->created_by = Yii::$app->user->id;
                        break;
                }
                if (!$model->save(false)) {
                    print_r($model->getErrors());
                }
                return $model->id;
            } else {
                return $model->id;
            }
        }
    }

    #######################################

    public function actionReport($id, $download = NULL) {
        // get your HTML raw content without any layouts or scripts
        $model = $this->findModel($id);
        $content = $this->renderPartial('view', [
            'model' => $model,
            'modelDetail' => $model->creditDetails
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'filename' => Yii::$app->img->getUploadPath('credit') . 'credit_' . $model->id . '.pdf',
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => 'รายงาน',
                'subject' => 'รายงาน1',
                'keywords' => 'รายงาน2',
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['รายงานการขอยื่นจอง'],
                'SetFooter' => ['หน้าที่ {PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    
    public function actionDraft() {
        $searchModel = new CreditDraftSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('draft', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionOffer() {
        $searchModel = new CreditOfferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('offer', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionConsider() {
        $searchModel = new CreditConsiderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('consider', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionConsiderView($id) {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
                
        if ($model->load($post)) {
          
            $modelConsider = new CreditConsider();
            $modelConsider->status = $post['CreditConsider']['status'];
            $modelConsider->comment = $post['CreditConsider']['comment'];
            $modelConsider->credit_id = $model->id;
            $modelConsider->created_by = Yii::$app->user->id;
            $modelConsider->created_at = time();
            
            switch ($modelConsider->status) {
                case 0:
                    $model->status = 5;
                    break;
                case 1: 
                    $model->status = 3;
                    $modelContract = new Contract();
                    $modelContract->credit_id = $model->id;
                    $modelContract->status = 1;//1=คงสัญญา
                    $modelContract->staff_id = Yii::$app->user->id;
                    $modelContract->created_at = time();
                    $modelContract->save(false);
                    break;
                case 2: 
                    $model->status = 4;
                    break;
            }
            $model->staff_id = Yii::$app->user->id;
            if ($model->save(false)) {
                if ($modelConsider->save(false)) {                   
                        Yii::$app->session->setFlash('success', 'บันทึกเรียบร้อย');
                        Yii::$app->notification->sent('เรียบร้อย', \yii\helpers\Url::to(['/consider/credit/view', 'id' => $model->id]), $model->seller);
                        return $this->redirect(['index']);
                    
                }
            }
        }



        return $this->render('view', [
                    'model' => $model,
                    'modelDetails' => $model->creditDetails,
                    'modelConsider' => $model->creditConsiders
        ]);
    }
    
    
    public function actionResult() {
        $searchModel = new CreditResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('result', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    
    
}
