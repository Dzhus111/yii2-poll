yii2-poll ext
=============

Poll widget for yii2

The Poll widget for the Yii2 framework allows you to create custom poll for authenticated users to vote on.

Installing 
==========

php composer.phar require --prefer-dist davidjeddy/yii2-pollext "*"

That's all. The widget uses mysql database. But you do not need to create the tables. The widget itself will create all the necessary tables in your database when and as needed.

Usage 
=====

At first, import widget classes in view file where you want create poll

use davidjeddy\poll\Poll;

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
            'backgroundLinesColor' => '#DCDCDC', //html hex 
            'linesColor'           => '#DC0079', // html hex 
            'linesHeight'          => 20, // in pixels
            'maxLineWidth'         => 200, // in pixels
        ]
    ]); 
    
So, now you can fast and easy create the poll

If you want to change the styles for the poll form, you can do it directly in the view file of poll widget in @apps\vendor\davidjeddy\yii2-poll\views\index.php
