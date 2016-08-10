<?php

namespace ikhlas\seller\controllers;

use Yii;
use ikhlas\seller\models\RegisterSeller;
use ikhlas\seller\models\RegisterSellerSearch;
use ikhlas\seller\models\RegisterSellerDraftSearch;
use backend\modules\persons\models\Person;
use backend\modules\persons\models\PersonDetail;
use backend\modules\persons\models\Address;
use backend\modules\persons\models\ContactAddress;
use backend\modules\persons\models\PersonContact;
use backend\modules\persons\models\PersonCareer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RegisterController implements the CRUD actions for RegisterSeller model.
 */
class RegisterController extends Controller {

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
     * Lists all RegisterSeller models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RegisterSellerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RegisterSeller model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $detail = RegisterSeller::getDetailRegister($id);
        return $this->render('view', [
                    'model' => $detail['model'],
                    'modelPerson' => $detail['modelPerson'],
                    'modelPersonDetail' => $detail['modelPersonDetail'],
                    'modelAddress' => $detail['modelAddress'],
                    'modelContactAddress' => $detail['modelContactAddress'],
                    'modelPersonContact' => $detail['modelPersonContact'],
                    'modelPersonCareer' => $detail['modelPersonCareer'],
        ]);
    }

    public function actionConfirm($id) {
        $detail = RegisterSeller::getDetailRegister($id);

        $model = $detail['model'];
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->staff_date = time();
            if ($model->staff_receive == 1) {
                $model->status = 3;
            } elseif ($model->staff_receive == 2) {
                $model->status = 4;
            }
            if ($model->save(false)) {



                $modelProgress = new \ikhlas\seller\models\RegisterSellerProgress();
                $modelProgress->status = $model->staff_receive;
                $modelProgress->register_seller_id = $model->id;
                $modelProgress->created_by = Yii::$app->user->id;
                $modelProgress->created_at = time();
                $modelProgress->data = [
                    'doc_fully' => $post['RegisterSeller']['doc_fully'],
                    'doc_because' => $post['RegisterSeller']['doc_because'],
                    'staff_receive' => $post['RegisterSeller']['staff_receive'],
                    'class' => $post['RegisterSeller']['class'],
                    'receive_because' => $post['RegisterSeller']['receive_because'],
                ];

                if ($modelProgress->save(false)) {
                    Yii::$app->session->setFlash('success', 'บันทึกเรียบร้อย');
                    Yii::$app->notification->sent($model->statusLabelString, \yii\helpers\Url::to(['view', 'id' => $model->id]), $model->staff);
                    if ($model->status == 3) {
                        return $this->redirect(['/seller/default/create', 'register_seller_id' => $model->id]);
                    } else {
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'พบปัญหา');
                }
            } else {
                print_r($model->getErrors());
                //$err=implode(' ',$registerCustomer->getErrors());
                Yii::$app->session->setFlash('error', 'พบปัญหา');
            }
        }

        return $this->render('confirm', [
                    'model' => $detail['model'],
                    'modelPerson' => $detail['modelPerson'],
                    'modelPersonDetail' => $detail['modelPersonDetail'],
                    'modelAddress' => $detail['modelAddress'],
                    'modelContactAddress' => $detail['modelContactAddress'],
                    'modelPersonContact' => $detail['modelPersonContact'],
                    'modelPersonCareer' => $detail['modelPersonCareer'],
        ]);
    }

    /**
     * Creates a new RegisterSeller model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null) {
        if ($id === NULL) {
            $model = new RegisterSeller();
            $model->status = 0;
            $model->created_at = time();
            $model->staff_id = Yii::$app->user->id;
            if ($model->save(false)) {
                $this->redirect(['create', 'id' => $model->id]);
            } else {
                print_r($model->getErrors());
            }
        } else {
            $model = RegisterSeller::findOne($id);
            $modelPerson = $model->person_id ? $model->person : new Person();
            $modelPersonDetail = $modelPerson->personDetail ? $modelPerson->personDetail : new PersonDetail();
            $modelAddress = $modelPerson->address_id ? $modelPerson->address : new Address();
            $modelContactAddress = $modelPerson->contact_address_id ? $modelPerson->contactAddress : new ContactAddress();
            $modelContactAddress->contactBy = ($modelPerson->address_id == $modelPerson->contact_address_id) ? 1 : 2;
            $modelPersonContact = $modelPerson->personContact ? $modelPerson->personContact : new PersonContact();
            $modelPersonCareer = $modelPerson->personCareer ? $modelPerson->personCareer : new PersonCareer();



            $post = Yii::$app->request->post();
            //print_r($post);
            if ($model->load($post)) {

                // ที่อยู่ตามสำเนา
                if ($modelAddress->load($post)) {
                    //$modelPersonDetail->person_id = $modelPerson->id;
                    if ($modelAddress->save()) {
                        $modelPerson->address_id = $modelAddress->id;
                    } else {
                        print_r($modelAddress->getError());
                    }
                }
                // ที่อยู่ที่ติดต่อได้
                if ($modelContactAddress->load($post)) {
                    //$modelPersonDetail->person_id = $modelPerson->id;
                    if ($modelContactAddress->save()) {
                        $modelPerson->contact_address_id = $modelContactAddress->id;
                    } else {
                        print_r($modelContactAddress->getError());
                    }
                }

                // ข้อมูลทั่วไป
                if ($modelPerson->load($post)) {
                    $modelPerson->id_card = str_replace('-', '', $modelPerson->id_card);

                    if ($modelPerson->save()) {
                        $model->person_id = $modelPerson->id;
                    } else {
                        print_r($modelPerson->getError());
                    }
                }

                // ข้อมูลทั่วไป-รายละเอียด
                if ($modelPersonDetail->load($post)) {
                    $modelPersonDetail->person_id = $modelPerson->id;
                    if ($modelPersonDetail->save()) {
                        
                    } else {
                        print_r($modelPersonDetail->getError());
                    }
                }

                if ($modelPersonContact->load($post)) {
                    $modelPersonContact->person_id = $modelPerson->id;
                    if ($modelPersonContact->save()) {
                        
                    } else {
                        print_r($modelPersonContact->getError());
                    }
                }

                if ($modelPersonCareer->load($post)) {
                    $modelPersonCareer->person_id = $modelPerson->id;
                    $modelPersonCareer->salary = str_replace(',','',$post['PersonCareer']['salary']);
                    if ($modelPersonCareer->save()) {
                        
                    } else {
                        print_r($modelPersonCareer->getError());
                    }
                }

                #############################
                if ($model->save()) {
                    if (isset($post['btnConfirm'])) {
                        return $this->redirect(['confirm', 'id' => $model->id]);
                    }
                    Yii::$app->session->setFlash('success', 'บันทึกเรียบร้อย');
                } else {
                    print_r($model->getErrors());
                    //$err=implode(' ',$registerCustomer->getErrors());
                    Yii::$app->session->setFlash('error', 'พบปัญหา');
                }
            }
            return $this->render('create', [
                        'model' => $model,
                        'modelPerson' => $modelPerson,
                        'modelPersonDetail' => $modelPersonDetail,
                        'modelAddress' => $modelAddress,
                        'modelContactAddress' => $modelContactAddress,
                        'modelPersonContact' => $modelPersonContact,
                        'modelPersonCareer' => $modelPersonCareer,
            ]);
        }
    }

    /**
     * Lists all RegisterSeller models.
     * @return mixed
     */
    public function actionDraft() {
        $searchModel = new RegisterSellerDraftSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('draft', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing RegisterSeller model.
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
     * Deletes an existing RegisterSeller model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['draft']);
    }

    /**
     * Finds the RegisterSeller model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegisterSeller the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RegisterSeller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
