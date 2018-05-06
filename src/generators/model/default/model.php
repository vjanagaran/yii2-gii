<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>
/*
 * Project : <?= Yii::$app->name ?><?= "\n" ?>
 * Class : <?= $generator->ns ?>\<?= $className ?><?= "\n" ?>
 * Author : Janagaran V (vjanagaran@gmail.com)
 * Created : <?= date("d-M-Y H:i") ?><?= "\n" ?>
 */
 
namespace <?= $generator->ns ?>;

use Yii;
<?php if (isset($properties['created_at']) || isset($properties['updated_at'])): ?>
use yii\behaviors\TimestampBehavior;
<?php endif; ?>
<?php if (isset($properties['created_by']) || isset($properties['updated_by'])): ?>
use yii\behaviors\BlameableBehavior;
<?php endif; ?>

/**
 *
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') ?> {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>() {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>

<?php if (isset($properties['created_by']) || isset($properties['created_at'])
        || isset($properties['updated_by']) || isset($properties['updated_at'])):  ?>

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
<?php if (isset($properties['created_at']) || isset($properties['updated_at'])):?>
            'timestamp' => [
                'class' => TimestampBehavior::className(),
<?php if (isset($properties['created_at'])):?>
                'createdAtAttribute' => 'created_at',
<?php else :?>
                'createdAtAttribute' => false,
<?php endif; ?>
<?php if (isset($properties['updated_at'])):?>
                'updatedAtAttribute' => 'updated_at',
<?php else :?>
                'updatedAtAttribute' => false,
<?php endif; ?>
                'value' => new Expression('NOW()'),
            ],
<?php endif; ?>
<?php if (isset($properties['created_by']) || isset($properties['updated_by'])):?>
            'blameable' => [
                'class' => BlameableBehavior::className(),
<?php if (isset($properties['created_by'])):?>
                'createdByAttribute' => 'created_by',
<?php else :?>
                'createdByAttribute' => false,
<?php endif; ?>
<?php if (isset($properties['updated_by'])):?>
                'updatedByAttribute' => 'updated_by',
<?php else :?>
                'updatedByAttribute' => false,
<?php endif; ?>
                'value' => '\\Yii::$app->admin->id',
            ],
<?php endif; ?>
        ];
    }
<?php endif; ?>
    
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find() {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
