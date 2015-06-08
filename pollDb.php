<?php

namespace pollext\poll;

use yii;

class PollDb {

    /**
     * [isPollExist description]
     * 
     * @param  [type]  $pollName [description]
     * @param  [type]  $pollName [description]
     * @return boolean           [description]
     */
    public function isPollExist($pollName)
    {
        $db = Yii::$app->db;
        $command = $db
            ->createCommand('SELECT * FROM poll WHERE poll_name=:pollName')
            ->bindParam(':pollName',$pollName);
        
        $pollData = $command->queryOne();

        if ($pollData==null) {
            return false;
        } else {
            return true;
        }
    }

    public function setVoicesData($pollId, $answerOptions)
    {
        $db = Yii::$app->db;
        $answersList = array();
        
        for($i=0; $i<count($answerOptions); $i++){
           $command = $db->createCommand()->insert('poll_response', [
            'answers' => $answerOptions[$i],
            'poll_id' => $pollId,
            'value'   => 0,
            ])->execute(); 
        }   
    }

    public function getVoicesData($pollId)
    {
        $db         = Yii::$app->db;
        $command    = $db
            ->createCommand('SELECT * FROM poll_response WHERE poll_id = :pollId')
            ->bindParam(':pollId',$pollId);
        $voicesData = $command->queryAll();
        return $voicesData;
    }

    public function updateAnswers($pollId, $voise, $answerOptions)
    {
        if(isset($_POST['PollResponse']['voice'])){
            $db = Yii::$app->db;
            ;
            /*if(array_search(Yii::$app->user->getId(),$ids)==false){
                
            }else{
                return false;
            }
            */
            
            $command = $db->createCommand("
                UPDATE poll_response
                SET value = value +1  
                WHERE poll_id = '$pollId'
                    AND answers = '$answerOptions[$voise]'")
                ->execute();
            
        }
    }

    public function updateUsers($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll WHERE poll_name=:pollName')->
        bindParam(':pollName',$pollName);
        $userId;
        if(Yii::$app->user->getId()==null){
            $userId = 0;
        }else{
            $userId = Yii::$app->user->getId(); 
        }
        $pollData = $command->queryOne();
        $command = $db->createCommand()->insert('poll_user', [
            'poll_id' => $pollData['id'],
            'user_id' => $userId
        ])->execute(); 
    }

    public  function isVote($pollName)
    {
        $db = Yii::$app->db;
        $command = $db->createCommand('SELECT * FROM poll WHERE poll_name=:pollName')->
        bindParam(':pollName',$pollName);
        $pollData = $command->queryOne();
        $userId;
        if(Yii::$app->user->getId()== null){
            $userId = 0;
        }else{
            $userId = Yii::$app->user->getId(); 
        }
        $db = Yii::$app->db;
        $command = $db->createCommand("SELECT * FROM poll_user WHERE user_id='$userId' AND poll_id=:pollId")->
        bindParam(':pollId',$pollData['id']);
        $result = $command->queryOne();
        
        if($result == null){
            return false;
        }else{
            return true;
        }
    }

    public function createTables()
    {
        $db = Yii::$app->db;
        $command_1 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll_user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `poll_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `poll_id` (`poll_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8")
            ->execute();
                    
        $command_2 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `poll_name` varchar(500) NOT NULL,
            `answer_options` text NOT NULL,
            PRIMARY KEY (`id`),
            KEY `poll_name` (`poll_name`(255))
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
            )->execute();

        $command_3 = $db->createCommand("
            CREATE TABLE IF NOT EXISTS `poll_response` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `poll_id` int(11) NOT NULL,
            `answers` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
            `value` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8"
            )->execute();
    }

    public function isTableExists()
    {
        $db      = Yii::$app->db;
        $command = $db->createCommand("SHOW TABLES LIKE 'poll'");
        $res     = $command->queryAll();
        return $res;
    }
}
