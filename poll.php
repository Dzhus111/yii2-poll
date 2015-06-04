<?php

    namespace pollext\poll;
    use yii;
    use yii\base\Widget;
    use yii\helpers\Html;
    
    class Poll extends Widget{
        
        private $params = array(
            'maxLineWidth'=>300,
            'backgroundLinesColor'=>'#D3D3D3',
            'linesHeight'=>15,
            'linesColor'=>'#4F9BC7'
        );
        
        private $pollName='';
        public $answerOptions=array();
        private $answerOptionsData;
        private $pollData;
        public $answers = array();
        private $usersIPs = array();
        public $sumOfVoices = 0;
        public $isVote;
        public $isExist;
        
        public function setPollName($name) {
            $this->pollName = $name;
        }
        
        public function getDbData(){
            
                $db = Yii::$app->db;
                
                $command = $db->createCommand('SELECT * FROM polls WHERE poll_name=:pollName')->
                bindParam(':pollName',$this->pollName);
                
                $this->pollData = $command->queryOne();
                $this->answerOptionsData = unserialize($this->pollData['answer_options']);
                
        }
        
        private function setDbData(){
            
                $db = Yii::$app->db;
                
                $c = $db->createCommand()->insert('polls', [
                    'poll_name' => $this->pollName,
                    'answer_options' => $this->answerOptionsData
                ])->execute();
        }
        
        public function setParams($params) {
            $this->params = array_merge($this->params, $params);
        }
        
        public function getParams($param){
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
                $this->setDbData();
                $pollDB->setVoicesData($this->pollName, $this->answerOptions);
            }
            if(Yii::$app->request->isAjax){
                if(isset($_POST['PollResponse'])){
                    if($_POST['poll_name']==$this->pollName){
                       $pollDB->updateAnswers($this->pollName, $_POST['PollResponse']['voice'], 
                        $this->answerOptions);
                        $pollDB->updateUsers($this->pollName);
                    }    
                }
                
            }
            $this->getDbData();
            $this->answers = $pollDB->getVoicesData($this->pollName);
            
            for($i=0; $i<count($this->answers); $i++){
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