<?php
/*******************************************************************************
 *             Copy Right (c) 2020 Capsheaf Co., Ltd.
 *
 *  Author:          Archibald<1961395960@qq.com>
 *  Date:            2020-08-20 16:10:09 CST
 *  Description:     mainProcess.php's function description
 *  Version:         1.0.0.20200820-alpha
 *  History:
 *        Archibald<1961395960@qq.com> 2020-08-20 16:10:09 CST initialized the file
 ******************************************************************************/


echo "start mainProcess" . PHP_EOL;

if (is_file("/tmp/swoole/start")){
    //进程1操作
    echo "master start sub1 sub2" . PHP_EOL;
    $sub1 = new swoole_process("sub1", false, true);
    $sub1->start();

    //进程2操作
    $sub2 = new swoole_process("sub2", false, true);
    $sub2->start();

    @unlink("/tmp/swoole/start");
}

while(true){

    if (is_file("/tmp/swoole/stop")){
        $sub1->write("stop");
        $sub2->write("stop");
    }

    if (is_file("/tmp/swoole/pause")){
        $sub1->write("pause");
        $sub2->write("pause");
    }

    sleep(5);
    echo "master nothing to do" . PHP_EOL;
}




function sub1($process)
{
    echo "sub1 started" . PHP_EOL;

    while(true){
        $sStatus = $process->read();
        echo "sub1 get status {$sStatus}" . PHP_EOL;
        if ($sStatus == "pause"){
            echo "sub1 paused" . PHP_EOL;
        } elseif ($sStatus == "stop"){
            echo "sub1 stop" . PHP_EOL;
            $process->exit(0);
            break;
        } else {
            echo "sub1 do nothing" . PHP_EOL;
        }

        sleep(5);
    }
}





function sub2($process)
{
    echo "sub2 started" . PHP_EOL;

    while(true){
        $sStatus = $process->read();
        echo "sub2 get status {$sStatus}" . PHP_EOL;
        if ($sStatus == "pause"){
            echo "sub2 paused" . PHP_EOL;
        } elseif ($sStatus == "stop"){
            echo "sub2 stop" . PHP_EOL;
            $process->exit(0);
            break;
        } else {
            echo "sub2 do nothing" . PHP_EOL;
        }

        sleep(5);
    }
}


