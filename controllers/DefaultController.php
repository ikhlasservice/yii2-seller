<?php

namespace ikhlas\seller\controllers;

use Yii;
use ikhlas\seller\models\Seller;
use ikhlas\seller\models\SellerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use ikhlas\persons\models\Person;
use ikhlas\persons\models\PersonDetail;
use ikhlas\persons\models\Address;
use ikhlas\persons\models\ContactAddress;
use ikhlas\persons\models\PersonContact;
use ikhlas\persons\models\PersonCareer;
use ikhlas\seller\models\RegisterSeller;
use common\models\User;

/**
 * DefaultController implements the CRUD actions for Seller model.
 */
class DefaultController extends \anda\core\controllers\AuthenController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Seller models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SellerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Seller model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Seller model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($register_seller_id = null, $id = null) {
        if ($register_seller_id != null) {
            $modelRegisterSeller = RegisterSeller::find()->where(['id' => $register_seller_id, 'status' => 3])->one();
            if (!$modelRegisterSeller) {
                Yii::$app->session->setFlash('warning', 'ไม่พบรหัสนี้');
                return $this->redirect(['index', 'id' => $model->id]);
            }
            $model = new Seller();
            //$model->id = Seller::getLastId();
            $model->register_seller_id = $modelRegisterSeller->id;
            $model->status = 0;
            $model->person_id = $modelRegisterSeller->person_id;
            $model->staff_id = Yii::$app->user->id;
            $model->created_at = time();
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'ระบบได้สร้างรหัสตัวแทนแล้ว');
                return $this->redirect(['create', 'id' => $model->id]);
            }
        } elseif ($id != null) {
            $model = $this->findModel($id);
            $modelUser = new User();
            if ($model->user_id != null) {
                Yii::$app->session->setFlash('warning', 'ตัวแทนนี้ได้สร้างชื่อเข้าใช้แล้ว');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $post = Yii::$app->request->post();
            if ($modelUser->load($post)) {
                $modelUser->passwordShow = $modelUser->password;
                $modelUser->setPassword($modelUser->password);
                $modelUser->generateAuthKey();
                $modelUser->status = User::STATUS_ACTIVE;
                //$modelUser->person_id = $model->person_id;

//                print_r($post);
//                exit();
                if ($modelUser->save()) {
                    $role = new \common\rbac\models\Role();
                    $role->user_id = $modelUser->getId();
                    $role->item_name = 'seller';
                    $role->created_at = time();
                    $role->save();
                    
                    $modelPerson = Person::findOne($model->person_id);
                    $modelPerson->user_id = $modelUser->getId();
                    $modelPerson->save(false);


                    $model->account_id = str_replace('-', '', $post['Seller']['account_id']);
                    $model->user_id = $modelUser->id;
                    $model->user_id = $modelUser->id;
                    $model->status = 1;
                    if ($model->save(false)) {
                        Yii::$app->session->setFlash('success', 'บันทึกเรียบร้อย');
                        Yii::$app->notification->sentNewAccout('สร้างบัญชีใหม่เรียบร้อย', $modelUser);
                        return $this->redirect('index');
                    }
                }
            } else {
                $username = explode('@', $model->person->email);
                $modelUser->displayname = $model->person->fullname;
                $modelUser->email = $model->person->email;
                $modelUser->username = $username[0];
                $modelUser->password = $model->person->id_card;
                $model->account_name = $model->person->fullname;
            }
            return $this->render('create', [
                        'model' => $model,
                        'modelPerson' => $model->person,
                        'modelUser' => $modelUser
            ]);
        }
    }

    public function actionRegister($id = null) {
        if ($id === NULL) {
            $model = new RegisterSeller();
            $model->status = 0;
            $model->created_at = time();
            $model->staff_id = Yii::$app->user->id;
            if ($model->save(false)) {
                $this->redirect(['register', 'id' => $model->id]);
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
            return $this->render('register', [
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
     * Updates an existing Seller model.
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
     * Deletes an existing Seller model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Seller model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seller the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Seller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

########################################
########################################

    private function uploadMultipleFile($model, $tempFile = null) {
        $files = [];
        $json = '';
        $tempFile = Json::decode($tempFile);
        $UploadedFiles = UploadedFile::getInstances($model, 'docs');
        if ($UploadedFiles !== null) {
            foreach ($UploadedFiles as $file) {
                try {
                    $oldFileName = $file->basename . '.' . $file->extension;
                    $newFileName = md5($file->basename . time()) . '.' . $file->extension;
                    $file->saveAs(Freelance::UPLOAD_FOLDER . '/' . $model->ref . '/' . $newFileName);
                    $files[$newFileName] = $oldFileName;
                } catch (Exception $e) {
                    
                }
            }
            $json = json::encode(ArrayHelper::merge($tempFile, $files));
        } else {
            $json = $tempFile;
        }
        return $json;
    }

}
