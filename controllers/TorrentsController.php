<?php

namespace app\controllers;

use app\models\Categorias;
use app\models\Licencias;
use app\models\Torrents;
use app\models\TorrentsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * TorrentsController implements the CRUD actions for Torrents model.
 */
class TorrentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'delete', 'update'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Torrents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TorrentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Torrents model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Torrents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Torrents([
            'usuario_id' => Yii::$app->user->identity->id,
            'n_descargas' => 0,
        ]);

        // En el caso de existir datos mediante POST los proceso
        if ($model->load(Yii::$app->request->post())) {
            $model->u_img = UploadedFile::getInstance($model, 'u_img');
            $model->u_torrent = UploadedFile::getInstance($model, 'u_torrent');

            // Es obligatorio que haya un torrent para continuar
            if ($model->u_torrent !== null) {
                $nombre = $model->u_torrent->baseName . '.' .
                          $model->u_torrent->extension;
                $model->size = $model->u_torrent->size;
                $model->md5 = md5_file($model->u_torrent->tempName);
                $model->file = $model->md5 . '-' . $nombre;

                // Guardo imagen si existiera
                if ($model->u_img !== null) {
                    $model->imagen = $model->md5 . '-' .
                        $model->u_img->baseName . '.' .
                        $model->u_img->extension;
                }

                // Guardo modelo y subo archivos
                if ($model->save() &&
                    $model->uploadTorrent() &&
                    $model->uploadImg()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Es obligatorio el archivo torrent');
                $model->addError(
                    'u_torrent',
                    'Es obligatorio agregar un Torrent válido'
                );
            }
        }

        $licencias = Licencias::getAll();
        $categorias = Categorias::getAll();

        return $this->render('create', [
            'model' => $model,
            'licencias' => $licencias,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Updates an existing Torrents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $licencias = Licencias::getAll();
        $categorias = Categorias::getAll();

        return $this->render('update', [
            'model' => $model,
            'licencias' => $licencias,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Deletes an existing Torrents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Torrents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Torrents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Torrents::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }

    public function actionAumentardescargas()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        if (Yii::$app->request->isAjax || Yii::$app->request->isPost) {
            $model->n_descargas += 1;
            $model->save();
        }
        echo $model->n_descargas;

        //return false;
    }

    public function actionRefrescardescargas()
    {
        header('Content-type: application/json');
        $x = ['x' => 20];

        echo json_encode($x);
    }
}
