<?php
use pollext\poll\AjaxSubmitButton;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<style>
    .poll{
        display: inline-block;
        margin-top: 10px;
        margin-bottom: 10px;
        background: #ffffff;
    }
    
    .poll label{
        width: 100%;
        font-size: 10pt;
        font-weight: bold;
        display: block;
        color: #464646;
    }
    
    .poll label:hover{
        cursor: pointer;
    }
    
    .poll button[type="submit"]{
        font-weight: bold;
        font-size: 10pt;
        margin-top: 10px;
        color: #4682B4;
    }
    
    .poll-option-name{
        font-weight: bold;
        font-size: 10pt;
        color: #464646;
    }
    .per_container{
        font-weight: bold;
        font-size: 10pt;
        color: #464646;
        padding: 0; 
        margin: 0;
        max-width: 50px;
    }
    
    .support_forms button[type="submit"]{
        border: none;
        font-weight: normal;
        color: #4682B4;
        margin-left: 0;
        padding: 0;
        background: #ffffff;
        
    }
    .support_forms button[type="submit"]:hover{
        text-decoration: underline;
    }
    .support_forms button[type="submit"]:focus{
        outline: none;
        border: none;
    }
    .support_forms{
        margin-top: 0;
    }
</style>

<div class="poll" style="width:<?php echo $params['maxLineWidth']+55;?>px;" >
    <?php  echo "<div style=\"max-width:".$params['maxLineWidth']."px; word-wrap: break-word; margin-bottom: 10px; font-size:12pt; font-weight:bold;\">".$pollData['poll_name']."</div>";?>

    <?php if((Yii::$app->user->getId()==null && $_POST['pollStatus']!='show' && $isVote == false ) || ($_POST['nameOfPoll']==$pollData['poll_name'] && $_POST['pollStatus']=='vote'&& $_POST['pollStatus']!='show' && Yii::$app->user->getId()==null)){
        
        echo "Sign in to vote";
    }?>

    <?php
    if ((
            $isVote                     == false
            && Yii::$app->user->getId() != null
            && $_POST['pollStatus']     != 'show'
        ) || ( $_POST['nameOfPoll']     == $pollData['poll_name']
            && $_POST['pollStatus']     == 'vote'
            && $_POST['pollStatus']     != 'show'
            && Yii::$app->user->getId() != null
    )) {
   
        echo Html::beginForm('#', 'post', ['class'=>'uk-width-medium-1-1 uk-form uk-form-horizontal']);
        
        echo Html::textInput('poll_id', $pollData['poll_id']);
        echo Html::textInput('poll_name', $pollData['poll_name']);
        
        echo Html::activeRadioList($model,'voice',$answers);

        AjaxSubmitButton::begin([
            'label'         => 'Vote',
            'ajaxOptions'   => [
                'success' => new \yii\web\JsExpression('function(data){$("body").html(data);}'),
                'type'    => 'POST',
                'url'     => '#',
            ],
            'options'       => ['class' => 'customclass', 'type' => 'submit'],
        ]);
        AjaxSubmitButton::end();

        echo Html::endForm(); 
    } ?>

    <?php if(($isVote == false && $_POST['pollStatus']!='show') || (Yii::$app->user->getId()==null && $_POST['pollStatus']!='show') || ($_POST['nameOfPoll']==$pollData['poll_name'] && $_POST['pollStatus']=='vote'&& $_POST['pollStatus']!='show')){?>
        <form method="POST" action="" class="support_forms">
        <input type="hidden" name="nameOfPoll" value="<?=$pollData['poll_name']?>"/>
        <input type="hidden" name="pollStatus" value="show"/>
         <?php AjaxSubmitButton::begin([
            'label'         => 'Show results',
            'ajaxOptions'   => [
                'success' => new \yii\web\JsExpression('function(data){$("body").html(data);}'),
                'type'    => 'POST',
                'url'     => '#',
            ],
            'options'       => ['class' => 'customclass', 'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
        ?>
        </form>
    <?php }?>

    <?php if($isVote == true || ($_POST['nameOfPoll']==$pollData['poll_name'] && $_POST['pollStatus']=='show') ){?> 
        <?php 
            for($i = 0; $i<count($answersData); $i++){ 
                $voicesPer = 0;
                if($sumOfVoices ==0){
                    $voicesPer = 0;
                }else{
                    $voicesPer = round($answersData[$i]['value']/$sumOfVoices, 4);
                }
                
                $lineWidth = $params['maxLineWidth']*$voicesPer;     
        ?>
            
            <div class="single-line" style="margin-bottom: 10px; ">
                <?php echo "<div class=\"poll-option-name\">".$answersData[$i]['answers'].": ".$answersData[$i]['value']."</div>"; ?>
                <div  style="width: <?php echo $params['maxLineWidth']; ?>px;  height: <?php echo $params['linesHeight']; ?>px; background-color: <?php echo $params['backgroundLinesColor']; ?>; ">
                    <div style="width: <?php echo $lineWidth;?>px; height: <?php echo $params['linesHeight'] ?>px; background-color: <?php echo $params['linesColor']; ?>;">
                    <div class="per_container" style="display: block; line-height:<?php echo $params['linesHeight'] ?>px;  height: <?php echo $params['linesHeight'] ?>px;  
                    position: relative; left:<?php echo $params['maxLineWidth']+5; ?>px; margin: 0;"><?php echo ($voicesPer*100)."%"?></div>
                    </div>
                    
                </div>
            </div>
        <?php }?>
    <?php }?>

    <?php if ($isVote == false && $_POST['pollStatus']=='show'){?>
        <form method="POST" action="" class="support_forms" style="margin-top: -10px;">
        <input type="hidden" name="nameOfPoll" value="<?=$pollData['poll_name']?>"/>
        <input type="hidden" name="pollStatus" value="vote"/>
        <?php AjaxSubmitButton::begin([
            'label'         => 'Vote',
            'ajaxOptions'   => [
                'success' => new \yii\web\JsExpression('function(data){$("body").html(data);}'),
                'type'    => 'POST',
                'url'     => '#',
            ],
            'options'       => ['class' => 'customclass', 'type' => 'submit'],
            ]);
            AjaxSubmitButton::end();
        ?>
    <?php }?>

   </form>
</div>


