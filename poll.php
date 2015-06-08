<?php

namespace pollext\poll;

use yii;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\Html;

class Poll extends Widget {

    private $answerOptionsData;
    private $pollData;
    private $pollId       = null;
    private $pollName     = null;
    private $usersIPs     = array();

    public $answerOptions = array();
    public $answers       = array();
    public $isExist;
    public $isVote;
    public $sumOfVoices   = 0;

    private $params = array(
        'backgroundLinesColor' => '#D3D3D3',
        'linesColor'           => '#4F9BC7',
        'linesHeight'          => 15,
        'maxLineWidth'         => 300,
    );

    /**
     * [setPollName description]
     *
     * @param [type] $name [description]
     */
    public function setPollName($name)
    {
        $this->pollName = $name;
    }

    /**
     * [setPollId description]
     * 
     * @param [type] $id [description]
     */
    public function setPollId($id)
    {

        $this->pollId = $id;
    }

    /**
     * [getDbData description]
     * 
     * @return [type] [description]
     */
    public function getDbData()
    {
        $db = Yii::$app->db;

        $command = $db
            ->createCommand('SELECT * FROM poll WHERE poll_name=:pollName')
            ->bindParam(':pollName',$this->pollName);
        
        $this->pollData = $command->queryOne();

        $this->answerOptionsData = unserialize($this->pollData['answer_options']);
    }

    /**
     * [setDbData description]
     */
    private function setDbData()
    {
        Yii::$app->db->createCommand()->insert('poll', [
            'answer_options' => $this->answerOptionsData,
            'poll_name'      => $this->pollName,
        ])->execute();



        $query = new Query;
        // compose the query
        $id = $query->select('id')
            ->from('poll')
            ->limit(1)
            ->one();
        return $id['id'];
    }
    
    public function setParams($params)
    {

        $this->params = array_merge($this->params, $params);
    }
    
    public function getParams($param)
    {

        return $this->params[$param];
    }
    
    public function init()
    {
        parent::init();
        
        $pollDB = new PollDb;
        $this->isExist = $pollDB->isTableExists();

        if(count($this->isExist)==0){
            $pollDB->createTables();
        }

        if($this->answerOptions != null){
            $this->answerOptionsData = serialize($this->answerOptions);
        }

        if(!$pollDB->isPollExist($this->pollName)){

            $this->pollId = $this->setDbData()['id'];
            $pollDB->setVoicesData($this->pollId, $this->answerOptions);
        } else {

            $this->setPollID($_POST['poll_id']);
        }

        if(Yii::$app->request->isAjax){
            if(isset($_POST['PollResponse'])){

                if($_POST['poll_id'] == $this->pollId){
                    $pollDB->updateAnswers(
                        $this->pollId,
                        $_POST['PollResponse']['voice'],
                        $this->answerOptions
                    );

                    $pollDB->updateUsers($this->pollName);
                }    
            }
        }

        $this->getDbData();

        $this->answers = $pollDB->getVoicesData($this->pollId);
        
        for ($i=0; $i<count($this->answers); $i++) {
             $this->sumOfVoices = $this->sumOfVoices + $this->answers[$i]['value'];
        }

        $this->isVote = $pollDB->isVote($this->pollName);
    }
    
    public function run()
    {   
        $model = new PollResponse;
        return  $this->render('index', [
            'answers'     => $this->answerOptions,
            'answersData' => $this->answers,
            'isVote'      => $this->isVote,
            'model'       => $model,
            'params'      => $this->params,
            'pollData'    => $this->pollData,
            'sumOfVoices' => $this->sumOfVoices,
        ]);
    }
}
