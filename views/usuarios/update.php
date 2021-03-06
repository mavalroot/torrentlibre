<?php

use app\assets\UsuariosCreateAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Modificar Usuario ' . $model->nombre . ' ('. $model->nick . ')';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view',
    'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Usuario';

// Registro assets para esta vista
UsuariosCreateAsset::register($this);
?>
<div class="usuarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
