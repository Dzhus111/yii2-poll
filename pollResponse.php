<?php

namespace pollext\poll;

use yii\base\Model;
    
class PollResponse extends Model{
    public $voice;
    public $type;
    
    public function attributeLabels()
    {
        return [
            'type'  => '',
            'voice' => '',
        ];
    }
}
