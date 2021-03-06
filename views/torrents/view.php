<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Torrents */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Torrents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="torrents-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'licencia_id',
            'categoria_id',
            'usuario_id',
            'titulo',
            'resumen',
            'descripcion',
            'imagen',
            'file',
            'size',
            'magnet',
            'password',
            'md5',
            'n_descargas',
            'online:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
