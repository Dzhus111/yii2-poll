# yii2-poll
Poll widget for yii2

The Poll widget for the Yii2 framework allows you to create custom poll for authenticated users to vote on.

Installing 

Put the widget directory in apps\basic\vendor\yiisoft

To autoloading classes of widget you need add alias in extensions.php which is located in apps\basic\vendor\yiisoft

  array (
    'name' => 'yiisoft/yii2-poll',
    'alias' =>
        array (
            '@pollext/poll' => $vendorDir . '/yiisoft/yii2-poll',
            )
      )
  
That's all. The widget uses mysql database. But you do not need to create tables. Widget itself will create all the necessary tables in your database.

Usage 

At first, import widget classes in view file where you want create poll

use pollext\poll\Poll;
Then invoke the widget and specify the name of the poll and the response options


        echo Poll::widget([
                'pollName'=>'Do you like PHP?',
                'answerOptions'=>
                [
                    'Yes',
                    'No',
                ],
            ]); 
    
    
That's all. You will see poll with standard parameters. But you can set your parameters. You can change max width of lines, color, height and background color for lines.


        echo Poll::widget([
                'pollName'=>'Do you like PHP?',
                'answerOptions'=>
                [
                    'Yes',
                    'No',
                ],
                'params'=>
                [
                'maxLineWidth'=> 200, // in pixels
                'backgroundLinesColor'=>'#DCDCDC', //html hex 
                'linesHeight'=>20, // in pixels
                'linesColor'=>'#DC0079' // html hex 
 
                ]
            ]); 
    
So, now you can fast and easy create the poll

If you want to change the styles for the poll form, you can do it directly in the view file of poll widget in apps\basic\vendor\yiisoft\yii2-poll\views\index.php
